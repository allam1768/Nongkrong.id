<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiBaristaService
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected string $defaultModel;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.nine_router.base_url', 'http://127.0.0.1:20128/v1'), '/');
        $this->apiKey = config('services.nine_router.api_key') ?: $this->resolveLocal9RouterKey();
        $this->defaultModel = config('services.nine_router.model', 'ag/gemini-3.8-flash-low');
    }

    /**
     * Resolve API key from local 9Router sqlite database if not configured in .env
     */
    protected function resolveLocal9RouterKey(): ?string
    {
        $home = $_SERVER['HOME'] ?? getenv('HOME') ?: '/home/puutraa';
        $sqlitePath = "{$home}/.9router/db/data.sqlite";
        if (file_exists($sqlitePath)) {
            try {
                $pdo = new \PDO("sqlite:{$sqlitePath}");
                $stmt = $pdo->query('SELECT key FROM apiKeys ORDER BY id ASC LIMIT 1');
                $key = $stmt ? $stmt->fetchColumn() : null;
                if ($key) {
                    return (string) $key;
                }
            } catch (Exception $e) {
                Log::debug('Could not read 9Router sqlite key: ' . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Chat with AI Barista with live catalog context
     *
     * @return array{text: string, showCta: bool, ctaText: string, actionType: string, actionPayload: string, recommendedMenu: string}
     */
    public function chat(string $query, string $context = 'order', ?string $tableNumber = null): array
    {
        $cleanQuery = trim($query);
        if ($cleanQuery === '') {
            return [
                'text' => 'Halo kak! Mau rekomendasi kopi segar, manis creamy, atau cemilan lezat hari ini?',
                'showCta' => false,
                'ctaText' => '',
                'actionType' => '',
                'actionPayload' => '',
                'recommendedMenu' => '',
            ];
        }

        // 1. Prepare Live Menu Context from DB (Full Catalog with Taste Tags & Description)
        $menus = Menu::with('category')->where('is_available', true)->get();
        $menuList = $menus->map(function ($m) {
            $cat = $m->category?->name ?? 'Menu';
            $price = number_format((float) $m->price, 0, ',', '.');
            $tags = !empty($m->taste_tags) ? implode(', ', $m->taste_tags) : '-';
            $desc = $m->description ? " | Deskripsi: {$m->description}" : '';
            return "- {$m->name} [Kategori: {$cat} | Harga: Rp {$price} | Profil Rasa: {$tags}{$desc}]";
        })->implode("\n");

        $aiConfig = Setting::get('ai_config');
        $basePersona = (is_array($aiConfig) && !empty($aiConfig['systemPrompt']))
            ? $aiConfig['systemPrompt']
            : 'Kamu adalah AI Barista pintar, ramah, dan solutif di kafe NONGKRONG.id.';

        $toneInstruction = match (is_array($aiConfig) ? ($aiConfig['tonePersona'] ?? 'friendly') : 'friendly') {
            'efficient' => 'Gaya bahasa to-the-point, ringkas, dan langsung pada rekomendasi tanpa basa-basi.',
            'trendy' => 'Gaya bahasa kasual santai khas anak muda/Gen-Z, hangat, ramah dan kekinian.',
            default => 'Gaya bahasa santun, bersahabat, ringkas, dan persuasif khas barista (1-2 paragraf pendek).',
        };

        $upsellInstruction = match (is_array($aiConfig) ? ($aiConfig['upsellStrategy'] ?? 'balanced') : 'balanced') {
            'soft' => 'Rekomendasikan menu pendamping secara halus jika pelanggan bertanya saran tambahan.',
            'proactive' => 'Wajib tawarkan 1 menu pendamping atau cemilan secara proaktif di akhir rekomendasi.',
            default => 'Tawarkan rekomendasi menu utama yang pas, dan sertakan saran pelengkap bila relevan.',
        };

        $temperature = (is_array($aiConfig) && isset($aiConfig['temperature']))
            ? (float) $aiConfig['temperature']
            : 0.6;

        $systemPrompt = <<<PROMPT
{$basePersona}
Lokasi pelanggan: Meja {$tableNumber} (Konteks halaman: {$context}).

INSTRUKSI GAYA & STRATEGI:
- {$toneInstruction}
- {$upsellInstruction}

KATALOG MENU RESMI DARI DATABASE:
{$menuList}

TUGAS & CARA KERJA:
1. Evaluasi dan bandingkan seluruh menu di atas berdasarkan permintaan pelanggan (kata kunci rasa, bahan, kafein/non-kafein, dingin/panas, makanan/minuman, maupun budget).
2. Tentukan 1 menu yang paling cocok dari hasil perbandingan seluruh katalog. Berikan alasan singkat mengapa menu tersebut paling pas untuk seleranya.
3. Sebutkan nama menu secara persis dan harga resminya (Rp).
4. WAJIB BALAS HANYA DALAM FORMAT JSON VALID tanpa markdown pembungkus (tanpa ```json):
{
  "text": "Teks rekomendasi lengkap yang membandingkan dan menjelaskan kenapa menu ini paling cocok...",
  "showCta": true,
  "ctaText": "Pesan <Nama Menu Persis>",
  "actionType": "order",
  "actionPayload": "<Nama Menu Persis>",
  "recommendedMenu": "<Nama Menu Persis>"
}
PROMPT;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . ($this->apiKey ?: 'dummy-token'),
                'Content-Type' => 'application/json',
            ])->timeout(15)->post("{$this->baseUrl}/chat/completions", [
                'model' => $this->defaultModel,
                'stream' => false,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $cleanQuery],
                ],
                'temperature' => $temperature,
                'max_tokens' => 450,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;

                if ($content) {
                    $parsed = $this->parseJsonResponse($content, $menus);
                    if ($parsed) {
                        return $parsed;
                    }
                }
            }
        } catch (Exception $e) {
            Log::warning('9Router AI Barista failed, falling back to smart catalog search: ' . $e->getMessage());
        }

        // Fallback: Smart Catalog-based Recommendation
        return $this->smartCatalogFallback($cleanQuery, $menus);
    }

    /**
     * Parse json content cleanly even if enclosed in markdown blocks
     */
    protected function parseJsonResponse(string $raw, $menus = null): ?array
    {
        $clean = trim($raw);
        if (str_starts_with($clean, '```json')) {
            $clean = substr($clean, 7);
        } elseif (str_starts_with($clean, '```')) {
            $clean = substr($clean, 3);
        }
        if (str_ends_with($clean, '```')) {
            $clean = substr($clean, 0, -3);
        }
        $clean = trim($clean);

        $decoded = json_decode($clean, true);
        if (is_array($decoded) && isset($decoded['text'])) {
            $recommended = trim((string) ($decoded['recommendedMenu'] ?? ''));
            $recommended = trim($recommended, "*\"' ");
            $menuId = null;

            if ($menus) {
                if ($recommended !== '') {
                    $matched = $menus->first(function ($m) use ($recommended) {
                        return strcasecmp($m->name, $recommended) === 0
                            || stripos($m->name, $recommended) !== false
                            || stripos($recommended, $m->name) !== false;
                    });
                    $menuId = $matched?->id;
                    if ($matched) {
                        $recommended = $matched->name;
                    }
                }

                if (! $menuId) {
                    $payload = trim((string) ($decoded['actionPayload'] ?? ''));
                    $matched = $menus->first(function ($m) use ($payload, $decoded) {
                        return ($payload && (stripos($m->name, $payload) !== false || stripos($payload, $m->name) !== false))
                            || stripos($decoded['text'] ?? '', $m->name) !== false;
                    });
                    $menuId = $matched?->id;
                    if ($matched && empty($recommended)) {
                        $recommended = $matched->name;
                    }
                }
            }

            return [
                'text' => (string) $decoded['text'],
                'showCta' => (bool) ($decoded['showCta'] ?? true),
                'ctaText' => (string) ($decoded['ctaText'] ?? 'Pesan Menu Ini'),
                'actionType' => (string) ($decoded['actionType'] ?? 'order'),
                'actionPayload' => (string) ($decoded['actionPayload'] ?? $recommended),
                'recommendedMenu' => $recommended,
                'menuId' => $menuId,
            ];
        }

        // If returned plain string without JSON
        if (strlen($clean) > 10) {
            return [
                'text' => $clean,
                'showCta' => true,
                'ctaText' => 'Lihat Menu',
                'actionType' => 'order',
                'actionPayload' => '',
                'recommendedMenu' => '',
                'menuId' => null,
            ];
        }

        return null;
    }

    /**
     * Smart catalog fallback based on live DB when network/LLM is unreachable
     */
    protected function smartCatalogFallback(string $query, $menus): array
    {
        $q = strtolower($query);

        // Segar / Non-kopi / Buah
        if (str_contains($q, 'seger') || str_contains($q, 'segar') || str_contains($q, 'dingin') || str_contains($q, 'buah') || str_contains($q, 'tea') || str_contains($q, 'teh') || str_contains($q, 'soda') || str_contains($q, 'sparkler') || str_contains($q, 'yakult') || str_contains($q, 'jus')) {
            $item = $menus->first(fn($m) => str_contains(strtolower($m->name), 'sparkler') || str_contains(strtolower($m->name), 'yakult') || str_contains(strtolower($m->name), 'tea'));
            $item = $item ?: $menus->first(fn($m) => $m->category_id == 4);

            $name = $item?->name ?? 'Lychee Yakult Blossom';
            $price = $item ? number_format((float) $item->price, 0, ',', '.') : '24.000';

            return [
                'text' => "Buat yang cari kesegaran maksimal, rekomendasi terbaikku adalah **{$name}** (Rp {$price})! Sensasi buah dingin yang manis asam menyegarkan tenggorokan. Cocok banget dinikmati saat ini!",
                'showCta' => true,
                'ctaText' => "Pesan {$name}",
                'actionType' => 'order',
                'actionPayload' => $name,
                'recommendedMenu' => $name,
                'menuId' => $item?->id,
            ];
        }

        // Kopi Manis & Creamy
        if (str_contains($q, 'manis') || str_contains($q, 'creamy') || str_contains($q, 'susu') || str_contains($q, 'aren') || str_contains($q, 'caramel') || str_contains($q, 'vanilla') || str_contains($q, 'latte')) {
            $item = $menus->first(fn($m) => str_contains(strtolower($m->name), 'aren') || str_contains(strtolower($m->name), 'caramel'));
            $name = $item?->name ?? 'Kopi Susu Gula Aren Senja';
            $price = $item ? number_format((float) $item->price, 0, ',', '.') : '22.000';

            return [
                'text' => "Pilihan pas banget kak! Rekomendasi juaraku ada **{$name}** (Rp {$price}). Rasa espresso lembut berpadu gurihnya susu dan manis harum gula aren.",
                'showCta' => true,
                'ctaText' => "Pesan {$name}",
                'actionType' => 'order',
                'actionPayload' => $name,
                'recommendedMenu' => $name,
                'menuId' => $item?->id,
            ];
        }

        // Strong / Pahit / Melek
        if (str_contains($q, 'strong') || str_contains($q, 'pahit') || str_contains($q, 'pait') || str_contains($q, 'ngantuk') || str_contains($q, 'melek') || str_contains($q, 'hitam') || str_contains($q, 'black') || str_contains($q, 'bitter') || str_contains($q, 'americano') || str_contains($q, 'espresso')) {
            $item = $menus->first(fn($m) => str_contains(strtolower($m->name), 'americano') || str_contains(strtolower($m->name), 'espresso'));
            $name = $item?->name ?? 'Caffe Americano Double Shot';
            $price = $item ? number_format((float) $item->price, 0, ',', '.') : '18.000';

            return [
                'text' => "Butuh booster fokus biar melek? Langsung pesan **{$name}** (Rp {$price})! Ekstraksi ganda dengan aroma kaya dan clean finish.",
                'showCta' => true,
                'ctaText' => "Pesan {$name}",
                'actionType' => 'order',
                'actionPayload' => $name,
                'recommendedMenu' => $name,
                'menuId' => $item?->id,
            ];
        }

        // Cemilan / Makanan
        if (str_contains($q, 'makan') || str_contains($q, 'lapar') || str_contains($q, 'laper') || str_contains($q, 'snack') || str_contains($q, 'cemil') || str_contains($q, 'fries') || str_contains($q, 'croissant') || str_contains($q, 'kentang') || str_contains($q, 'roti')) {
            $item = $menus->first(fn($m) => str_contains(strtolower($m->name), 'fries') || str_contains(strtolower($m->name), 'croissant') || str_contains(strtolower($m->name), 'goreng'));
            $name = $item?->name ?? 'Truffle Parmesan Fries';
            $price = $item ? number_format((float) $item->price, 0, ',', '.') : '25.000';

            return [
                'text' => "Buat nemenin nongkrong, enaknya sambil ngemil **{$name}** (Rp {$price})! Porsinya pas dan gurih lezat.",
                'showCta' => true,
                'ctaText' => "Pesan {$name}",
                'actionType' => 'order',
                'actionPayload' => $name,
                'recommendedMenu' => $name,
                'menuId' => $item?->id,
            ];
        }

        // Direct search matching query tokens against name, taste tags, or description
        $tokens = array_filter(explode(' ', preg_replace('/[^a-z0-9 ]/', '', $q)));
        foreach ($tokens as $token) {
            if (strlen($token) < 3) continue;
            $matched = $menus->first(function ($m) use ($token) {
                if (str_contains(strtolower($m->name), $token)) return true;
                if ($m->description && str_contains(strtolower($m->description), $token)) return true;
                foreach ($m->taste_tags as $t) {
                    if (str_contains(strtolower($t), $token)) return true;
                }
                return false;
            });
            if ($matched) {
                $name = $matched->name;
                $price = number_format((float) $matched->price, 0, ',', '.');
                return [
                    'text' => "Dari hasil pencocokan selera kamu, **{$name}** (Rp {$price}) jadi pilihan yang paling pas! Mau coba pesan?",
                    'showCta' => true,
                    'ctaText' => "Pesan {$name}",
                    'actionType' => 'order',
                    'actionPayload' => $name,
                    'recommendedMenu' => $name,
                    'menuId' => $matched->id,
                ];
            }
        }

        // Default: Varied recommendation instead of static first item
        $item = $menus->where('is_recommended', true)->first() ?? $menus->random() ?? $menus->first();
        $name = $item?->name ?? 'Signature Drink';
        $price = $item ? number_format((float) $item->price, 0, ',', '.') : '22.000';

        return [
            'text' => "Halo kak! Di kafe NONGKRONG.id salah satu menu favorit barista adalah **{$name}** (Rp {$price}). Mau coba pesan menu ini?",
            'showCta' => true,
            'ctaText' => "Pesan {$name}",
            'actionType' => 'order',
            'actionPayload' => $name,
            'recommendedMenu' => $name,
            'menuId' => $item?->id,
        ];
    }
}
