<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Laporan Keamanan Pangan</title>
<style>
    body { font-family: sans-serif; font-size: 11px; color: #1e293b; margin: 40px 42px; }

    /* KOP */
    .kop { text-align: center; border-bottom: 3px double #1a5276; padding-bottom: 12px; margin-bottom: 18px; }
    .kop .pemda { font-size: 15px; font-weight: bold; letter-spacing: 1px; }
    .kop .app { font-size: 13px; font-weight: bold; color: #1a5276; margin-top: 2px; }
    .kop .tagline { font-size: 10px; color: #64748b; }
    .judul-laporan { text-align: center; margin: 14px 0 4px; }
    .judul-laporan h3 { margin: 0; font-size: 14px; text-decoration: underline; letter-spacing: 0.5px; }
    .judul-laporan .periode { font-size: 11px; color: #475569; margin-top: 3px; }

    /* RINGKASAN */
    .ringkasan { border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px 12px; margin: 14px 0 16px; background: #f8fafc; }
    .ringkasan table { width: 100%; border: none; }
    .ringkasan td { border: none; padding: 3px 6px; font-size: 11px; }
    .ringkasan .num { font-weight: bold; font-size: 16px; color: #1a5276; }
    .ringkasan .num.merah { color: #c0392b; }
    .ringkasan .num.hijau { color: #1e8449; }

    /* TABEL */
    table.data { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    table.data th, table.data td { border: 1px solid #b6c2cf; padding: 5px 7px; text-align: left; vertical-align: top; }
    table.data th { background: #1a5276; color: #fff; font-size: 10px; }
    table.data tr:nth-child(even) td { background: #f1f6fa; }
    table.data td.center { text-align: center; }

    .sec-title { background: #d6eaf8; border: 1px solid #a9cce3; border-left: 5px solid #1a5276; padding: 6px 10px; font-weight: bold; font-size: 12px; color: #1a5276; margin: 16px 0 8px; }

    /* Badge hasil */
    .badge { display: inline-block; padding: 1px 7px; border-radius: 8px; font-size: 9px; font-weight: bold; }
    .bg-merah { background: #fdedec; color: #c0392b; border: 1px solid #f5b7b1; }
    .bg-hijau { background: #eafaf1; color: #1e8449; border: 1px solid #a9dfbf; }
    .bg-abu { background: #f4f6f7; color: #5d6d7e; border: 1px solid #d5dbdb; }

    /* Blok uji lab */
    .lab-block { border: 1px solid #b6c2cf; border-radius: 6px; margin-bottom: 10px; page-break-inside: avoid; }
    .lab-head { background: #eaf2f8; border-bottom: 1px solid #b6c2cf; padding: 6px 10px; }
    .lab-head .lab-title { font-weight: bold; font-size: 11px; color: #1a5276; }
    .lab-head .lab-meta { font-size: 9.5px; color: #5d6d7e; margin-top: 2px; }
    .lab-block table.data { margin: 0; }
    .lab-block table.data th { background: #5499c7; }

    .kosong { text-align: center; color: #94a3b8; font-style: italic; padding: 14px; }

    /* Kesimpulan */
    .kesimpulan { border: 1px solid #b6c2cf; border-radius: 6px; padding: 10px 12px; background: #fef9e7; margin-top: 8px; }
    .kesimpulan ul { margin: 4px 0 0 16px; padding: 0; }
    .kesimpulan li { margin-bottom: 3px; }

    /* Tanda tangan */
    .ttd { margin-top: 28px; width: 100%; }
    .ttd table { width: 100%; border: none; }
    .ttd td { border: none; text-align: center; padding: 4px; font-size: 11px; }
    .ttd .kota { margin-bottom: 4px; }
    .ttd .jabatan { margin-bottom: 60px; }
    .ttd .nama { font-weight: bold; text-decoration: underline; }

    .footer-cetak { text-align: center; color: #94a3b8; font-size: 8.5px; margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 6px; }

    /* page break */
    .page-break { page-break-before: always; }
</style>
</head>
<body>

<div class="kop">
    <div class="pemda">PEMERINTAH KOTA TUAL</div>
    <div class="app">PANCA &mdash; Pangan Cerdas Maryadat Kota Tual</div>
    <div class="tagline">Sistem Monitoring Stabilisasi Harga, Pasokan, dan Keamanan Pangan Kota Tual, Provinsi Maluku</div>
</div>

<div class="judul-laporan">
    <h3>LAPORAN HASIL UJI KEAMANAN PANGAN</h3>
    <div class="periode">{{ $periode }} &bull; Dicetak: {{ now()->translatedFormat('d F Y') }}</div>
</div>

<div class="ringkasan">
    <table>
        <tr>
            <td style="width:25%"><div class="num">{{ $summary['total_rapid'] }}</div>Total Uji Rapid Test</td>
            <td style="width:25%"><div class="num hijau">{{ $summary['rapid_negatif'] }}</div>Negatif (Aman)</td>
            <td style="width:25%"><div class="num merah">{{ $summary['rapid_positif'] }}</div>Positif (Terkontaminasi)</td>
            <td style="width:25%"><div class="num">{{ $summary['total_lab'] }}</div>Total Uji Laboratorium</td>
        </tr>
        <tr>
            <td><span class="num hijau">{{ $summary['lab_ms'] }}</span> Uji Lab Memenuhi Syarat (MS)</td>
            <td><span class="num merah">{{ $summary['lab_tms'] }}</span> Uji Lab Tidak Memenuhi Syarat (TMS)</td>
            <td colspan="2"><span class="num">{{ $summary['total_parameters'] }}</span> Total Parameter Uji Dianalisis</td>
        </tr>
    </table>
</div>

@if($summary['report_type'] === 'all' || $summary['report_type'] === 'rapid')
<div class="sec-title">A. HASIL UJI RAPID TEST</div>
@if($rapidTests->count())
<table class="data">
    <thead>
        <tr>
            <th style="width:26px">No</th>
            <th style="width:62px">Tanggal</th>
            <th>Komoditas</th>
            <th>Jenis Komoditas</th>
            <th>Lokasi Sampel</th>
            <th>Jenis Rapid Test</th>
            <th style="width:92px">Hasil Uji</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rapidTests as $i => $r)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td class="center">{{ $r->test_date?->format('d/m/Y') }}</td>
            <td>{{ $r->commodity_name }}</td>
            <td>{{ $r->commodity_type ?? '-' }}</td>
            <td>{{ $r->sample_location }}</td>
            <td>{{ $r->type_label }}</td>
            <td class="center"><span class="badge {{ $r->result === 'positif' ? 'bg-merah' : 'bg-hijau' }}">{{ strtoupper($r->result) }}</span></td>
            <td>{{ $r->notes ? \Illuminate\Support\Str::limit($r->notes, 60) : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="kosong">Tidak ada data uji rapid test pada periode ini.</div>
@endif
@endif

@if($summary['report_type'] === 'all' || $summary['report_type'] === 'lab')
@if($summary['report_type'] === 'all')
<div class="page-break"></div>
@endif
<div class="sec-title">B. HASIL UJI LABORATORIUM</div>
@if($labTests->count())
@foreach($labTests as $i => $lab)
@php
    $overall = $lab->parameters->contains(fn($p) => $p->status === 'TMS') ? 'TMS' : ($lab->parameters->count() > 0 ? 'MS' : null);
@endphp
<div class="lab-block">
    <div class="lab-head">
        <div class="lab-title">Uji #{{ $i + 1 }}: {{ $lab->commodity_name }} &mdash; {{ $lab->psat_type_label }}
            @if($overall)
            &nbsp;<span class="badge {{ $overall === 'TMS' ? 'bg-merah' : 'bg-hijau' }}">{{ $overall === 'TMS' ? 'TMS (Tidak Memenuhi Syarat)' : 'MS (Memenuhi Syarat)' }}</span>
            @endif
        </div>
        <div class="lab-meta">
            Tanggal Uji: {{ $lab->test_date?->format('d/m/Y') }} &bull; Lab Uji: {{ $lab->lab_name }} &bull;
            Lokasi Sampel: {{ $lab->sample_location }} &bull; Metode: {{ $lab->method ?? '-' }} &bull; Sertifikat CoA: {{ $lab->certificate_file ? 'Ada (terlampir di aplikasi)' : 'Tidak ada' }}
        </div>
    </div>
    @if($lab->parameters->count())
    <table class="data">
        <thead>
            <tr>
                <th style="width:26px">No</th>
                <th style="width:120px">Kelompok Uji</th>
                <th>Parameter</th>
                <th style="width:74px">Hasil</th>
                <th style="width:52px">Nilai</th>
                <th style="width:48px">Satuan</th>
                <th style="width:64px">Standar</th>
                <th style="width:56px">LOD / LOQ</th>
                <th style="width:58px">Status</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lab->parameters as $j => $p)
            <tr>
                <td class="center">{{ $j + 1 }}</td>
                <td>{{ $p->category_label }}</td>
                <td>{{ $p->parameter }}</td>
                <td><span class="badge {{ $p->result === 'terdeteksi' ? 'bg-merah' : 'bg-hijau' }}">{{ $p->result_label }}</span></td>
                <td>{{ $p->value ?? '-' }}</td>
                <td>{{ $p->unit ?? '-' }}</td>
                <td>{{ $p->standard ?? '-' }}</td>
                <td>{{ trim(($p->lod ?? '-') . ' / ' . ($p->loq ?? '-')) }}</td>
                <td class="center">@if($p->status)<span class="badge {{ $p->status === 'TMS' ? 'bg-merah' : 'bg-hijau' }}">{{ $p->status }}</span>@else - @endif</td>
                <td>{{ $p->method ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="kosong">Belum ada parameter uji yang diinput untuk sampel ini.</div>
    @endif
    @if($lab->notes)
    <div style="padding: 5px 10px; font-size: 9.5px; color: #5d6d7e; border-top: 1px dashed #d5dbdb;"><em>Catatan: {{ $lab->notes }}</em></div>
    @endif
</div>
@endforeach
@else
<div class="kosong">Tidak ada data uji laboratorium pada periode ini.</div>
@endif
@endif

<div class="kesimpulan">
    <strong>Kesimpulan:</strong>
    <ul>
        @if($summary['report_type'] === 'all' || $summary['report_type'] === 'rapid')
        <li>Terdapat <strong>{{ $summary['total_rapid'] }}</strong> sampel uji rapid test: <strong>{{ $summary['rapid_negatif'] }} negatif</strong> (aman) dan <strong>{{ $summary['rapid_positif'] }} positif</strong> terdeteksi bahan berbahaya{{ $summary['rapid_positif'] > 0 ? ' (perlu tindak lanjut penarikan produk)' : '' }}.</li>
        @endif
        @if($summary['report_type'] === 'all' || $summary['report_type'] === 'lab')
        <li>Terdapat <strong>{{ $summary['total_lab'] }}</strong> sampel uji laboratorium ({{ $summary['total_parameters'] }} parameter): <strong>{{ $summary['lab_ms'] }} MS</strong> dan <strong>{{ $summary['lab_tms'] }} TMS</strong>{{ $summary['lab_tms'] > 0 ? ' (perlu tindak lanjut sesuai regulasi pangan)' : '' }}.</li>
        @endif
        <li>Laporan ini dihasilkan otomatis oleh sistem PANCA &mdash; Pangan Cerdas Maryadat Kota Tual.</li>
    </ul>
</div>

<div class="ttd">
    <table>
        <tr>
            <td style="width:55%"></td>
            <td>
                <div class="kota">Tual, {{ now()->translatedFormat('d F Y') }}</div>
                <div class="jabatan">Ketua Maryadat Pangan<br>Kota Tual</div>
                <div class="nama">( .......................................... )</div>
            </td>
        </tr>
    </table>
</div>

<div class="footer-cetak">PANCA &mdash; Pangan Cerdas Maryadat Kota Tual &copy; {{ now()->year }} Pemerintah Kota Tual &bull; Dicetak dari sistem oleh {{ auth()->user()?->name ?? '-' }}</div>

</body>
</html>
