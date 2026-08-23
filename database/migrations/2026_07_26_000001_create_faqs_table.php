<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['nib', 'psat_pduk'])->default('nib')->comment('Kategori FAQ: NIB atau PSAT-PDUK');
            $table->string('question');
            $table->text('answer')->nullable();
            $table->json('items')->nullable()->comment('Bullet points (JSON array)');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default FAQ dari dokumen "FAQ NIB PSAT PDUK - Kota Tual"
        $now = now();
        $faqs = [
            // ===== FAQ NIB =====
            ['nib', 'Apa itu NIB?', 'NIB (Nomor Induk Berusaha) adalah bukti registrasi atau pendaftaran pelaku usaha yang berfungsi sebagai identitas resmi dalam menjalankan kegiatan usaha.', null],
            ['nib', 'Apa fungsi NIB?', 'NIB memiliki beberapa fungsi, yaitu:', json_encode([
                'Sebagai identitas dan bukti pendaftaran pelaku usaha.',
                'Berfungsi sebagai Angka Pengenal Importir (API-U atau API-P).',
                'Memberikan hak akses kepabeanan.',
                'Menjadi dasar pendaftaran kepesertaan BPJS Kesehatan dan BPJS Ketenagakerjaan.',
                'Memenuhi kewajiban pelaporan ketenagakerjaan untuk periode pertama pelaku usaha.',
            ])],
            ['nib', 'Apa manfaat memiliki NIB?', 'Manfaat memiliki NIB antara lain:', json_encode([
                'Usaha diakui sah dan terdaftar secara hukum.',
                'Memudahkan memperoleh pinjaman dari perbankan atau lembaga keuangan.',
                'Memudahkan akses terhadap pelatihan, subsidi, BLT UMKM, dan program pemerintah lainnya.',
                'Mempermudah kerja sama dengan perusahaan besar, e-commerce, maupun kegiatan ekspor.',
                'Membantu pemerintah memberikan kebijakan dan insentif yang lebih tepat sasaran kepada pelaku usaha.',
            ])],
            ['nib', 'Siapa saja yang dapat mengajukan NIB?', 'NIB dapat diajukan oleh pelaku usaha perseorangan maupun badan usaha sesuai dengan persyaratan yang berlaku.', null],
            ['nib', 'Apa persyaratan pembuatan NIB untuk pelaku usaha perseorangan?', 'Persyaratan yang diperlukan adalah:', json_encode([
                'KTP Pelaku Usaha.',
                'Alamat email yang aktif.',
            ])],
            ['nib', 'Apa persyaratan pembuatan NIB untuk badan usaha?', 'Persyaratan yang diperlukan meliputi:', json_encode([
                'KTP Direktur.',
                'NPWP Badan Usaha.',
                'SK AHU Online.',
                'Akta Notaris/Akta Pendirian Badan Usaha.',
                'Alamat email dan password yang aktif.',
                'Nomor HP aktif yang terdaftar di WhatsApp.',
                'Nomor Induk Koperasi (khusus koperasi).',
            ])],
            ['nib', 'Berapa lama proses pembuatan NIB?', 'Pembuatan NIB diselesaikan dalam waktu 1 (satu) hari kerja setelah seluruh persyaratan dinyatakan lengkap.', null],
            ['nib', 'Apakah pembuatan NIB dikenakan biaya?', 'Tidak. Layanan pembuatan NIB gratis atau tidak dipungut biaya.', null],
            ['nib', 'Berapa lama masa berlaku NIB?', 'NIB berlaku selama pelaku usaha masih menjalankan kegiatan usahanya.', null],
            ['nib', 'Ke mana saya dapat menghubungi jika membutuhkan informasi lebih lanjut?', 'Untuk informasi lebih lanjut, silakan hubungi DPMPTSP Kota Tual melalui kontak yang tersedia di bagian bawah halaman ini.', null],

            // ===== FAQ PSAT-PDUK =====
            ['psat_pduk', 'Apa itu PSAT-PDUK?', 'PSAT-PDUK (Pangan Segar Asal Tumbuhan Produksi Dalam Negeri Usaha Kecil) adalah pangan segar asal tumbuhan yang diproduksi di dalam negeri oleh pelaku usaha mikro dan kecil sesuai dengan ketentuan yang berlaku.', null],
            ['psat_pduk', 'Siapa yang dapat mengajukan registrasi PSAT-PDUK?', 'Registrasi PSAT-PDUK dapat diajukan oleh pelaku usaha mikro dan kecil yang memproduksi pangan segar asal tumbuhan di dalam negeri sesuai dengan ketentuan peraturan yang berlaku.', null],
            ['psat_pduk', 'Apa dasar hukum penyelenggaraan PSAT-PDUK?', 'Dasar hukum PSAT-PDUK meliputi:', json_encode([
                'Undang-Undang Nomor 18 Tahun 2012 tentang Pangan.',
                'Peraturan Pemerintah Nomor 86 Tahun 2019 tentang Keamanan Pangan.',
                'Peraturan Badan Nomor 10 Tahun 2025 tentang Standar Produk pada Penyelenggaraan Perizinan Berusaha Berbasis Risiko Subsektor Pangan Segar.',
                'Peraturan Badan Pangan Nasional Nomor 1 Tahun 2023 tentang Label Pangan Segar.',
            ])],
            ['psat_pduk', 'Apa saja persyaratan permohonan baru atau perpanjangan registrasi PSAT-PDUK?', 'Dokumen yang harus disiapkan meliputi:', json_encode([
                'Mengisi keterangan informasi produk.',
                'Surat pernyataan tentang komitmen.',
                'Surat perjanjian sewa untuk unit penanganan dengan status sewa (opsional).',
            ])],
            ['psat_pduk', 'Apa saja persyaratan untuk perubahan data registrasi PSAT-PDUK?', 'Persyaratan yang diperlukan meliputi:', json_encode([
                'Surat perjanjian sewa untuk unit penanganan dengan status sewa (opsional).',
                'Sertifikat Registrasi PSAT-PDUK yang masih berlaku (minimal 3 bulan sebelum masa berlaku berakhir).',
                'Formulir keterangan perubahan data registrasi.',
                'Desain label dan kemasan lama.',
                'Desain label dan kemasan baru.',
            ])],
            ['psat_pduk', 'Bagaimana cara mengajukan permohonan PSAT-PDUK?', 'Pelaku usaha mengajukan permohonan melalui Sistem OSS dengan melengkapi seluruh dokumen persyaratan yang diperlukan.', null],
            ['psat_pduk', 'Bagaimana proses verifikasi permohonan PSAT-PDUK?', 'Setelah permohonan diajukan:', json_encode([
                'Verifikator OPD Teknis melakukan penilaian dokumen.',
                'Dinas Ketahanan Pangan melakukan persetujuan dokumen.',
                'Apabila dokumen belum memenuhi syarat, pelaku usaha diminta melakukan perbaikan.',
                'Setelah disetujui, DPMPTSP melakukan persetujuan akhir dan menerbitkan PB-UMKU PSAT-PDUK.',
            ])],
            ['psat_pduk', 'Apa yang terjadi jika dokumen permohonan belum lengkap atau belum sesuai?', 'Permohonan akan dikembalikan untuk dilakukan perbaikan dokumen sebelum dapat diproses lebih lanjut.', null],
            ['psat_pduk', 'Melalui sistem apa permohonan PSAT-PDUK diajukan?', 'Permohonan diajukan melalui Sistem OSS (Online Single Submission).', null],
            ['psat_pduk', 'Ke mana saya dapat menghubungi jika membutuhkan informasi lebih lanjut?', 'Untuk informasi lebih lanjut, silakan hubungi DPMPTSP Kota Tual melalui kontak yang tersedia di bagian bawah halaman ini.', null],
        ];

        $rows = [];
        $sort = 1;
        foreach ($faqs as $f) {
            $rows[] = [
                'category'   => $f[0],
                'question'   => $f[1],
                'answer'     => $f[2],
                'items'      => $f[3],
                'is_active'  => true,
                'sort_order' => $sort++,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('faqs')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
