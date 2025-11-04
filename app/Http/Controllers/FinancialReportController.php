<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\City;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
