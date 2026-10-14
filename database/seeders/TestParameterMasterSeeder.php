<?php

namespace Database\Seeders;

use App\Models\TestParameterMaster;
use Illuminate\Database\Seeder;

/**
 * Seeder Master Parameter Uji (Revisi 14 Okt 2026)
 * Diisi ±8 parameter utama yang dipakai.
 * CATATAN: menunggu kiriman data master resmi (daftar parameter + mapping
 * komoditas) dari pihak ketahanan pangan — seeder ini hanya nilai awal
 * dan dapat diubah kapan saja dari menu Admin → Master Parameter.
 */
class TestParameterMasterSeeder extends Seeder
{
    public function run(): void
    {
        $masters = [
            ['name' => 'Boraks (Asam Borat)', 'category' => null, 'unit' => '%', 'standard' => 'Tidak boleh terdeteksi', 'method' => 'SNI 01-2894-1992'],
            ['name' => 'Formalin (Formaldehida)', 'category' => null, 'unit' => 'mg/kg', 'standard' => 'Tidak boleh terdeteksi', 'method' => 'SNI 01-2894-1992'],
            ['name' => 'Residu Pestisida', 'category' => 'residu_pestisida', 'unit' => 'mg/kg', 'standard' => 'SNI 7313', 'method' => 'Kromatografi'],
            ['name' => 'Aflatoksin B1', 'category' => 'mikotoksin', 'unit' => 'ppb', 'standard' => 'SNI 7385', 'method' => 'HPLC'],
            ['name' => 'Timbal (Pb)', 'category' => 'logam_berat', 'unit' => 'mg/kg', 'standard' => 'SNI 7387', 'method' => 'AAS'],
            ['name' => 'Kadmium (Cd)', 'category' => 'logam_berat', 'unit' => 'mg/kg', 'standard' => 'SNI 7387', 'method' => 'AAS'],
            ['name' => 'Escherichia coli', 'category' => 'mikrobiologi', 'unit' => 'APM/g', 'standard' => 'SNI 7388', 'method' => 'ALT'],
            ['name' => 'Salmonella spp.', 'category' => 'mikrobiologi', 'unit' => 'Negatif/25g', 'standard' => 'SNI 7388', 'method' => 'Biokonfirmasi'],
        ];

        foreach ($masters as $m) {
            TestParameterMaster::updateOrCreate(['name' => $m['name']], array_merge($m, ['is_active' => true]));
        }
    }
}
