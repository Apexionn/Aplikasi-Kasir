<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('proses-edit-barang', ['id' => $barang->id_barang]) }}" method="POST" class="form-container" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="kode">Kode Barang:</label>
                            <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode Barang" value="{{ $barang->kode_barang }}" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Nama Barang:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Barang" value="{{ $barang->nama_barang }}" required>
                        </div>

                        <div class="form-group">
                            <label for="stok">Stok:</label>
                            <input type="number" class="form-control" id="stok" name="stok" placeholder="Stok" value="{{ $barang->stok }}" min="0" max="100" required>
                        </div>

                        {{-- <div class="form-group">
                            <label for="foto_barang">Foto Barang:</label>
                            <img src="data:image/png;base64,{{ base64_encode($barang->image) }}" class="mb-3" alt="barang" style="width: 100px;">
                            <input type="file" class="form-control" name="image">
                        </div> --}}

                        <div class="form-group">
                            <label for="foto_barang">Foto Barang:</label>
                            <img src="{{ asset($barang->image) }}" alt="Barang Image" class="mb-3" style="width: 300px;">
                            <input type="file" class="form-control" name="image_varchar">
                        </div>

                        <div class="form-group">
                            <label>Genres:</label><br>
                            @foreach($genres as $genre)
                                <div class="form-check form-check-inline mb-3">
                                    <input class="form-check-input" type="checkbox" name="genres[]" id="genre_{{ $genre->id_genre }}" value="{{ $genre->id_genre }}"
                                    @if($barang->genres->contains($genre->id_genre)) checked @endif>
                                    <label class="form-check-label" for="genre_{{ $genre->id_genre }}">{{ $genre->nama_genre }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga:</label>
                            <input type="number" class="form-control" name="harga" placeholder="Harga" value="{{ $barang->harga }}" min="0" required>
                        </div>

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                        <button type="submit" class="btn btn-primary" style="background-color: #007bff; color: #fff;">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        var maxGenres = 5;

        $('input[name="genres[]"]').on('change', function(evt) {
        var checkedGenresCount = $('input[name="genres[]"]:checked').length;
        console.log("Checked genres: " + checkedGenresCount);

        if(checkedGenresCount > maxGenres) {
            this.checked = false;
            console.log("Limit exceeded");

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'You can only select up to ' + maxGenres + ' genres!',
            });
        }
        });
    });
</script>




<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
