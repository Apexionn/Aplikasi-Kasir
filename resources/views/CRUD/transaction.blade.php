<x-app-layout>
    <script src="https://kit.fontawesome.com/e0d812d232.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .relative-wrapper {
            position: relative;
            display: inline-block;
        }

        .sold-out-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(80, 78, 78, 0.5);
            color: white;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            z-index: 1;
            text-shadow:
                -1px -1px 0 #000,
                1px -1px 0 #000,
                -1px  1px 0 #000,
                1px  1px 0 #000;
        }
        .image-wrapper {
            display: inline-block;
            position: relative;
            line-height: 0;
        }

        .search-bar-container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .search-bar-form {
            display: flex;
            width: 100%;
            max-width: 600px;
        }

        .form-control.search-input {
            width: 100%;
            padding: 10px 20px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-right: none;
            border-radius: 5px 0 0 5px;
            outline: none;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .search-button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .form-control.search-input, .search-button {
                font-size: 14px;
                padding: 8px 16px;
            }
        }

        .button-minus:not(:disabled) {
            background-color: #f56565;
        }

        .button-minus:not(:disabled):hover {
            background-color: #c53030;
        }

        .button-plus:not(:disabled) {
            background-color: #4299e1;
        }

        .button-plus:not(:disabled):hover {
            background-color: #2b6cb0;
        }


        </style>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 flex flex-wrap">
            <!-- Items grid -->
            <div class="w-full lg:w-1/2">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" style="height: 580px; overflow-y: auto; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);">
                    <div class="p-8 text-gray-900">
                        <div class="search-bar-container">
                            <form action="{{ route('search-barang-transaction') }}" method="GET" class="search-bar-form">
                                <input type="text" class="form-control search-input" placeholder="Search for Barang..." name="search" value="{{ request()->query('search') }}">
                                <button class="search-button" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-2 gap-4">
                            @foreach ($barangData as $barang)
                            <div class="border rounded-lg p-4 text-center flex flex-col relative-wrapper" style="display: flex; flex-direction: column; height: 100%;">
                                @if ($barang->stok == 0)
                                <div class="image-wrapper relative" style="line-height: 0;"> 
                                    <div class="sold-out-overlay">
                                        Sold Out
                                    </div>
                                    <img src="{{ asset($barang->image) }}" alt="Barang Image" class="w-52 h-64 object-cover mx-auto">
                                </div>
                                @else
                                <img src="{{ asset($barang->image) }}" alt="Barang Image" class="w-52 h-64 object-cover mx-auto">
                                @endif
                                <div class="flex-grow flex flex-col justify-between">
                                    <h3 class="text-base mt-4">{{ $barang->nama_barang }}</h3>
                                    <div class="my-2">
                                        @if (isset($barang->harga_diskon) && $barang->harga_diskon != $barang->harga)
                                        <p class="text-sm text-gray-600 mt-2 line-through">Rp {{ number_format($barang->harga) }}</p>
                                        <p class="text-sm text-red-600 mt-2">Rp {{ number_format($barang->harga_diskon) }}</p>
                                        @else
                                        <p class="text-sm text-gray-600">Rp {{ number_format($barang->harga) }}</p>
                                        @endif
                                        <h3 class="text-base mt-2">Tersedia: {{ $barang->stok }}</h3>
                                    </div>
                                </div>
                                <div>
                                    <button onclick="tambahkanAtauKurangiBarang('{{ $barang->id_barang }}', '{{ $barang->nama_barang }}', {{ $barang->harga_diskon ?? $barang->harga }}, {{ $barang->stok }}, false)" class="px-4 py-2 text-white rounded ml-2 button-minus {{ $barang->stok == 0 ? 'bg-gray-500 disabled:opacity-50 cursor-not-allowed' : 'bg-blue-500' }}" {{ $barang->stok == 0 ? 'disabled' : '' }}>-</button>

                                    <button onclick="tambahkanAtauKurangiBarang('{{ $barang->id_barang }}', '{{ $barang->nama_barang }}', {{ $barang->harga_diskon ?? $barang->harga }}, {{ $barang->stok }}, true)" class="px-4 py-2 text-white rounded button-plus {{ $barang->stok == 0 ? 'bg-gray-500 disabled:opacity-50 cursor-not-allowed' : 'bg-blue-500' }}"" {{ $barang->stok == 0 ? 'disabled' : '' }}>+</button>

                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selected items list -->
            <div class="w-full lg:w-1/2 pl-4">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" style="height: 580px; overflow-y: auto; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);">
                    <div class="p-8 text-gray-900">
                        <div class="flex justify-between items-center">
                            <h2 class="font-semibold text-lg">Barang Terpilih</h2>
                            <button onclick="clearAllItems()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 focus:outline-none">Clear All</button>
                        </div>
                            <div style="overflow-y: auto; height: 300px;">
                            <table id="addedItemsTable" class="mt-4 min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Barang
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kuantitas
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Harga
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody" class="bg-white divide-gray-200">
                                    <!-- Data yang dipilih akan muncul disini -->
                                </tbody>
                            </table>
                        </div>
                        <hr class="mt-5 mb-5">
                        <p>Total Harga: <span id="totalHarga" style="color: red;">Rp 0</span></p>
                        <div class="flex flex-col space-y-4 lg:space-y-0 lg:flex-row lg:items-center lg:justify-between mt-10">
                            <div>
                                <label for="uang_diberikan">Uang Diberikan</label>
                                <input type="number" name="uang_diberikan" id="uang_diberikan" class="mt-1">
                            </div>
                            <div class="flex items-center space-x-4">
                                <div>
                                    <label for="uang_kembalian">Uang Kembalian</label>
                                    <input type="number" name="uang_kembalian" id="uang_kembalian" disabled class="mt-1">
                                </div>
                                <form id="submitForm" action="{{ route('proses-transaction') }}" method="POST" class="shrink-0">
                                    @csrf
                                    <input type="hidden" name="items" id="itemsInput">
                                    <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none" type="submit">Beli</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let addedItems = [];

        // Fungsi ini digunakan untuk menambahkan atau mengurangi barang ke dalam keranjang
        function tambahkanAtauKurangiBarang(id, nama, harga, stok, isAdding) {
            // Mencari index barang dalam array berdasarkan nama
            const existingItemIndex = addedItems.findIndex(item => item.nama === nama);
            // Jika barang sudah ada dalam array
            if (existingItemIndex !== -1) {
                // Jika aksi adalah menambahkan
                if (isAdding) {
                    // Cek apakah kuantitas kurang dari stok
                    if (addedItems[existingItemIndex].quantity < stok) {
                        addedItems[existingItemIndex].quantity += 1;
                    } else {
                        // Jika stok sudah mencapai batas, tampilkan pesan error
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `${nama} has reached its stock limit.`,
                        });
                    }
                } else {
                    // Jika aksi adalah mengurangi
                    if (addedItems[existingItemIndex].quantity > 1) {
                        addedItems[existingItemIndex].quantity -= 1;
                    } else {
                        // Jika kuantitas = 1, hapus barang dari array
                        addedItems.splice(existingItemIndex, 1);
                    }
                }
            } else {
                // Jika barang belum ada dalam array dan aksi adalah menambahkan
                if (isAdding) {
                    addedItems.push({ id, nama, harga, quantity: 1, totalHarga: harga });
                } else {
                    // Jika barang belum ada dalam keranjang dan aksi adalah mengurangi, tampilkan pesan error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `${nama} is not in the cart.`,
                    });
                }
            }
            // Update total harga setelah menambahkan atau mengurangi
            if (existingItemIndex !== -1 || isAdding) {
                addedItems.forEach(item => {
                    if (item.nama === nama) {
                        if (item.discount_percentage > 0) {
                            const discountAmount = item.harga * (item.discount_percentage / 100);
                            const discountedHarga = item.harga - discountAmount;
                            item.totalHarga = discountedHarga * item.quantity;
                        } else {
                            item.totalHarga = item.harga * item.quantity;
                        }
                    }
                });
            }
            // Memperbarui daftar barang yang telah ditambahkan
            updateAddedItemsList();
        }

        // Fungsi ini digunakan untuk memperbarui tampilan daftar barang yang telah ditambahkan
        function updateAddedItemsList() {
            const tableBody = document.getElementById('itemsTableBody');
            tableBody.innerHTML = '';
            let totalHarga = 0;

            addedItems.forEach((item) => {
                const row = document.createElement('tr');

                const namaCell = document.createElement('td');
                namaCell.textContent = item.nama;
                namaCell.className = 'px-6 py-4 whitespace-nowrap';

                const quantityCell = document.createElement('td');
                quantityCell.textContent = item.quantity;
                quantityCell.className = 'px-6 py-4 whitespace-nowrap';

                const totalHargaCell = document.createElement('td');
                totalHargaCell.textContent = `Rp ${item.totalHarga.toLocaleString()}`;
                totalHargaCell.className = 'px-6 py-4 whitespace-nowrap';

                row.appendChild(namaCell);
                row.appendChild(quantityCell);
                row.appendChild(totalHargaCell);

                tableBody.appendChild(row);
                totalHarga += item.totalHarga;
            });

            document.getElementById('totalHarga').textContent = `Rp ${totalHarga.toLocaleString()}`;

            // Enable or disable the Uang Diberikan input based on the addedItems array
            const uangDiberikanInput = document.getElementById('uang_diberikan');
            if (addedItems.length > 0) {
                uangDiberikanInput.disabled = false;
            } else {
                uangDiberikanInput.disabled = true;
                document.getElementById('uang_kembalian').value = '';
            }
        }

        document.getElementById('uang_diberikan').addEventListener('input', function() {
            const totalHarga = addedItems.reduce((acc, item) => acc + item.totalHarga, 0);
            const uangDiberikan = parseInt(this.value, 10);
            const uangKembalian = uangDiberikan - totalHarga;

            if (!isNaN(uangKembalian)) {
                document.getElementById('uang_kembalian').value = uangKembalian;
            } else {
                document.getElementById('uang_kembalian').value = '';
            }
        });

        updateAddedItemsList();

        document.getElementById('uang_diberikan').addEventListener('input', function() {
            const totalHarga = addedItems.reduce((acc, item) => acc + item.totalHarga, 0);
            const uangDiberikan = parseInt(this.value, 10);
            const uangKembalian = uangDiberikan - totalHarga;

            if (!isNaN(uangKembalian)) {
                document.getElementById('uang_kembalian').value = uangKembalian;
            } else {
                document.getElementById('uang_kembalian').value = '';
            }
        });

        // Fungsi ini digunakan untuk mengosongkan semua item yang telah ditambahkan
        function clearAllItems() {
            if (addedItems.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: "There's no data to be cleared.",
                });
            } else {
                addedItems = [];
                updateAddedItemsList();
                document.getElementById('totalHarga').textContent = 'Rp 0';
            }
        }

        // Fungsi ini dipanggil saat form disubmit untuk memastikan ada barang yang ditambahkan
        document.getElementById('submitForm').onsubmit = function(event) {
            if (addedItems.length === 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Your cart is empty. Please add items before proceeding.',
                });
            } else {
                const uangDiberikan = document.getElementById('uang_diberikan').value;
                const uangKembalian = document.getElementById('uang_kembalian').value;
                if (!uangDiberikan || isNaN(uangDiberikan) || parseInt(uangDiberikan, 10) <= 0) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Attention!',
                        text: 'Please insert the Uang Diberikan input!',
                    });
                } else if (uangKembalian < 0) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Sorry, Your money isn\'t enough.',
                        text: 'Please input it again!',
                    });
                } else {
                    document.getElementById('itemsInput').value = JSON.stringify(addedItems);
                }
            }
        };

        // Menampilkan pesan sukses jika ada setelah DOM dimuat
        document.addEventListener('DOMContentLoaded', function () {
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Completed!',
                        text: '{{ session('success') }}',
                    });
                @endif
            });
    </script>
</x-app-layout>
