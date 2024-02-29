<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function AddGenrePage(){
        return view('CRUD.Genre.genre-add-form');
    }

    public function ProsesTambah(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $genre = Genre::create([
            'nama_genre' => $request->input('name'),
        ]);

        return redirect()->route('genre')->with('success', 'Genre data added successfully!')->with('status', 'added');
    }

    public function EditGenrePage($id){
        $genre = Genre::find($id);
        return view('CRUD.Genre.genre-form', compact('genre'));
    }

    public function ProsesUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $genre = Genre::find($id);

        $genre->nama_genre = $request->input('name');

        $genre->save();

        return redirect()->route('genre')->with('success', 'Genre data updated successfully!')->with('status', 'updated');
    }

    public function DeleteGenre($id){
        $barang = Genre::find($id);
        if ($barang->barangs2->isNotEmpty()) {
            return back()->withErrors('Cannot delete this genre because it has related data from barang.');
        }
        $barang->delete();
        return back()->with('success','Data berhasil dihapus!');
    }
}
