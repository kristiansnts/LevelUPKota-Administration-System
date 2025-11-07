<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - Financial Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #c41e3a;
        }
        
        .header h1 {
            font-size: 24px;
            color: #c41e3a;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            font-style: italic;
            color: #666;
        }
        
        .report-type {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #c41e3a;
        }
        
        .report-type strong {
            color: #c41e3a;
        }
        
        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
        }
        
        .filter-info p {
            margin: 3px 0;
            font-size: 10px;
        }
        
        .section-title {
            background-color: #c41e3a;
            color: white;
            padding: 8px 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        thead {
            background-color: #f5f5f5;
        }
        
        th {
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .amount-positive {
            color: #28a745;
            font-weight: bold;
        }
        
        .amount-negative {
            color: #dc3545;
            font-weight: bold;
        }
        
        .total-row {
            background-color: #e8e8e8 !important;
            font-weight: bold;
        }
        
        .balance-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border: 2px solid #c41e3a;
            text-align: center;
        }
        
        .balance-section h2 {
            color: #c41e3a;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .balance-amount {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Laporan Keuangan - LevelUP Ngawi</h1>
        <p>Financial Report</p>
    </div>

    <!-- Report Type -->
    <div class="report-type">
        <strong>Jenis Laporan / Report Type:</strong> {{ $filters['report_type'] }}
    </div>

    <!-- Filter Information -->
    @if($filters['start_date'] || $filters['end_date'])
    <div class="filter-info">
        <p><strong>Periode / Period:</strong></p>
        @if($filters['start_date'])
        <p>Tanggal Mulai / Start Date: {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }}</p>
        @endif
        @if($filters['end_date'])
        <p>Tanggal Akhir / End Date: {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}</p>
        @endif
    </div>
    @endif

    <!-- Income Section -->
    <div class="section-title">Dana Masuk / Income</div>
    @if($incomeTransactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Tanggal / Date</th>
                    <th style="width: 15%;">Kategori / Category</th>
                    <th style="width: 25%;">Deskripsi / Description</th>
                    <th style="width: 13%;">Metode / Method</th>
                    <th style="width: 15%;">Jumlah / Amount</th>
                    <th style="width: 15%;">Kode Invoice</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incomeTransactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $transaction->transactionCategory->name ?? '-' }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->paymentMethod->name ?? '-' }}</td>
                    <td class="amount-positive">Rp {{ number_format(round($transaction->amount / 100), 0, ',', '.') }}</td>
                    <td>{{ $transaction->invoice_code }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5" style="text-align: right;">Total Dana Masuk / Total Income:</td>
                    <td colspan="2" class="amount-positive">Rp {{ number_format(round($totalIncome), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data dana masuk / No income data</div>
    @endif

    <!-- Expense Section -->
    <div class="section-title">Dana Keluar / Expense</div>
    @if($expenseTransactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Tanggal / Date</th>
                    <th style="width: 15%;">Kategori / Category</th>
                    <th style="width: 25%;">Deskripsi / Description</th>
                    <th style="width: 13%;">Metode / Method</th>
                    <th style="width: 15%;">Jumlah / Amount</th>
                    <th style="width: 15%;">Kode Invoice</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenseTransactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $transaction->transactionCategory->name ?? '-' }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->paymentMethod->name ?? '-' }}</td>
                    <td class="amount-negative">Rp {{ number_format(round($transaction->amount / 100), 0, ',', '.') }}</td>
                    <td>{{ $transaction->invoice_code }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5" style="text-align: right;">Total Dana Keluar / Total Expense:</td>
                    <td colspan="2" class="amount-negative">Rp {{ number_format(round($totalExpense), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data dana keluar / No expense data</div>
    @endif

    <!-- Total Balance Section -->
    <div class="balance-section">
        <h2>Total Saldo / Total Balance</h2>
        <div class="balance-amount">Rp {{ number_format(round($totalBalance), 0, ',', '.') }}</div>
    </div>
</body>
</html>
