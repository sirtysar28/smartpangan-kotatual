<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapid_tests', function (Blueprint $table) {
            $table->string('parameter')->nullable()->after('rapid_test_type')->comment('Parameter uji (mis. Aflatoksin B1, As, Manual)');
        });

        // Pisahkan data lama yang parameter tersimpan gabung di rapid_test_type
        $aflatoksin = ['okratoksin_a', 'aflatoksin_b1', 'aflatoksin_b2', 'aflatoksin_g1', 'aflatoksin_g2', 'aflatoksin_m1', 'aflatoksin_total'];
        foreach ($aflatoksin as $param) {
            DB::table('rapid_tests')->where('rapid_test_type', $param)->update([
                'rapid_test_type' => 'aflatoksin',
                'parameter' => $param,
            ]);
        }

        foreach (['as', 'cd', 'hg', 'sn', 'pb'] as $param) {
            DB::table('rapid_tests')->where('rapid_test_type', 'logam_berat_' . $param)->update([
                'rapid_test_type' => 'logam_berat',
                'parameter' => $param,
            ]);
        }

        DB::table('rapid_tests')->where('rapid_test_type', 'pestisida_manual')->update([
            'rapid_test_type' => 'pestisida',
            'parameter' => 'manual',
        ]);

        DB::table('rapid_tests')->where('rapid_test_type', 'klorin_manual')->update([
            'rapid_test_type' => 'pemutih_klorin',
            'parameter' => 'manual',
        ]);
    }

    public function down(): void
    {
        // Gabungkan kembali parameter ke rapid_test_type
        DB::table('rapid_tests')->where('rapid_test_type', 'aflatoksin')->whereNotNull('parameter')
            ->update(['rapid_test_type' => DB::raw('parameter')]);
        DB::table('rapid_tests')->where('rapid_test_type', 'logam_berat')->whereNotNull('parameter')
            ->update(['rapid_test_type' => DB::raw("CONCAT('logam_berat_', parameter)")]);
        DB::table('rapid_tests')->where('rapid_test_type', 'pestisida')->where('parameter', 'manual')
            ->update(['rapid_test_type' => 'pestisida_manual']);
        DB::table('rapid_tests')->where('rapid_test_type', 'pemutih_klorin')->where('parameter', 'manual')
            ->update(['rapid_test_type' => 'klorin_manual']);

        Schema::table('rapid_tests', function (Blueprint $table) {
            $table->dropColumn('parameter');
        });
    }
};
