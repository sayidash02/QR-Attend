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

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );



        /*
        |--------------------------------------------------------------------------
        | DOSEN
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['email' => 'dosen@gmail.com'],
            [
                'name' => 'Pak Fatan',
                'nidn' => '12345678',
                'matkul' => 'Pemrograman Web',
                'password' => bcrypt('password'),
                'role' => 'dosen',
            ]
        );



        /*
        |--------------------------------------------------------------------------
        | MAHASISWA
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['email' => 'mahasiswa@gmail.com'],
            [
                'name' => 'Wildan',
                'nim' => '231110027',
                'password' => bcrypt('password'),
                'role' => 'mahasiswa',
            ]
        );
    }
}