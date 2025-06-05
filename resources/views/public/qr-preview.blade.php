<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        
        .container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
        }
        
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }
        
        .qr-container {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        
        .download-btn {
            background-color: #c41e3a;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        
        .download-btn:hover {
            background-color: #a01829;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">QR Code Preview</div>
        <div class="subtitle">Scan this QR code to view the document</div>
        
        <div class="qr-container">
            <img src="{{ route('qr.code.generate', ['qrGeneratorId' => $qrGeneratorId]) }}" alt="QR Code">
        </div>
        
        <a href="{{ route('qr.code.download', ['qrGeneratorId' => $qrGeneratorId]) }}" class="download-btn">
            Download QR Code
        </a>
    </div>
</body>
</html> 