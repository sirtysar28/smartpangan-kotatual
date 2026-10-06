<?php

namespace App\Support;

/**
 * Normalisasi input angka format Indonesia (atau Inggris) menjadi nilai numerik murni.
 *
 * Contoh:
 *   "60.983"    → 60983   (titik ribuan)
 *   "60.983,50" → 60983.5 (koma desimal)
 *   "60,983"    → 60.983  (koma desimal)
 *   "60983"     → 60983
 *   "Rp 60.983" → 60983
 *   60983       → 60983   (sudah numerik)
 */
class IdNumber
{
    public static function parse($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        $s = trim((string) $value);
        // buang "Rp", spasi, dan spasi tipis
        $s = str_replace(['Rp', 'rp', 'RP', ' ', "\t", "\xC2\xA0"], '', $s);
        if ($s === '') {
            return null;
        }

        // Karakter asing (bukan digit/titik/koma/minus) → kembalikan apa adanya,
        // biarkan validator Laravel yang menolak dengan pesan error.
        if (!preg_match('/^-?[\d.,]+$/', $s)) {
            return $value;
        }

        $hasComma = str_contains($s, ',');
        $hasDot   = str_contains($s, '.');

        if ($hasComma && $hasDot) {
            // Pemisah yang muncul TERAKHIR adalah desimal (contoh: 60.983,50 atau 60,983.50)
            if (strrpos($s, ',') > strrpos($s, '.')) {
                $s = str_replace('.', '', $s);   // titik = ribuan
                $s = str_replace(',', '.', $s);  // koma = desimal
            } else {
                $s = str_replace(',', '', $s);   // koma = ribuan
            }
        } elseif ($hasComma) {
            $s = str_replace(',', '.', $s);      // koma = desimal (id-ID)
        } elseif ($hasDot) {
            // Titik berpola ribuan (1.000 / 60.983 / 1.234.567) → buang titik.
            // Selain itu (60.5) dianggap desimal.
            if (preg_match('/^-?\d{1,3}(\.\d{3})+$/', $s)) {
                $s = str_replace('.', '', $s);
            }
        }

        if (!is_numeric($s)) {
            return $value;
        }

        return str_contains($s, '.') || stripos($s, 'e') !== false
            ? (float) $s
            : (int) $s;
    }

    /**
     * Merge beberapa key request menjadi angka ternormalisasi (hanya jika key ada).
     */
    public static function merge($request, array $keys): void
    {
        foreach ($keys as $key) {
            if ($request->exists($key)) {
                $request->merge([$key => self::parse($request->input($key))]);
            }
        }
    }
}
