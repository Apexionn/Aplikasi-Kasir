<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/e0d812d232.js" crossorigin="anonymous"></script>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="mb-4 text-center text-3xl font-weight-bold">Nota <span style="color: #ff0000;">Apexion</span></h1>
                    <div class="mb-4">
                        <p><strong>Kasir : </strong> {{ session('userName') }}</p>
                        <p><strong>Tanggal Transaksi :</strong> {{ session('tanggalTransaksi') }}</p>
                    </div>
                    <!-- Table Container -->
                    <div style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
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
                                @foreach(session('addedItems') as $item)
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
                    </div>

                    @php
                        $grandTotal = 0;
                        foreach(session('addedItems') as $item) {
                            $grandTotal += $item['harga'] * $item['quantity'];
                        }
                    @endphp

                    <div class="mt-4">
                        <p><strong>Total Harga:</strong> Rp. {{ number_format($grandTotal, 0, ',', '.') }}</p>
                    </div>
                    <div class="mt-4 text-center">
                        <form action="{{ route('transaction') }}" method="get" style="display: inline;">
                            <button type="submit" class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-500 dark:hover:bg-gray-600 focus:outline-none dark:focus:ring-gray-700"><i class="fa-solid fa-arrow-left" style="color: #ffffff;"></i> &nbsp; &nbsp; Back To Transaction</button>
                        </form>

                        <button onclick="window.location='{{ route('downloadPdfNota') }}'" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800""> <i class="fa-solid fa-print" style="color: #ffffff;"></i> &nbsp; Print </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
