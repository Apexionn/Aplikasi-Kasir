<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Diskon;
use App\Models\DetailDiskon;
use Illuminate\Http\Request;

class DetailDiskonController extends Controller
{
    public function index()
    {
        $data = DetailDiskon::with(['diskon', 'genre'])
            ->get()
            ->groupBy('id_diskon')
            ->map(function ($group) {
                return [
                    'id' => $group->first()->id_diskon,
                    'nama_diskon' => $group->first()->diskon->nama_diskon,
                    'genres' => $group->pluck('genre.nama_genre')->join(', '),
                ];
            });

        return view('CRUD.DetailDiskon.detail-diskon', compact('data'));
    }

    public function show($id)
    {
        $diskon = Diskon::with('genres2')->find($id);

        if (!$diskon) {
            return redirect()->route('detaildiskon')->with('error', 'Diskon not found.');
        }

        $data = [
            'id_diskon' => $diskon->id_diskon,
            'nama_diskon' => $diskon->nama_diskon,
            'persentase_diskon' => $diskon->persentase_diskon,
            'tanggal_awal' => $diskon->tanggal_awal,
            'tanggal_akhir' => $diskon->tanggal_akhir,
            'genres' => $diskon->genres->pluck('nama_genre')->all(), 
        ];

        return view('CRUD.Diskon.detail-diskon', compact('data'));
    }



    public function AddDetailDiskonPage(){
        $diskon = Diskon::all();
        $genres = Genre::all();
            // Fetch all diskon IDs that are already used in DetailDiskon
        $usedDiskonIds = DetailDiskon::pluck('id_diskon')->all();
        return view('CRUD.DetailDiskon.detail-diskon-add-form', compact('genres', 'diskon', 'usedDiskonIds'));
    }
    public function EditDetailDiskonPage($id){
        $diskon = Diskon::find($id);
        $genres = Genre::all();
        return view('CRUD.DetailDiskon.detail-diskon-form', compact('genres', 'diskon'));
    }

    public function ProsesTambah(Request $request)
    {
        $request->validate([
            'diskon_id' => 'required',
            'genres' => 'required|array',
        ]);

        $diskon = Diskon::findOrFail($request->diskon_id);

        $diskon->genres()->sync($request->genres);

        return redirect()->route('detaildiskon')->with('success', 'Diskon updated successfully with new genres.');
    }



    public function ProsesUpdate(Request $request, $id)
    {
        $diskon = Diskon::findOrFail($id);

        $diskon->genres()->sync($request->genres);

        return redirect()->route('detaildiskon')->with('success', 'Diskon genres updated successfully.');
    }

    public function DeleteDetailDiskon($id)
    {
        $diskon = Diskon::findOrFail($id);

        $diskon->genres()->detach();

        return redirect()->route('detaildiskon')->with('success', 'Diskon deleted successfully.');
    }
}
