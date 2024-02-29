<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Barang;
use Illuminate\Http\Request;


class BarangController extends Controller
{

    public function search(Request $request)
    {
        $search = $request->query('search');

        if (!empty($search)) {
            $data = Barang::where('kode_barang', 'LIKE', "%{$search}%")
                        ->orWhere('nama_barang', 'LIKE', "%{$search}%")
                        ->orWhereHas('genres', function ($query) use ($search) {
                            $query->where('nama_genre', 'LIKE', "%{$search}%");
                        })
                        ->paginate(10);
        } else {
            $data = Barang::with('genres')->paginate(10);
        }

        return view('CRUD.Barang.barang', compact('data'));
    }

    public function AddBarangPage(){
        $genres = Genre::all();
        return view('CRUD.Barang.barang-add-form', compact('genres'));
    }

    public function ProsesTambah(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:6',
            'name' => 'required|string|max:255',
            'stok' => 'required',
            'image_varchar' => 'image|mimes:png,jpg,jpeg,webp',
            'harga' => 'required',
            'genres' => 'required|array',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_varchar')) {
            $imageVarchar = $request->file('image_varchar');
            $fileName = time() . '_' . $imageVarchar->getClientOriginalName();

            $imageVarchar->storeAs('public/images', $fileName);

            $imagePath = request()->getSchemeAndHttpHost() . '/storage/images/' . $fileName;
        }

        $barang = Barang::create([
            'kode_barang' => $request->input('kode'),
            'nama_barang' => $request->input('name'),
            'stok' => $request->input('stok'),
            'image' => $imagePath,
            'harga' => $request->input('harga'),
        ]);

        $barang->genres()->attach($request->input('genres'));

        return redirect()->route('games')->with('success', 'Data Added Successfully')->with('status', 'added');
    }


    public function EditBarangPage($id){
        $genres = Genre::all();
        $barang = Barang::find($id);
        return view('CRUD.Barang.barang-form', compact('genres', 'barang'));
    }

    public function ProsesUpdate(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:6',
            'name' => 'required|string|max:255',
            'stok' => 'required',
            'image_varchar' => 'sometimes|image|mimes:png,jpg,jpeg',
            'harga' => 'required',
            'genres' => 'sometimes|array',
        ]);

        $barang = Barang::find($id);

        if ($request->hasFile('image_varchar')) {
            $imageVarchar = $request->file('image_varchar');
            $fileName = time() . '_' . $imageVarchar->getClientOriginalName();
            $imageVarchar->storeAs('public/images', $fileName);
            $imagePath = request()->getSchemeAndHttpHost() . '/storage/images/' . $fileName;
            $barang->image = $imagePath;
        }

        $barang->kode_barang = $request->input('kode');
        $barang->nama_barang = $request->input('name');
        $barang->stok = $request->input('stok');
        $barang->harga = $request->input('harga');
        $barang->save();

        $barang->genres()->sync($request->input('genres'));

        return redirect()->route('games')->with('success', 'Data Updated Successfully')->with('status', 'updated');
    }


    public function DeleteBarang($id)
    {
        $barang = Barang::with('detailGenres')->find($id);

        if ($barang->detailGenres->isNotEmpty() || $barang->detailTransactions->isNotEmpty()) {
            return back()->withErrors('Cannot delete this barang because it has associated detail genres or transactions.');
        }

        $barang->delete();

        return back()->with('success','Barang berhasil dihapus!');
    }

}
