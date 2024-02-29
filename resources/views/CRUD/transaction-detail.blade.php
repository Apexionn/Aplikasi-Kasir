<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/e0d812d232.js" crossorigin="anonymous"></script>
<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('download-pdf') }}" class="btn btn-primary" style="margin-bottom: 20px;"> <i class="fa-solid fa-file-pdf" style="color: #ffffff;"></i> &nbsp; Download PDF</a>
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                {{-- <th>No.</th> --}}
                                <th>No. Transaksi</th>
                                <th>User</th>
                                <th>Barang</th>
                                <th>Jumlah Barang</th>
                                <th>Harga Jual</th>
                                <th>Tanggal Transaksi</th>
                            </tr>
                        </thead>
                        {{-- @php
                        $no = 1;
                        @endphp --}}
                        <tbody>
                            @foreach ($data as $detail)
                                <tr>
                                    {{-- <td class="align-middle">{{ $no++ }}</td> --}}
                                    <td class="align-middle">{{ $detail->id_transaction }}</td>
                                    <td class="align-middle">{{ $detail->transaction->user->name ?? 'N/A' }}</td> {{-- Display user's name --}}
                                    <td class="align-middle">{{ $detail->barang->nama_barang }}</td> {{-- Change made here --}}
                                    <td class="align-middle">{{ $detail->quantity }}</td>
                                    <td class="align-middle">Rp. {{ number_format($detail->harga_jual)  }}</td>
                                    <td class="align-middle">{{ $detail->transaction->tanggal_transaksi ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


</x-app-layout>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
