<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email' => 'required|email',

            'password' => 'required',

        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | ROLE REDIRECT
            |--------------------------------------------------------------------------
            */

            if ($user->role === 'admin') {

                return redirect('/admin/dashboard');

            }

            if ($user->role === 'dosen') {

                return redirect('/dosen/dashboard');

            }

            return redirect('/mahasiswa/dashboard');

        }

        return back()->withErrors([

            'email' => 'Email atau password salah',

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PROFILE
    |--------------------------------------------------------------------------
    */

    public function updateProfile(Request $request)
    {
        $request->validate([

            'name' => 'required',

            'email' => 'required|email',

        ]);

        $user = Auth::user();

        $user->update([

            'name' => $request->name,

            'email' => $request->email,

        ]);

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PASSWORD
    |--------------------------------------------------------------------------
    */

    public function updatePassword(Request $request)
    {
        $request->validate([

            'password' => 'required|min:6',

        ]);

        $user = Auth::user();

        $user->update([

            'password' => bcrypt($request->password),

        ]);

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD PHOTO
    |--------------------------------------------------------------------------
    */

    public function uploadPhoto(Request $request)
    {
        $request->validate([

            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        $file = $request->file('photo');

        $filename = time() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('profile'), $filename);

        $user = Auth::user();

        $user->update([

            'photo' => $filename,

        ]);

        return back();
    }
}