<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Voucher Reward</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .voucher-container {
            border: 5px double #ffd700;
            padding: 30px;
            width: 80%;
            margin: 50px auto;
            text-align: center;
            background-color: #fcfcf4;
        }
        .header {
            background-color: #004d40; /* Warna hijau tua */
            color: white;
            padding: 10px;
            font-size: 24px;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }
        .brand { font-weight: bold; font-size: 28px; color: #004d40; }
        .congrats { font-size: 20px; margin: 20px 0; }
        .recipient-name {
            font-size: 26px;
            font-weight: bold;
            color: #d4af37; /* Warna emas */
            border-bottom: 2px solid #004d40;
            display: inline-block;
            padding-bottom: 5px;
            margin-bottom: 30px;
        }
        .reward-details { margin-bottom: 30px; }
        .reward-details h3 {
            margin: 0;
            font-weight: normal;
            color: #555;
        }
        .reward-details p {
            margin: 5px 0;
            font-size: 22px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="voucher-container">
        <div class="header">VOUCHER PENGHARGAAN</div>
        <p class="brand">PEMUSTAKA AWARD</p>
        
        <p class="congrats">Dengan bangga diberikan kepada:</p>
        <div class="recipient-name">{{ $nama }}</div>

        <div class="reward-details">
            <h3>Atas pencapaiannya meraih hadiah:</h3>
            <p>Level {{ $level }} - {{ $hadiah }}</p>
        </div>
        
        <div class="footer">
            Hadiah ini berhasil diklaim pada tanggal: {{ $tanggal }}
            <br>
            *Voucher ini adalah bukti klaim yang sah.*
        </div>
    </div>
</body>
</html>