<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('AI Barista Tuner - Admin NONGKRONG.id')]
class AdminAiConfig extends Component
{
    public string $model = 'gemini-1.5-flash';

    public string $systemPrompt = 'Kamu adalah Gemini Barista pintar di kafe NONGKRONG.id. Karaktermu ramah, asyik, dan paham cita rasa kopi. Tugasmu membantu pelanggan memilih minuman atau camilan terbaik sesuai mood, rasa manis, kafein, dan preferensi mereka. Rekomendasikan menu yang ada di katalog dengan menyebutkan harganya.';

    public float $temperature = 0.7;

    public string $tonePersona = 'friendly'; // friendly, efficient, trendy

    public string $upsellStrategy = 'balanced'; // soft, balanced, proactive

    public string $testInput = '';

    /**
     * @var array<int, array<string, string>>
     */
    public array $chatMessages = [];

    public ?string $toastMessage = null;

    public function mount(): void
    {
        $this->chatMessages = [
            [
                'role' => 'assistant',
                'message' => 'Halo Kak! Aku AI Barista NONGKRONG.id siap nemenin nongkrong kamu. Lagi pengen kopi yang strong atau yang manis creamy nih?',
            ],
        ];
    }

    public function saveConfig(): void
    {
        $this->triggerToast('Konfigurasi Gemini AI Barista berhasil disimpan!');
    }

    public function sendTestPrompt(): void
    {
        $query = trim($this->testInput);
        if ($query === '') {
            return;
        }

        $this->chatMessages[] = [
            'role' => 'user',
            'message' => $query,
        ];
        $this->testInput = '';

        // Generate dynamic contextual barista response based on tuning and menus
        $response = $this->generateSimulatedResponse($query);

        $this->chatMessages[] = [
            'role' => 'assistant',
            'message' => $response,
        ];
    }

    public function resetChat(): void
    {
        $this->chatMessages = [
            [
                'role' => 'assistant',
                'message' => 'Halo Kak! Aku AI Barista NONGKRONG.id. Mau rekomendasi kopi atau camilan apa hari ini?',
            ],
        ];
    }

    protected function generateSimulatedResponse(string $query): string
    {
        $lower = strtolower($query);

        if (str_contains($lower, 'ngantuk') || str_contains($lower, 'strong') || str_contains($lower, 'pahit')) {
            $menu = Menu::where('name', 'like', '%Espresso%')->orWhere('name', 'like', '%Americano%')->first();
            $name = $menu?->name ?? 'Double Shot Americano';
            $price = $menu ? number_format((float) $menu->price, 0, ',', '.') : '22.000';

            return "Wah kalau lagi butuh booster mata melek, rekomendasi terbaikku adalah **{$name}** (Rp {$price})! Ekstraksi espresso tebal dengan aroma nutty pekat, dijamin langsung fokus! Mau pesan sekarang?";
        }

        if (str_contains($lower, 'manis') || str_contains($lower, 'creamy') || str_contains($lower, 'susu')) {
            $menu = Menu::where('name', 'like', '%Susu%')->orWhere('name', 'like', '%Latte%')->first();
            $name = $menu?->name ?? 'Kopi Susu Gula Aren';
            $price = $menu ? number_format((float) $menu->price, 0, ',', '.') : '20.000';

            return "Cocok banget Kak! Cobain **{$name}** (Rp {$price}) andalan kami. Perpaduan espresso gurih dan gula aren organik manis lembut yang creamy di lidah. Pas banget nemenin santai sore!";
        }

        if (str_contains($lower, 'non kopi') || str_contains($lower, 'matcha') || str_contains($lower, 'cokelat') || str_contains($lower, 'segar')) {
            $menu = Menu::where('name', 'like', '%Matcha%')->orWhere('name', 'like', '%Chocolate%')->first();
            $name = $menu?->name ?? 'Matcha Latte Kyoto';
            $price = $menu ? number_format((float) $menu->price, 0, ',', '.') : '25.000';

            return "Tenang Kak, untuk non-kopi favorit kita ada **{$name}** (Rp {$price})! Rasa matcha murni yang autentik dan lembut. Segar dan nagih banget!";
        }

        // Default response
        return 'Pilihan mantap Kak! Rekomendasi teratas hari ini ada Signature Caramel Macchiato dan Croissant Butter hangat. Mau aku pesankan sekarang?';
    }

    protected function triggerToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->dispatch('toast-notify');
    }

    public function render()
    {
        return view('livewire.admin.AdminAiConfig');
    }
}
