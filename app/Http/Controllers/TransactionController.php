<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function index(Request $request) {
        $currentDate = date('Y-m-d');

        // Fetch all barang data with eager loading for genres
        $barangData = Barang::with(['genres' => function ($query) use ($currentDate) {
            $query->with(['diskons' => function ($subQuery) use ($currentDate) {
                $subQuery->where('tanggal_mulai', '<=', $currentDate)
                        ->where('tanggal_akhir', '>=', $currentDate);
            }]);
        }])->get();

        // Iterate over barangData to calculate discounted prices if applicable
        foreach ($barangData as $barang) {
            // Initialize harga_diskon with the original harga
            $barang->harga_diskon = $barang->harga;

            $applicableDiscount = $barang->findApplicableDiscount();

            // Check if there is an applicable discount
            if ($applicableDiscount) {
                // Remove the '%' symbol and convert to a numeric value if necessary
                $persentase_diskon = rtrim($applicableDiscount->persentase_diskon, '%');
                $persentase_diskon = (float)$persentase_diskon / 100; // Convert to a fraction

                $barang->harga_diskon -= $barang->harga * $persentase_diskon;
            }
        }

        return view('CRUD.transaction', compact('barangData'));
    }


    public function store(Request $request)
    {
        $items = json_decode($request->items, true);

        $transaction = Transaction::create([
            'id_users' => Auth::id(),
            'tanggal_transaksi' => now(),
        ]);

        foreach ($items as $item) {
            DetailTransaction::create([
                'id_transaction' => $transaction->id_transaction,
                'id_barang' => $item['id'],
                'quantity' => $item['quantity'],
                'harga_jual' => $item['harga'],
            ]);

            $barang = Barang::find($item['id']);
            if ($barang) {
                $barang->decrement('stok', $item['quantity']);
            }
        }

        return redirect()->route('transaction')->with('success', 'Transaction successful!');
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
}
