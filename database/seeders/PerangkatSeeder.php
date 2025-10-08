<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perangkat;

class PerangkatSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            ['nama' => 'Router Utama', 'tipe' => 'Router', 'lokasi' => 'Lantai 1 - Ruang Server', 'status' => 'aktif'],
            ['nama' => 'Switch Core', 'tipe' => 'Switch', 'lokasi' => 'Lantai 1 - Rack A', 'status' => 'aktif'],
            ['nama' => 'AccessPoint-Lobby', 'tipe' => 'Access Point', 'lokasi' => 'Lobby', 'status' => 'tidak_aktif'],
        ];

        foreach ($samples as $s) {
            Perangkat::create($s);
        }
    }
}
