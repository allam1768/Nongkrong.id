<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiBaristaChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_barista_chat_endpoint_validates_input(): void
    {
        $response = $this->postJson('/api/ai/barista-chat', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    public function test_ai_barista_chat_returns_recommendation_with_live_menu(): void
    {
        $category = Category::create([
            'name' => 'Artisan Tea & Refreshers',
            'slug' => 'tea-refreshers',
            'icon' => 'glass-water',
            'is_active' => true,
        ]);

        Menu::create([
            'category_id' => $category->id,
            'name' => 'Berry Citrus Sparkler Soda',
            'slug' => 'berry-citrus-sparkler-soda',
            'price' => 23000,
            'is_available' => true,
        ]);

        $response = $this->postJson('/api/ai/barista-chat', [
            'message' => 'saran minuman yang seger',
            'context' => 'order',
            'table_number' => '05',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'text',
                'showCta',
                'ctaText',
                'actionType',
                'actionPayload',
                'recommendedMenu',
            ]);

        $data = $response->json();
        $this->assertTrue($data['showCta']);
        $this->assertNotEmpty($data['text']);
    }

    public function test_ai_barista_chat_handles_slang_bitter_query(): void
    {
        $category = Category::create([
            'name' => 'Signature Coffee',
            'slug' => 'signature-coffee',
            'icon' => 'coffee',
            'is_active' => true,
        ]);

        Menu::create([
            'category_id' => $category->id,
            'name' => 'Caffe Americano Double Shot',
            'slug' => 'caffe-americano-double-shot',
            'price' => 18000,
            'is_available' => true,
        ]);

        $response = $this->postJson('/api/ai/barista-chat', [
            'message' => 'mau yang pait pait ddong',
            'context' => 'order',
            'table_number' => '02',
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['showCta']);
        $this->assertStringContainsString('Americano', $data['recommendedMenu']);
    }
}
