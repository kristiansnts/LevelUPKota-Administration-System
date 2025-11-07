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
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            background-color: #c41e3a;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 18px;
            font-style: italic;
            opacity: 0.9;
        }
        
        .filter-section {
            padding: 30px 20px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-primary {
            background-color: #c41e3a;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #a01729;
        }
        
        .btn-secondary {
            background-color: #666;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #555;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .content {
            padding: 20px;
        }
        
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .summary-card {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }
        
        .summary-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-card p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        
        thead {
            background-color: #c41e3a;
            color: white;
        }
        
        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        tbody tr:hover {
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
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin-top: 20px;
            padding: 20px 0;
            flex-wrap: wrap;
        }
        
        .pagination .page-link,
        .pagination .active,
        .pagination .disabled {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            display: inline-block;
            min-width: 40px;
            text-align: center;
        }
        
        .pagination .page-link:hover {
            background-color: #f0f0f0;
        }
        
        .pagination .active {
            background-color: #c41e3a;
            color: white;
            border-color: #c41e3a;
        }
        
        .pagination .disabled {
            color: #ccc;
            cursor: not-allowed;
            background-color: #f5f5f5;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .filter-form {
                grid-template-columns: 1fr;
            }
            
            .summary {
                grid-template-columns: 1fr;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Keuangan</h1>
            <p>Financial Report</p>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('laporan-keuangan') }}">
                <div class="filter-form">
                    <div class="form-group">
                        <label for="start_date">Tanggal Mulai / Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir / End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="report_id">Jenis Laporan / Report Type</label>
                        <select id="report_id" name="report_id">
                            <option value="">Semua Laporan / All Reports</option>
                            @foreach($reports as $report)
                                <option value="{{ $report->id }}" {{ request('report_id') == $report->id ? 'selected' : '' }}>
                                    {{ $report->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="filter-buttons">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('laporan-keuangan') }}" class="btn btn-secondary">Reset</a>
                    <button type="button" class="btn btn-success" onclick="exportPDF()">Cetak PDF</button>
                </div>
            </form>
        </div>
        
        <script>
        function exportPDF() {
            const params = new URLSearchParams();
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const reportId = document.getElementById('report_id').value;
            
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (reportId) params.append('report_id', reportId);
            
            window.location.href = '{{ route("laporan-keuangan.pdf") }}?' + params.toString();
        }
        </script>

        <!-- Content -->
        <div class="content">
            <!-- Summary Cards -->
            <div class="summary">
                <div class="summary-card">
                    <h3>Total Transaksi / Total Transactions</h3>
                    <p>{{ number_format($transactions->total()) }}</p>
                </div>
                <div class="summary-card">
                    <h3>Saldo / Balance</h3>
                    @php
                        $lastTransaction = $transactions->first();
                        $lastBalance = $lastTransaction ? $lastTransaction->balance / 100 : 0;
                    @endphp
                    <p>Rp {{ number_format(round($lastBalance), 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="table-wrapper">
                @if($transactions->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal / Date</th>
                                <th>Kategori / Category</th>
                                <th>Deskripsi / Description</th>
                                <th>Metode Pembayaran / Payment Method</th>
                                <th>Dana Masuk / Income</th>
                                <th>Dana Keluar / Expense</th>
                                <th>Kode Invoice / Invoice Code</th>
                                <th>Laporan / Report</th>
                                <th>Saldo / Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $index => $transaction)
                                @php
                                    $isIncome = $transaction->transactionCategory && $transaction->transactionCategory->transaction_type === 'income';
                                    $amountIn = $isIncome ? $transaction->amount / 100 : 0;
                                    $amountOut = !$isIncome ? $transaction->amount / 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $transactions->firstItem() + $index }}</td>
                                    <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                    <td>{{ $transaction->transactionCategory->name ?? '-' }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>{{ $transaction->paymentMethod->name ?? '-' }}</td>
                                    <td class="amount-positive">
                                        @if($amountIn > 0)
                                            Rp {{ number_format(round($amountIn), 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="amount-negative">
                                        @if($amountOut > 0)
                                            Rp {{ number_format(round($amountOut), 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $transaction->invoice_code }}</td>
                                    <td>{{ $transaction->report->title ?? '-' }}</td>
                                    <td>Rp {{ number_format(round($transaction->balance / 100), 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($transactions->onFirstPage())
                            <span class="disabled">‹ Sebelumnya</span>
                        @else
                            <a href="{{ $transactions->appends(request()->query())->previousPageUrl() }}" class="page-link">‹ Sebelumnya</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @for ($i = 1; $i <= $transactions->lastPage(); $i++)
                            @if ($i == $transactions->currentPage())
                                <span class="active">{{ $i }}</span>
                            @else
                                <a href="{{ $transactions->appends(request()->query())->url($i) }}" class="page-link">{{ $i }}</a>
                            @endif
                        @endfor

                        {{-- Next Page Link --}}
                        @if ($transactions->hasMorePages())
                            <a href="{{ $transactions->appends(request()->query())->nextPageUrl() }}" class="page-link">Berikutnya ›</a>
                        @else
                            <span class="disabled">Berikutnya ›</span>
                        @endif
                    </div>
                @else
                    <div class="no-data">
                        Tidak ada data transaksi / No transaction data available
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
