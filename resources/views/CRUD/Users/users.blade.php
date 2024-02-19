<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Above your table -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('search-users') }}" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Search for users..." name="search" value="{{ request()->query('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <a href="{{ route('add-users-page') }}" class="btn btn-primary" style="margin-bottom: 20px;">Add Users</a>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($data as $user)
                                <tr>
                                    <td class="align-middle">{{ $no++ }}</td>
                                    <td class="align-middle">{{ $user->name }}</td>
                                    <td class="align-middle">{{ $user->email }}</td>
                                    <td class="align-middle">{{ $user->role }}</td>
                                    <td class="align-middle">
                                        <div style="display: flex; align-items: center; justify-content: center; margin-top: 15px;">
                                            <form action="{{ route('edit-users', ['id' => $user->id]) }}" method="GET">
                                                @csrf
                                                <button type="submit" class="btn btn-primary" style="background-color: #007bff; color: #fff;">Edit</button>
                                            </form>
                                            <form action="{{ route('delete-users', ['id' => $user->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger ml-2" style="background-color: #FF0000; color: #fff;">Delete</button>
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
