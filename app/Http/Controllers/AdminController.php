<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAMPIL DOSEN
    |--------------------------------------------------------------------------
    */

    public function dosen()
    {
        $dosen = User::where('role', 'dosen')->get();

        return inertia('Admin/Dosen', [

            'dosen' => $dosen,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAH DOSEN
    |--------------------------------------------------------------------------
    */

    public function storeDosen(Request $request)
    {
        $dosen = User::create([
            'name' => $request->name,
            'nidn' => $request->nidn,
            'matkul' => $request->matkul,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'dosen',
        ]);

        if ($request->matkul) {
            $subjects = explode(',', $request->matkul);
            foreach ($subjects as $subject) {
                $subjectName = trim($subject);
                if (!empty($subjectName)) {
                    \App\Models\MataKuliah::create([
                        'nama_matkul' => $subjectName,
                        'dosen_id' => $dosen->id,
                    ]);
                }
            }
        }

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAH MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function storeMahasiswa(Request $request)
    {
        User::create([

            'name' => $request->name,

            'nim' => $request->nim,

            'email' => $request->email,

            'password' => bcrypt($request->password),

            'role' => 'mahasiswa',

        ]);

        return back();
    }

    public function destroyDosen($id)
    {
        $dosen = User::findOrFail($id);
        
        // Delete related mata_kuliahs first
        \App\Models\MataKuliah::where('dosen_id', $dosen->id)->delete();
        
        $dosen->delete();

        return response()->json([
            'message' => 'Dosen berhasil dihapus'
        ]);
    }

    public function destroyMahasiswa($id)
    {
        $mahasiswa = User::findOrFail($id);
        
        $mahasiswa->delete();

        return response()->json([
            'message' => 'Mahasiswa berhasil dihapus'
        ]);
    }

    public function updateMatkul(Request $request, $id)
    {
        $dosen = User::findOrFail($id);

        $dosen->update([
            'matkul' => $request->matkul,
        ]);

        // Delete existing mata_kuliahs for this dosen
        \App\Models\MataKuliah::where('dosen_id', $dosen->id)->delete();

        // Create new ones
        if ($request->matkul) {
            $subjects = explode(',', $request->matkul);
            foreach ($subjects as $subject) {
                $subjectName = trim($subject);
                if (!empty($subjectName)) {
                    \App\Models\MataKuliah::create([
                        'nama_matkul' => $subjectName,
                        'dosen_id' => $dosen->id,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Matakuliah berhasil diupdate'
        ]);
    }
}