<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PDF;

class TransactionController extends Controller
{

public function index(Request $request) {
    $currentDate = date('Y-m-d');
    // Mengambil semua data barang dengan 'eager loading' untuk genre. Eager loading mengurangi jumlah query yang dibutuhkan untuk mengambil relasi
    $barangData = Barang::with(['genres' => function ($query) use ($currentDate) {
        // Untuk setiap genre, kita juga melakukan eager loading untuk diskon yang berlaku pada tanggal saat ini
        $query->with(['diskons' => function ($subQuery) use ($currentDate) {
            $subQuery->where('tanggal_mulai', '<=', $currentDate) // Diskon dimulai sebelum atau sama dengan tanggal saat ini
                    ->where('tanggal_akhir', '>=', $currentDate); // dan berakhir setelah atau sama dengan tanggal saat ini
        }]);
    }])->get();

    // Iterasi melalui setiap data barang untuk menghitung harga diskon jika ada
    foreach ($barangData as $barang) {
        // Inisialisasi harga_diskon dengan harga asli barang
        $barang->harga_diskon = $barang->harga;

        // Mencari diskon yang berlaku
        $applicableDiscount = $barang->findApplicableDiscount();

        // Memeriksa apakah ada diskon yang berlaku
        if ($applicableDiscount) {
            // Menghapus simbol '%' dari persentase diskon dan konversi ke nilai numerik
            $persentase_diskon = rtrim($applicableDiscount->persentase_diskon, '%');
            $persentase_diskon = (float)$persentase_diskon / 100; // Mengubah persentase menjadi pecahan

            // Menghitung harga setelah diskon dan memperbarui harga_diskon pada barang
            $barang->harga_diskon -= $barang->harga * $persentase_diskon;
        }
    }

    // Mengembalikan view 'CRUD.transaction' dengan data barang yang sudah diolah
    return view('CRUD.transaction', compact('barangData'));
}


public function store(Request $request)
{
    $items = json_decode($request->items, true);

    $transaction = Transaction::create([
        'id_users' => Auth::id(),
        'tanggal_transaksi' => now(),
    ]);

    $addedItemsWithNames = [];

    foreach ($items as $item) {
        $barang = Barang::with(['genres.diskons'])->find($item['id']);

        if ($barang) {
            $originalPrice = $barang->harga;
            $applicableDiscount = $barang->findApplicableDiscount();
            $discountPercentage = $applicableDiscount ? $applicableDiscount->persentase_diskon : 0;

            $discountAmount = ($originalPrice * $discountPercentage) / 100;
            $hargaJual = $originalPrice - $discountAmount;

            DetailTransaction::create([
                'id_transaction' => $transaction->id_transaction,
                'id_barang' => $item['id'],
                'quantity' => $item['quantity'],
                'harga_jual' => $hargaJual,
                'persentase_diskon' => $discountPercentage, 
            ]);

            $barang->decrement('stok', $item['quantity']);

            $addedItemsWithNames[] = [
                'name' => $barang->nama_barang,
                'quantity' => $item['quantity'],
                'harga' => $hargaJual,
                'original_price' => $originalPrice,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage,
            ];
        }
    }

    $totalHarga = array_sum(array_map(function($item) {
        return $item['harga'] * $item['quantity'];
    }, $addedItemsWithNames));

    $userName = Auth::user()->name;
    $tanggalTransaksi = $transaction->tanggal_transaksi->format('d-m-Y');

    Session::put('totalHarga', $totalHarga);
    Session::put('addedItems', $addedItemsWithNames);
    Session::put('userName', $userName);
    Session::put('tanggalTransaksi', $tanggalTransaksi);

    return redirect()->route('nota')->with('success', 'Transaction successful!');
}




    public function search(Request $request)
    {
        $search = $request->query('search');

        if (!empty($search)) {
            $barangData = Barang::where('kode_barang', 'LIKE', "%{$search}%")
                        ->orWhere('nama_barang', 'LIKE', "%{$search}%")
                        ->get();
        } else {
            $barangData = Barang::all();
        }

        return view('CRUD.transaction', compact('barangData'));
    }

    public function downloadPdf() {
        $items = session('addedItems');
        $totalHarga = session('totalHarga');
        $pdf = PDF::loadView('CRUD.nota-pdf', compact('items', 'totalHarga'));
        return $pdf->download('invoice.pdf');
    }
}
