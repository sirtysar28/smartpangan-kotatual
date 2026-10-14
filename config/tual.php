<?php

/*
|--------------------------------------------------------------------------
| Data Wilayah Administratif Kota Tual
|--------------------------------------------------------------------------
| Sumber: Permendagri No. 137 Tahun 2017 jo. No. 72 Tahun 2019
| (5 Kecamatan — 27 Desa + 3 Kelurahan)
|
| Dipakai pada: filter & grafik Laporan Perizinan (halaman depan),
| dropdown Kecamatan/Desa pada form Perizinan Pangan (Dashboard Admin).
|
| Catatan: nama kecamatan ditulis tanpa awalan "Kec." agar seragam
| dengan penyimpanan data kolom `permits.kecamatan`.
*/

return [

    'kecamatan' => [

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
    ],
];
