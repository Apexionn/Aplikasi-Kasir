<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function search(Request $request)
    {
        $search = $request->query('search');

        if (!empty($search)) {
            $data = User::where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('role', 'LIKE', "%{$search}%")
                        ->get();
        } else {
            $data = User::all();
        }

        return view('CRUD.Users.users', compact('data'));
    }

        public function AddUserPage(){
        return view('CRUD.Users.users-add-form');
    }

    public function ProsesTambah(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required',
            'role' => 'required',
        ]);

        $hashedPassword = Hash::make($request->input('password'));

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashedPassword,
            'role' => $request->input('role'),
        ]);

        return redirect()->route('users')->with('success', 'Users data added successfully!')->with('status', 'added');
    }

    public function EditUserPage($id){
        $user = User::find($id);
        return view('CRUD.Users.users-form', compact('user'));
    }

    public function ProsesUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required',
        ]);

        $user = User::find($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->route('users')->with('success', 'Petugas data updated successfully!')->with('status', 'updated');
    }

    public function DeleteUsers($id){
        $user = User::find($id);

        if ($user->transactions->isNotEmpty()) {
            return back()->withErrors('Cannot delete this user because it has associated with transactions.');
        }

        $user->delete();
        return back()->with('success','Data berhasil dihapus!');
    }
}
