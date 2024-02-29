<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nota Apexion</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0 20px;
        }
        h1 {
            font-size: 24px;
            text-align: center;
            margin: 20px 0;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
            font-size: 16px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table, .table th, .table td {
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th, .table td {
            padding: 10px;
            font-size: 14px;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Nota Apexion</h1>
    <div class="info">
        <p><strong> Kasir: </strong>{{ session('userName') }}</p>
        <p><strong>Tanggal Transaksi: </strong>{{ session('tanggalTransaksi') }}</p>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Asli (Rp)</th>
                <th>Diskon (Rp)</th>
                <th>Harga Jual (Rp)</th>
                <th>Total Harga Barang (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ number_format($item['original_price'], 0, ',', '.') }}</td>
                <td>{{ number_format($item['discount_amount'], 0, ',', '.') }}</td>
                <td>{{ number_format($item['harga'], 0, ',', '.') }}</td>
                <td>{{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total">
        @php
                    $grandTotal = 0;
                    foreach(session('addedItems') as $item) {
                        $grandTotal += $item['harga'] * $item['quantity'];
                    }
                @endphp

                <div class="mt-4">
                    <p><strong>Total Harga:</strong> Rp. {{ number_format($grandTotal, 0, ',', '.') }}</p>
                </div>    </div>
</body>
</html>
