<!DOCTYPE html>
<html>
<head>
    <title>Transaction Details</title>
    <style>
        /* Add your CSS styles for the PDF here */
        body { font-family: 'DejaVu Sans', sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Transaction Details</h2>
    <table>
        <thead>
            <tr>
                <th>No. Transaksi</th>
                <th>User</th>
                <th>Barang</th>
                <th>Jumlah Barang</th>
                <th>Harga Jual</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $detail)
                <tr>
                    <td>{{ $detail->id_transaction }}</td>
                    <td>{{ $detail->transaction->user->name ?? 'N/A' }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->harga_jual }}</td>
                    <td>{{ $detail->transaction->tanggal_transaksi ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
