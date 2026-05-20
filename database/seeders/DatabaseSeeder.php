<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        User::create([

            'name' => 'Admin',

            'email' => 'admin@gmail.com',

            'password' => bcrypt('password'),

            'role' => 'admin',

        ]);



        /*
        |--------------------------------------------------------------------------
        | DOSEN
        |--------------------------------------------------------------------------
        */

        User::create([

            'name' => 'Pak Fatan',

            'nidn' => '12345678',

            'matkul' => 'Pemrograman Web',

            'email' => 'dosen@gmail.com',

            'password' => bcrypt('password'),

            'role' => 'dosen',

        ]);



        /*
        |--------------------------------------------------------------------------
        | MAHASISWA
        |--------------------------------------------------------------------------
        */

        User::create([

            'name' => 'Wildan',

            'nim' => '231110027',

            'email' => 'mahasiswa@gmail.com',

            'password' => bcrypt('password'),

            'role' => 'mahasiswa',

        ]);
    }
}