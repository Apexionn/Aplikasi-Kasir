<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('add-detail-diskon-page') }}" class="btn btn-primary" style="margin-bottom: 20px;">Add Diskon</a>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Diskon</th>
                                <th>Genre</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($data as $index => $diskon)
                                <tr>
                                    <td class="align-middle">{{ $no++ }}</td>
                                    <td class="align-middle">{{ $diskon['nama_diskon'] }}</td>
                                    <td class="align-middle">{{ $diskon['genres'] }}</td>
                                    <td class="align-middle">
                                        <div style="display: flex; align-items: center; justify-content: center; margin-top: 15px;">
                                            <form action="{{ route('edit-detail-diskon', ['id' => $diskon['id']]) }}" method="GET">
                                                @csrf
                                                <button type="submit" class="btn btn-primary" style="background-color: #007bff; color: #fff;">Edit</button>
                                            </form>
                                            <form action="{{ route('delete-detail-diskon', ['id' => $diskon['id']]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger ml-2" style="background-color: #FF0000; color: #fff;" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
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


