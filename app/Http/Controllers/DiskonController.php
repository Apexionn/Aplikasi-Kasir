<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Genre;
use App\Models\Diskon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DiskonController extends Controller
{

    public function index()
    {
        $today = new DateTime();
        $today->setTime(0, 0, 0);

        $diskons = Diskon::all();

        foreach ($diskons as $diskon) {
            $startDate = new DateTime($diskon->tanggal_mulai);
            $startDate->setTime(0, 0, 0);
            $endDate = new DateTime($diskon->tanggal_akhir);
            $endDate->setTime(0, 0, 0);

            if ($endDate < $today) {
                $diskon->status = 'Tidak Berlaku';
            } elseif ($startDate > $today) {
                $diskon->status = 'Akan Berlaku';
            } else {
                $diskon->status = 'Masih Berlaku';
            }

            $diskon->save();
        }

        if (Auth::check() && Auth::user()->role == 'Admin') {
            $data = Diskon::paginate(10);
        return view('CRUD.Diskon.diskon', compact('data'));
        }
        return redirect('/transaction')->with('error', 'You are not authorized to view this page.');
    }

    public function AddDiskonPage(){
        $genres = Genre::all();
        return view('CRUD.Diskon.diskon-add-form', compact('genres'));
    }

    public function ProsesTambah(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'persentase' => 'required|numeric|max:90',
            'awal' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $overlapping = Diskon::where(function ($query) use ($value, $request) {
                        $query->where('tanggal_mulai', '<=', $value)
                              ->where('tanggal_akhir', '>=', $value);
                    })->orWhere(function ($query) use ($request) {
                        $query->where('tanggal_mulai', '<=', $request->input('akhir'))
                              ->where('tanggal_akhir', '>=', $request->input('akhir'));
                    })->exists();

                    if ($overlapping) {
                        $fail('The ' . $attribute . ' overlaps with another discount period.');
                    }
                },
            ],
            'akhir' => [
                'required',
                'date',
                'after:awal',
            ],
            'genres' => 'required|array',
        ]);

        $diskon = Diskon::create([
            'nama_diskon' => $request->input('name'),
            'persentase_diskon' => $request->input('persentase'),
            'tanggal_mulai' => $request->input('awal'),
            'tanggal_akhir' => $request->input('akhir'),
        ]);

        $diskon->genres()->attach($request->input('genres'));


        return redirect()->route('diskon')->with('success', 'Genre data added successfully!')->with('status', 'added');
    }

    public function EditDiskonPage($id){
        $genres = Genre::all();
        $diskon = Diskon::find($id);
        if (Auth::check() && Auth::user()->role == 'Admin') {
            return view('CRUD.Diskon.diskon-form', compact('diskon', 'genres'));
        }
        return redirect('/transaction')->with('error', 'You are not authorized to view this page.');
    }

    public function ProsesUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'persentase' => 'required|numeric|max:90',
            'awal' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($id, $request) {
                    $overlapping = Diskon::where('id_diskon', '!=', $id)
                        ->where(function ($query) use ($value, $request) {
                            $query->where(function ($q) use ($value, $request) {
                                $q->where('tanggal_mulai', '<=', $value)
                                  ->where('tanggal_akhir', '>=', $value);
                            })->orWhere(function ($q) use ($value, $request) {
                                $q->where('tanggal_mulai', '<=', $request->input('akhir'))
                                  ->where('tanggal_akhir', '>=', $request->input('akhir'));
                            });
                        })->exists();

                    if ($overlapping) {
                        $fail('The ' . $attribute . ' overlaps with another discount period.');
                    }
                },
            ],
            'akhir' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($id, $request) {
                    $overlapping = Diskon::where('id_diskon', '!=', $id)
                        ->where(function ($query) use ($value, $request) {
                            $query->where(function ($q) use ($value, $request) {
                                $q->where('tanggal_mulai', '<=', $value)
                                  ->where('tanggal_akhir', '>=', $value);
                            })->orWhere(function ($q) use ($value, $request) {
                                $q->where('tanggal_mulai', '<=', $request->input('awal'))
                                  ->where('tanggal_akhir', '>=', $request->input('awal'));
                            });
                        })->exists();

                    if ($overlapping) {
                        $fail('The ' . $attribute . ' overlaps with another discount period.');
                    }
                },
                'after:awal',
            ],
            'genres' => 'required|array',
        ]);

        $genre = Diskon::find($id);

        if (!$genre) {
            return redirect()->route('diskon')->with('error', 'Discount data not found!');
        }

        $genre->nama_diskon = $request->input('name');
        $genre->persentase_diskon = $request->input('persentase');
        $genre->tanggal_mulai = $request->input('awal');
        $genre->tanggal_akhir = $request->input('akhir');
        $genre->save();

        $genre->genres()->sync($request->input('genres'));


        return redirect()->route('diskon')->with('success', 'Genre data updated successfully!')->with('status', 'updated');
    }

    public function DeleteDiskon($id){
        $barang = Diskon::find($id);

        if ($barang->genres->isNotEmpty()) {
            return back()->withErrors('Cannot delete this Diskon because it has associated with detail diskon.');
        }

        $barang->delete();
        return back()->with('success','Data berhasil dihapus!');
    }
}
