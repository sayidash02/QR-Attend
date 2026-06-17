<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Kehadiran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KehadiranExport implements FromCollection, WithHeadings
{
    protected $matkulId;

    public function __construct($matkulId)
    {
        $this->matkulId = $matkulId;
    }

    public function collection()
    {
        $mahasiswas = User::where('role', 'mahasiswa')->get();

        return $mahasiswas->map(function ($mhs) {

            $hadir = Kehadiran::where('user_id', $mhs->id)
                ->where('mata_kuliah_id', $this->matkulId)
                ->where('status', 'hadir')
                ->count();

            $telat = Kehadiran::where('user_id', $mhs->id)
                ->where('mata_kuliah_id', $this->matkulId)
                ->where('status', 'telat')
                ->count();

            $izin = Kehadiran::where('user_id', $mhs->id)
                ->where('mata_kuliah_id', $this->matkulId)
                ->where('status', 'izin')
                ->count();

            $alpha = Kehadiran::where('user_id', $mhs->id)
                ->where('mata_kuliah_id', $this->matkulId)
                ->where('status', 'alpha')
                ->count();

            return [
                $mhs->nim,
                $mhs->name,
                $hadir,
                $telat,
                $izin,
                $alpha,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama Mahasiswa',
            'Total Hadir',
            'Total Telat',
            'Total Izin',
            'Total Alpha',
        ];
    }
}