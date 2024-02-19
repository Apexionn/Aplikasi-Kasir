<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // 'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        //     // Handle File Upload
        // if($request->hasFile('image')){
        //     // Get filename with extension
        //     $filenameWithExt = $request->file('image')->getClientOriginalName();
        //     // Get just filename
        //     $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //     // Get just extension
        //     $extension = $request->file('image')->getClientOriginalExtension();
        //     // Filename to store
        //     $fileNameToStore = $filename.'_'.time().'.'.$extension;
        //     // Upload Image
        //     $path = $request->file('image')->storeAs('public/images', $fileNameToStore);
        // } else {
        //     $fileNameToStore = 'noimage.jpg';
        // }
        
        $tgl_registered = now()->toDateString();
        $imagePath = 'IMG/default-user.png';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'image' => $fileNameToStore,
            'image' => $imagePath,
            'tanggal_lahir' => $tgl_registered
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
