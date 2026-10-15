<?php

namespace Tests\Feature;

use App\Livewire\Admin\AdminAiConfig;
use App\Livewire\Admin\AdminAnalytics;
use App\Livewire\Admin\AdminMenu;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Table $table;

    protected Category $category;

    protected Menu $menu;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Owner Cafe',
            'email' => 'admin@smartcafe.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->table = Table::create([
            'table_number' => '05',
            'qr_token' => 'qr-05-token',
            'access_code' => 'T05',
            'status' => 'available',
            'capacity' => 4,
        ]);

        $this->category = Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'icon_url' => '☕',
            'is_active' => true,
        ]);

        $this->menu = Menu::create([
            'category_id' => $this->category->id,
            'name' => 'Caramel Macchiato',
            'slug' => 'caramel-macchiato',
            'price' => 28000,
            'is_available' => true,
        ]);
    }

    public function test_unauthenticated_user_redirected_to_portal_from_admin_routes(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('portal'));
    }

    public function test_non_admin_forbidden_from_admin_routes(): void
    {
        $staff = User::create([
            'name' => 'Staf Biasa',
            'email' => 'staf@smartcafe.id',
            'password' => bcrypt('password123'),
            'role' => 'staff',
        ]);

        $response = $this->actingAs($staff)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_dashboard_renders_successfully(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Executive Dashboard');
    }

    public function test_admin_menus_renders_with_array_taste_profile_without_error(): void
    {
        Menu::create([
            'category_id' => $this->category->id,
            'name' => 'Kopi Susu Gula Aren Senja',
            'slug' => 'kopi-susu-senja-test',
            'price' => 22000,
            'is_available' => true,
            'taste_profile' => ['sweet', 'creamy', 'coffee'],
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.menus'));
        $response->assertStatus(200);
        $response->assertSee('Kopi Susu Gula Aren Senja');
        $response->assertSee('sweet');
        $response->assertSee('creamy');
    }

    public function test_admin_analytics_computes_kpis(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-ADM-01',
            'table_id' => $this->table->id,
            'customer_name' => 'Pelanggan VIP',
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'qris',
            'total_amount' => 30800,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'menu_id' => $this->menu->id,
            'quantity' => 1,
            'unit_price' => 28000,
            'subtotal' => 28000,
        ]);

        Livewire::actingAs($this->admin)
            ->test(AdminAnalytics::class)
            ->assertSee('Caramel Macchiato');
    }

    public function test_admin_menus_renders_and_toggles_availability(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.menus'));
        $response->assertStatus(200);

        Livewire::actingAs($this->admin)
            ->test(AdminMenu::class)
            ->call('toggleAvailability', $this->menu->id);

        $this->assertDatabaseHas('menus', [
            'id' => $this->menu->id,
            'is_available' => false,
        ]);
    }

    public function test_admin_menu_creation_works(): void
    {
        Livewire::actingAs($this->admin)
            ->test(AdminMenu::class)
            ->set('name', 'Matcha Latte Ice')
            ->set('categoryId', $this->category->id)
            ->set('price', 25000)
            ->set('description', 'Matcha Uji Jepang premium')
            ->call('saveMenu');

        $this->assertDatabaseHas('menus', [
            'name' => 'Matcha Latte Ice',
            'price' => 25000,
        ]);
    }

    public function test_admin_ai_config_renders_and_tests_simulation(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.ai-config'));
        $response->assertStatus(200);
        $response->assertSee('AI Barista Tuner');

        Livewire::actingAs($this->admin)
            ->test(AdminAiConfig::class)
            ->set('testInput', 'Halo barista, ada rekomendasi kopi?')
            ->call('sendTestPrompt')
            ->assertCount('chatMessages', 3); // initial welcome + user message + bot response
    }

    public function test_admin_can_physically_delete_menu_without_order_history(): void
    {
        $unusedMenu = Menu::create([
            'category_id' => $this->category->id,
            'name' => 'Menu Baru Uji Coba',
            'slug' => 'menu-baru-uji-coba',
            'price' => 15000,
            'is_available' => true,
        ]);

        Livewire::actingAs($this->admin)
            ->test(AdminMenu::class)
            ->call('deleteMenu', $unusedMenu->id)
            ->assertDispatched('toast-notify');

        $this->assertDatabaseMissing('menus', [
            'id' => $unusedMenu->id,
        ]);
    }

    public function test_admin_delete_menu_with_order_history_deactivates_menu_without_crashing(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-ADMIN-HIST',
            'table_id' => $this->table->id,
            'customer_name' => 'Pak Budi',
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'total_amount' => 28000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'menu_id' => $this->menu->id,
            'quantity' => 1,
            'unit_price' => 28000,
            'subtotal' => 28000,
        ]);

        Livewire::actingAs($this->admin)
            ->test(AdminMenu::class)
            ->call('deleteMenu', $this->menu->id)
            ->assertDispatched('toast-notify');

        // Menu should still exist in database due to foreign key integrity, but is_available set to false
        $this->assertDatabaseHas('menus', [
            'id' => $this->menu->id,
            'is_available' => false,
        ]);
    }
}
