<?php

namespace Database\Seeders;

use App\Models\SistemOperasi;
use Illuminate\Database\Seeder;

class SistemOperasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $windows7 = SistemOperasi::create([
            'nama' => 'Windows 7 Profesional'
        ]);
        $windows7->details()->create([
            'tipe' => 'x86'
        ]);
        $windows7->details()->create([
            'tipe' => 'x64'
        ]);

        $windows10 = SistemOperasi::create([
            'nama' => 'Windows 10 Profesional'
        ]);
        $windows10->details()->create([
            'tipe' => 'x86'
        ]);
        $windows10->details()->create([
            'tipe' => 'x64'
        ]);
    }
}
