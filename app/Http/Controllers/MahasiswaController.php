<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        if (Auth::check() && Auth::user()->role !== 'dosen') {

            abort(403);

        }
    }

    public function index()
    {
        $mahasiswa = User::where('role', 'mahasiswa')->get();

        return inertia('Mahasiswa', [

            'mahasiswa' => $mahasiswa,

        ]);
    }

        public function store(Request $request)
    {
        User::create([

            'nim' => $request->nim,

            'name' => $request->name,

            'email' => $request->email,

            'password' => bcrypt($request->password),

            'role' => 'mahasiswa',

        ]);

        return redirect('/mahasiswa');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect('/mahasiswa');
    }
}