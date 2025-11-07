<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\City;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with([
            'transactionCategory', 
            'paymentMethod', 
            'report'
        ]);

        // Filter by report - default is NULL (no report), or filter by selected report
        if ($request->filled('report_id')) {
            $query->where('report_id', $request->report_id);
        } else {
            $query->whereNull('report_id');
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        // Calculate summary statistics for all filtered transactions
        $summaryQuery = Transaction::query();
        
        // Apply same report filter as main query
        if ($request->filled('report_id')) {
            $summaryQuery->where('report_id', $request->report_id);
        } else {
            $summaryQuery->whereNull('report_id');
        }
        
        if ($request->filled('start_date')) {
            $summaryQuery->where('transaction_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $summaryQuery->where('transaction_date', '<=', $request->end_date);
        }
        
        $totalIncome = (clone $summaryQuery)->where('amount', '>', 0)->sum('amount');
        $totalExpense = abs((clone $summaryQuery)->where('amount', '<', 0)->sum('amount'));
        $totalBalance = $summaryQuery->sum('amount');

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get all reports for the dropdown
        $reports = Report::orderBy('title')->get();

        return view('public.financial-report', compact('transactions', 'reports', 'totalIncome', 'totalExpense', 'totalBalance'));
    }

    public function exportPdf(Request $request)
    {
        $query = Transaction::with([
            'transactionCategory', 
            'paymentMethod', 
            'report'
        ]);

        // Filter by report
        if ($request->filled('report_id')) {
            $query->where('report_id', $request->report_id);
        } else {
            $query->whereNull('report_id');
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        $allTransactions = $query->orderBy('transaction_date', 'asc')->get();

        // Separate income and expense transactions
        $incomeTransactions = $allTransactions->filter(function($transaction) {
            return $transaction->transactionCategory && $transaction->transactionCategory->transaction_type === 'income';
        });

        $expenseTransactions = $allTransactions->filter(function($transaction) {
            return !$transaction->transactionCategory || $transaction->transactionCategory->transaction_type !== 'income';
        });

        // Calculate totals
        $totalIncome = $incomeTransactions->sum('amount') / 100;
        $totalExpense = abs($expenseTransactions->sum('amount')) / 100;
        $totalBalance = $totalIncome - $totalExpense;

        // Get report type name
        $reportType = 'Semua Laporan / All Reports';
        $reportSlug = 'semua-laporan';
        if ($request->filled('report_id')) {
            $report = Report::find($request->report_id);
            if ($report) {
                $reportType = $report->title;
                $reportSlug = \Illuminate\Support\Str::slug($report->title);
            }
        }

        $filters = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'report_type' => $reportType
        ];

        $pdf = Pdf::loadView('public.financial-report-pdf', compact(
            'incomeTransactions', 
            'expenseTransactions', 
            'totalIncome', 
            'totalExpense', 
            'totalBalance',
            'filters'
        ));

        // Build filename
        $filename = 'laporan-keuangan-lup-ngawi-' . $reportSlug;
        
        // Add date range to filename if filtered
        if ($request->filled('start_date') || $request->filled('end_date')) {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $filename .= '-' . date('Y-m-d', strtotime($request->start_date)) . '_' . date('Y-m-d', strtotime($request->end_date));
            } elseif ($request->filled('start_date')) {
                $filename .= '-from-' . date('Y-m-d', strtotime($request->start_date));
            } elseif ($request->filled('end_date')) {
                $filename .= '-until-' . date('Y-m-d', strtotime($request->end_date));
            }
        }
        
        $filename .= '.pdf';
        
        return $pdf->download($filename);
    }
}
