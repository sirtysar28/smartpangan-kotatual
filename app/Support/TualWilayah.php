<?php

namespace App\Support;

/**
 * Data wilayah administratif Kota Tual (fallback permanen).
 *
 * Sumber: Permendagri No. 137 Tahun 2017 jo. No. 72 Tahun 2019
 * (5 Kecamatan — 27 Desa + 3 Kelurahan).
 *
 * Dipakai sebagai fallback bila config('tual.kecamatan') tidak tersedia
 * (mis. file config belum ter-deploy atau config masih cache lama),
 * sehingga halaman Laporan Perizinan tidak pernah crash.
 */
class TualWilayah
{
    public const KECAMATAN_DESA = [
        'Pulau Dullah Selatan' => [
            'Taar',
            'Tual',
            'Ketsoblak',
            'Lodar El',
            'Masrum',
        ],
        'Pulau Dullah Utara' => [
            'Dullah',
            'Dullah Laut',
            'Fiditan',
            'Labetawi',
            'Ngadi',
            'Ohoitahit',
            'Ohoitel',
            'Tamedan',
        ],
        'Tayando Tam' => [
            'Tam Ngurhir',
            'Tayando Langgiar',
            'Tayando Ohoiel',
            'Tayando Yamru',
            'Tayando Yamtel',
        ],
        'Pulau-Pulau Kur' => [
            'Finualen',
            'Kaimear',
            'Lokwirin',
            'Sermaf',
            'Tubyal',
        ],
        'Kur Selatan' => [
            'Hirit',
            'Kanara',
            'Niela',
            'Rumoin',
            'Tiflen',
            'Warkar',
            'Yapas',
        ],
    ];

    /**
     * Ambil mapping Kecamatan => Desa/Kelurahan.
     * Utama: config('tual.kecamatan'); fallback: daftar bawaan di atas.
     */
    public static function kecamatan(): array
    {
        $cfg = config('tual.kecamatan');

        return (is_array($cfg) && $cfg !== []) ? $cfg : self::KECAMATAN_DESA;
    }
}
