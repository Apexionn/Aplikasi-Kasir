<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\DetailTransaction;

class DetailTransactionController extends Controller
{
    public function showTransactions()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $data = DetailTransaction::with(['barang', 'transaction.user'])
                ->whereHas('transaction', function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth]);
                })
                ->orderBy('id_transaction', 'desc')
                ->get();

        foreach ($data as $detail) {
            // $detail->original_price = $detail->calculateDiscountPercentage();
            $detail->persentase_diskon = $detail->calculateDiscountPercentage();
        }

        return view('CRUD.transaction-detail', compact('data'));
    }



    public function downloadPdf()
    {

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $data = DetailTransaction::with(['transaction.user', 'barang'])
                ->whereHas('transaction', function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth]);
                })
                ->get();

        $pdf = PDF::loadView('CRUD.transaction-pdf', compact('data'));

        return $pdf->download('laporan-penjualan.pdf');
    }

}
