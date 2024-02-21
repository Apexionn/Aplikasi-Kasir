<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Diskon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
                $diskon->status = 'Tidak Berlaku'; // Adjusted for ENUM value
            } elseif ($startDate > $today) {
                $diskon->status = 'Akan Berlaku'; // Adjusted for ENUM value
            } else {
                $diskon->status = 'Masih Berlaku'; // Adjusted for ENUM value
            }

            $diskon->save();
        }

        $data = Diskon::paginate(10);
        return view('CRUD.Diskon.diskon', compact('data'));
    }

    public function AddDiskonPage(){
        return view('CRUD.Diskon.diskon-add-form');
    }

    public function ProsesTambah(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'persentase' => 'required|numeric',
            'awal' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    // Check if the tanggal_mulai is unique and not overlapping with existing date ranges
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
        ]);
        
        Diskon::create([
            'nama_diskon' => $request->input('name'),
            'persentase_diskon' => $request->input('persentase'),
            'tanggal_mulai' => $request->input('awal'),
            'tanggal_akhir' => $request->input('akhir'),
        ]);

        return redirect()->route('diskon')->with('success', 'Genre data added successfully!');
    }

    public function EditDiskonPage($id){
        $diskon = Diskon::find($id);
        return view('CRUD.Diskon.diskon-form', compact('diskon'));
    }

    public function ProsesUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'persentase' => 'required|numeric',
            'awal' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($id, $request) {
                    // Check if the tanggal_mulai is unique and not overlapping with existing date ranges
                    $overlapping = Diskon::where('id_diskon', '!=', $id) // Ignore the current record
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
                    // Check if the tanggal_akhir is unique and not overlapping with existing date ranges
                    $overlapping = Diskon::where('id_diskon', '!=', $id) // Ignore the current record
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

        return redirect()->route('diskon')->with('success', 'Genre data updated successfully!');
    }

    public function DeleteDiskon($id){
        $barang = Diskon::find($id);
        $barang->delete();
        return back()->with('success','Data berhasil dihapus!');
    }
}
