{{-- ===== KOP LAPORAN PDF: logo + nama aplikasi (DomPDF-safe: table layout) ===== --}}
@php
    $logoData = '';
    $logoPath = public_path('logo.png');
    if (is_file($logoPath)) { $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)); }
@endphp
@if($logoData)
<table width="100%" style="margin-bottom:14px">
    <tr>
        <td width="60" valign="middle">
            <img src="{{ $logoData }}" width="52" height="52" style="border-radius:8px">
        </td>
        <td valign="middle" style="padding-left:10px">
            <div style="font-size:14px;font-weight:bold;letter-spacing:1px;color:#0f172a">PEMERINTAH KOTA TUAL</div>
            <div style="font-size:13px;font-weight:bold;color:#1a5276">PANCA &mdash; Pangan Cerdas Maryadat Kota Tual</div>
            <div style="font-size:9px;color:#64748b">Sistem Monitoring Stabilisasi Harga, Pasokan, dan Keamanan Pangan Kota Tual, Provinsi Maluku</div>
        </td>
    </tr>
</table>
<div style="border-bottom:3px double #1a5276;margin-bottom:16px"></div>
@else
<div class="header" style="text-align:center;border-bottom:3px double #1a5276;padding-bottom:10px;margin-bottom:16px">
    <h2 style="color:#1a5276;margin:0;font-size:14px">PEMERINTAH KOTA TUAL</h2>
    <p style="margin:2px 0 0;font-size:12px;font-weight:bold;color:#1a5276">PANCA &mdash; Pangan Cerdas Maryadat Kota Tual</p>
</div>
@endif
