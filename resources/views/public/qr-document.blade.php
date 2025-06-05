<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Dokumen</title>
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
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            background-color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }
        
        .logo-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            width: 140px;
            height: 50px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
        }
        
        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .logo-text {
            font-size: 12px;
            color: #666;
        }
        
        .logo-text .main {
            color: #ff6b35;
            font-weight: bold;
        }
        
        .logo-right {
            width: 80px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            overflow: hidden;
        }
        
        .logo-right img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .title-section {
            background-color: #c41e3a;
            color: white;
            padding: 30px 20px;
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }
        
        .title-main {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .title-sub {
            font-size: 16px;
            font-style: italic;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
            color: #333;
        }
        
        .info-value {
            font-size: 14px;
            color: #666;
        }
        
        .info-value.large {
            font-size: 16px;
            color: #333;
        }
        
        .surat-section {
            margin-bottom: 30px;
        }
        
        .surat-label {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .surat-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        
        .produced-section {
            margin-bottom: 30px;
        }
        
        .produced-label {
            font-style: italic;
            font-size: 16px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .produced-info {
            font-size: 14px;
            color: #333;
            line-height: 1.4;
        }
        
        .table-header {
            background-color: #d4d4d4;
            display: grid;
            grid-template-columns: 2fr 1fr;
            padding: 15px 20px;
            margin: 0 -20px 0 -20px;
        }
        
        .table-header-item {
            font-size: 18px;
            color: #666;
        }
        
        .table-header-main {
            font-weight: bold;
        }
        
        .table-header-sub {
            font-style: italic;
        }
        
        .signature-item {
            display: grid;
            grid-template-columns: 2fr 1fr;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        
        .signature-item:last-child {
            border-bottom: none;
        }
        
        .signature-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .signature-icon {
            width: 30px;
            height: 30px;
            background-color: #333;
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .signature-details h4 {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 2px;
        }
        
        .signature-details p {
            font-size: 12px;
            color: #666;
        }
        
        .signature-status {
            text-align: center;
        }
        
        .status-main {
            font-size: 14px;
            color: #333;
            margin-bottom: 2px;
        }
        
        .status-sub {
            font-size: 12px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-left">
                <div class="logo-icon">
                    <img src="{{ asset('images/LOGO-PPHTGD-01.png') }}" alt="PPHTGD Logo">
                </div>
            </div>
            <div class="logo-right">
                <img src="{{ asset('images/Logo_LUP.png') }}" alt="LevelUP Logo">
            </div>
        </div>

        <!-- Title Section -->
        <div class="title-section">
            <div class="title-main">Informasi Dokumen</div>
            <div class="title-sub">Document Information</div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Document Info Grid -->
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nomor Surat :</div>
                    <div class="info-value large">{{ $document_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal buat :</div>
                    <div class="info-value large">{{ $created_date }}</div>
                    <div class="info-value large">{{ $created_time }}</div>
                </div>
            </div>

            <!-- Surat Section -->
            <div class="surat-section">
                <div class="surat-label">Surat :</div>
                <div class="surat-title">{{ $document_title }}</div>
            </div>

            <!-- Produced Section -->
            <div class="produced-section">
                <div class="produced-label">Produced date:</div>
                <div class="produced-info">
                    Dibuat oleh,<br>
                    {{ $producer_organization }}<br>
                </div>
                <div style="margin-top: 10px;">
                    <div class="produced-label">Produced by,</div>
                    <div class="produced-info">{{ $producer_full }}</div>
                </div>
                <div style="margin-top: 15px; text-align: right;">
                    <div class="info-value large">{{ $produced_date }}</div>
                    <div class="info-value large">{{ $produced_time }}</div>
                </div>
            </div>

            <!-- Table Header -->
            <div class="table-header">
                <div class="table-header-item">
                    <div class="table-header-main">Rincian</div>
                    <div class="table-header-sub">Details</div>
                </div>
                <div class="table-header-item">
                    <div class="table-header-main">Status</div>
                    <div class="table-header-sub">Status</div>
                </div>
            </div>

            <!-- Signatures -->
            @foreach($signatures as $signature)
            <div class="signature-item">
                <div class="signature-info">
                    <div class="signature-icon"></div>
                    <div class="signature-details">
                        <h4>{{ $signature['name'] }}</h4>
                        <p>{{ $signature['position'] }}</p>
                    </div>
                </div>
                <div class="signature-status">
                    <div class="status-main">{{ $signature['status'] }}</div>
                    <div class="status-sub">{{ $signature['status_en'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html> 