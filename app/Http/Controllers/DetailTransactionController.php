<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use PDF;

class DetailTransactionController extends Controller
{
    public function showTransactions()
    {
        $data = DetailTransaction::with(['barang', 'transaction.user'])
                ->orderBy('id_transaction', 'desc')
                ->get();

        return view('CRUD.transaction-detail', compact('data'));
    }


    public function downloadPdf()
    {

        $data = DetailTransaction::with(['transaction.user', 'barang'])->get();

        $pdf = PDF::loadView('CRUD.transaction-pdf', compact('data'));

        return $pdf->download('laporan-penjualan.pdf');
    }

}
