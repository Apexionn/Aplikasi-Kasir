<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('search-barang') }}" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Search for Barang..." name="search" value="{{ request()->query('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <a href="{{ route('add-barang-page') }}" class="btn btn-primary" style="margin-bottom: 20px;">Add Barang</a>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Genre</th>
                                <th>Foto</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($data as $index => $barang)
                            <tr>
                                    {{-- <td class="align-middle">{{ $no++ }}</td> --}}
                                    <td class="align-middle">{{ $data->firstItem() + $index }}</td>
                                    <td class="align-middle">{{ $barang->kode_barang }}</td>
                                    <td class="align-middle">{{ $barang->nama_barang }}</td>
                                    <td class="align-middle">{{ $barang->stok }}</td>
                                    <td class="align-middle">
                                        @foreach ($barang->genres as $genre)
                                            {{ $genre->nama_genre }}@if(!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td class="align-middle">
                                        <img src="{{ asset($barang->image) }}" alt="Barang Image" style="width: 100px; display: block; margin: auto;">
                                    </td>
                                    </td>
                                    <td class="align-middle">{{ $barang->harga }}</td>
                                    <td class="align-middle">
                                        <div style="display: flex; align-items: center; justify-content: center; margin-top: 15px;">
                                            <form action="{{ route('edit-barang', ['id' => $barang->id_barang]) }}" method="GET">
                                                @csrf
                                                <button type="submit" class="btn btn-primary" style="background-color: #007bff; color: #fff;">Edit</button>
                                            </form>
                                            <form action="{{ route('delete-barang', ['id' => $barang->id_barang]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger ml-2" style="background-color: #FF0000; color: #fff;">Delete</button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $data->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
            document.addEventListener('DOMContentLoaded', function () {
                @if(session('success'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                let message = "{{ session('success') }}";
                    if ("{{ session('status') }}" === "added") {
                        message = "Data Added Successfully";
                    } else if ("{{ session('status') }}" === "updated") {
                        message = "Data Updated Successfully";
                    }

                    Toast.fire({
                        icon: "success",
                        title: message
                    });
                @endif
            });
</script>

</x-app-layout>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
