<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        // Read API key from settings table (not .env)
        $apiKey = Setting::get('groq_api_key');

        if (!$apiKey) {
            // Fallback: try reading from .env as backup
            $apiKey = env('GROQ_API_KEY', '');
        }

        if (!$apiKey) {
            return response()->json([
                'error' => 'API Key belum dikonfigurasi. Silakan atur di menu Pengaturan Admin.',
            ], 500);
        }

        // Read model & params from settings — default & otomatis dialihkan ke GPT-OSS 120B
        $model = $this->normalizeGroqModel(Setting::get('groq_model', 'openai/gpt-oss-120b'));
        $maxTokens = (int) Setting::get('ai_max_tokens', 1000);
        $temperature = (float) Setting::get('ai_temperature', 0.7);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Kamu adalah asisten AI Pangan Cerdas (Panca) Maryadat Kota Tual - Layanan Pangan Cerdas untuk Kota Tual, Provinsi Maluku. 
Kamu membantu menjawab pertanyaan terkait:
- Panel Harga Pangan (19 komoditas strategis)
- Ketersediaan Pangan (jumlah stok & ketahanan stok)
- Kerawanan Pangan (FSVA & infografis)
- Keamanan Pangan (pemeriksaan & rekomendasi)
- Kegiatan Pengendalian Inflasi (Gerakan Pangan Murah, Kios Pangan, Sidak Operasi Pasar, Cadangan Pangan, Pemanfaatan Pangan Lestari)
- Perizinan Informasi (izin pelaku usaha pangan)
- Informasi UMKM dan Marketplace di Kota Tual
- Prediksi harga dan analisis inflasi

Jawab dalam Bahasa Indonesia dengan ramah dan profesional. Jika ditanya hal di luar konteks, arahkan kembali ke topik yang relevan.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->message,
                    ],
                ],
                'max_tokens' => $maxTokens,
                'temperature' => $temperature,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'response' => $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat memproses permintaan Anda.',
                ]);
            }

            // Log the actual error from Groq
            \Log::error('Groq API Error: ' . $response->status() . ' - ' . $response->body());

            $errorBody = $response->json();
            $errorMsg = $errorBody['error']['message'] ?? 'Gagal menghubungi AI. Silakan coba lagi.';

            return response()->json([
                'error' => $errorMsg,
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Normalisasi nama model Groq:
     * - otomatis dialihkan (alias) ke GPT-OSS 120B bila cocok,
     * - melengkapi prefix "openai/" untuk varian gpt-oss,
     * - memetakan model lama yang sudah deprecated.
     */
    private function normalizeGroqModel(?string $model): string
    {
        $m = strtolower(trim($model ?? ''));

        $aliases = [
            // GPT-OSS (otomatis dialihkan ke 120B)
            'gpt-oss-120b' => 'openai/gpt-oss-120b',
            'gpt-oss' => 'openai/gpt-oss-120b',
            'gpt oss 120b' => 'openai/gpt-oss-120b',
            'gptoss120b' => 'openai/gpt-oss-120b',
            'openai/gpt-oss' => 'openai/gpt-oss-120b',
            'openai/gpt-oss-120b' => 'openai/gpt-oss-120b',
            'gpt-oss-20b' => 'openai/gpt-oss-20b',
            'openai/gpt-oss-20b' => 'openai/gpt-oss-20b',
            // Legacy
            'llama-3.3-70b' => 'llama-3.3-70b-versatile',
            'llama3.3-70b' => 'llama-3.3-70b-versatile',
            'llama-3.1-8b' => 'llama-3.1-8b-instant',
            'llama3.1-8b' => 'llama-3.1-8b-instant',
        ];

        return $aliases[$m] ?? ($m !== '' ? $m : 'openai/gpt-oss-120b');
    }
}
