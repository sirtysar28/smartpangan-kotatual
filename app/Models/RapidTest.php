<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapidTest extends Model
{
    use HasFactory;

    /** Pilihan jenis rapid test */
    public const TYPES = [
        'boraks' => 'Boraks',
        'formalin' => 'Formalin (Formaldehida)',
        'rhodamin_b' => 'Rhodamin B',
        'methanil_yellow' => 'Methanil Yellow',
        'karbit' => 'Karbit (Kalsium Karbida)',
        'pemutih_klorin' => 'Pemutih / Klorin (Bleaching Agent)',
        'pewarna_tekstil' => 'Pewarna Tekstil',
        'antibiotik' => 'Residu Antibiotik',
        'pestisida' => 'Residu Pestisida (Cepat)',
        'lainnya' => 'Lainnya',

        // Jenis rapid test dengan parameter uji khusus
        'aflatoksin' => 'Rapid Test Aflatoksin',
        'logam_berat' => 'Rapid Test Logam Berat',
    ];

    /**
     * Parameter uji per jenis rapid test (sesuai daftar parameter rapid test).
     * Revisi 15 Okt 2026: jenis "Manual" dihapus — admin memilih parameter yang sudah
     * ada atau mengetik sendiri parameter baru langsung di form (input pilih + ketik).
     */
    public const PARAMETERS = [
        'aflatoksin' => [
            'okratoksin_a' => 'Okratoksin A',
            'aflatoksin_b1' => 'Aflatoksin B1',
            'aflatoksin_b2' => 'Aflatoksin B2',
            'aflatoksin_g1' => 'Aflatoksin G1',
            'aflatoksin_g2' => 'Aflatoksin G2',
            'aflatoksin_m1' => 'Aflatoksin M1',
            'aflatoksin_total' => 'Aflatoksin Total',
        ],
        'logam_berat' => [
            'as' => 'As (Arsen)',
            'cd' => 'Cd (Kadmium)',
            'hg' => 'Hg (Merkuri)',
            'sn' => 'Sn (Timah)',
            'pb' => 'Pb (Timbal)',
        ],
    ];

    protected $fillable = [
        'test_date', 'sample_location', 'commodity_name', 'commodity_type',
        'rapid_test_type', 'parameter', 'result', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'test_date' => 'date',
        ];
    }

    /**
     * Normalisasi parameter uji (Revisi 15 Okt 2026):
     * - Jika admin memilih parameter bawaan (label/kunci), simpan kuncinya agar konsisten.
     * - Jika admin mengetik parameter baru, simpan apa adanya (trim, kapital awal).
     */
    protected function setParameterAttribute($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            $this->attributes['parameter'] = null;
            return;
        }

        $lower = mb_strtolower($value);
        foreach (self::PARAMETERS as $parameters) {
            foreach ($parameters as $key => $label) {
                if ($lower === $key || $lower === mb_strtolower($label)) {
                    $this->attributes['parameter'] = $key;
                    return;
                }
            }
        }

        // Parameter baru yang diketik admin — simpan teksnya
        $this->attributes['parameter'] = $value;
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }

    /** Label dari suatu nilai parameter (kunci bawaan maupun teks bebas yang diketik admin) */
    public static function parameterLabel(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        foreach (self::PARAMETERS as $parameters) {
            if (isset($parameters[$value])) {
                return $parameters[$value];
            }
        }

        return ucfirst(str_replace('_', ' ', $value));
    }

    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->rapid_test_type] ?? ucfirst(str_replace('_', ' ', $this->rapid_test_type));
    }

    /** Label parameter uji, mis. "Aflatoksin B1", "As (Arsen)", atau teks yang diketik admin */
    public function getParameterLabelAttribute()
    {
        return self::parameterLabel($this->parameter);
    }

    public function getResultLabelAttribute()
    {
        return $this->result === 'positif' ? 'Positif (Terkontaminasi)' : 'Negatif (Aman)';
    }

    public function getResultColorAttribute()
    {
        return $this->result === 'positif' ? '#ef4444' : '#10b981';
    }
}
