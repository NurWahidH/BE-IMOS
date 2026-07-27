<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['nama_ruangan' => 'Instalasi Gawat Darurat', 'unit' => 'IGD'],
            ['nama_ruangan' => 'Farmasi Utama', 'unit' => 'FAR'],
            ['nama_ruangan' => 'Poliklinik Spesialis Anak', 'unit' => 'ANA'],
            ['nama_ruangan' => 'Intensive Care Unit', 'unit' => 'ICU'],
            ['nama_ruangan' => 'Laboratorium Patologi', 'unit' => 'LAB'],
            ['nama_ruangan' => 'Radiologi & Imaging', 'unit' => 'RAD'],
        ];

        foreach ($units as $unit) {
            Lokasi::firstOrCreate(
                ['unit' => $unit['unit']],
                ['nama_ruangan' => $unit['nama_ruangan']]
            );
        }
    }
}
