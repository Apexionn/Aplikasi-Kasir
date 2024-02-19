<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Diskon') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('proses-edit-detail-diskon', ['id' => $diskon->id_diskon]) }}" method="POST" class="form-container">
                        @csrf

                        <div class="form-group">
                            <label for="name">Nama Diskon:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $diskon->nama_diskon }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Genres:</label><br>
                            @foreach($genres as $genre)
                            <div class="form-check form-check-inline mb-3">
                                <input class="form-check-input" type="checkbox" name="genres[]" id="genre_{{ $genre->id_genre }}" value="{{ $genre->id_genre }}"
                                @if($diskon->genres->pluck('id_genre')->contains($genre->id_genre)) checked @endif>
                                <label class="form-check-label" for="genre_{{ $genre->id_genre }}">{{ $genre->nama_genre }}</label>
                            </div>
                        @endforeach

                        </div>

                        <button type="submit" class="btn btn-primary" style="background-color: #007bff; color: #fff;">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
