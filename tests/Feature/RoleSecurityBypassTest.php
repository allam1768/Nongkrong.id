<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleSecurityBypassTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $cashier;
    protected User $kitchen;
    protected User $cleaner;
    protected Table $table;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Owner Admin',
            'email' => 'admin@smartcafe.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->cashier = User::create([
            'name' => 'Siti Kasir',
            'email' => 'kasir@smartcafe.id',
            'password' => bcrypt('password123'),
            'role' => 'cashier',
        ]);

        $this->kitchen = User::create([
            'name' => 'Budi Dapur',
            'email' => 'dapur@smartcafe.id',
            'password' => bcrypt('password123'),
            'role' => 'kitchen',
        ]);

        $this->cleaner = User::create([
            'name' => 'Joko Pembersih',
            'email' => 'cleaner@smartcafe.id',
            'password' => bcrypt('password123'),
            'role' => 'cleaner',
        ]);

        $this->table = Table::create([
            'table_number' => '01',
            'capacity' => 4,
            'status' => 'available',
            'qr_code_token' => 'QR-01-SEC',
            'access_code' => 'S01',
        ]);
    }

    public function test_guest_is_redirected_to_portal_from_all_protected_urls(): void
    {
        $protectedUrls = [
            route('staff.cashier-tables'),
            route('staff.cashier-register'),
            route('staff.kitchen-board'),
            route('staff.cleaning-board'),
            route('admin.dashboard'),
            route('admin.menus'),
            route('admin.tables'),
            route('admin.users'),
            route('admin.ai-config'),
        ];

        foreach ($protectedUrls as $url) {
            $response = $this->get($url);
            $response->assertRedirect(route('portal'));
        }
    }

    public function test_cashier_cannot_bypass_into_kitchen_cleaner_or_admin_urls(): void
    {
        $forbiddenUrls = [
            route('staff.kitchen-board'),
            route('staff.cleaning-board'),
            route('admin.dashboard'),
            route('admin.menus'),
            route('admin.tables'),
            route('admin.users'),
            route('admin.ai-config'),
        ];

        foreach ($forbiddenUrls as $url) {
            $response = $this->actingAs($this->cashier)->get($url);
            $response->assertStatus(403);
        }

        // Cashier can access cashier tables and cashier register
        $this->actingAs($this->cashier)->get(route('staff.cashier-tables'))->assertStatus(200);
        $this->actingAs($this->cashier)->get(route('staff.cashier-register'))->assertStatus(200);
    }

    public function test_kitchen_cannot_bypass_into_cashier_cleaner_or_admin_urls(): void
    {
        $forbiddenUrls = [
            route('staff.cashier-tables'),
            route('staff.cashier-register'),
            route('staff.cleaning-board'),
            route('admin.dashboard'),
            route('admin.menus'),
            route('admin.tables'),
            route('admin.users'),
            route('admin.ai-config'),
        ];

        foreach ($forbiddenUrls as $url) {
            $response = $this->actingAs($this->kitchen)->get($url);
            $response->assertStatus(403);
        }

        // Kitchen can only access kitchen board
        $allowedResponse = $this->actingAs($this->kitchen)->get(route('staff.kitchen-board'));
        $allowedResponse->assertStatus(200);
    }

    public function test_cleaner_cannot_bypass_into_cashier_kitchen_or_admin_urls(): void
    {
        $forbiddenUrls = [
            route('staff.cashier-tables'),
            route('staff.cashier-register'),
            route('staff.kitchen-board'),
            route('admin.dashboard'),
            route('admin.menus'),
            route('admin.tables'),
            route('admin.users'),
            route('admin.ai-config'),
        ];

        foreach ($forbiddenUrls as $url) {
            $response = $this->actingAs($this->cleaner)->get($url);
            $response->assertStatus(403);
        }

        // Cleaner can only access cleaning board
        $allowedResponse = $this->actingAs($this->cleaner)->get(route('staff.cleaning-board'));
        $allowedResponse->assertStatus(200);
    }

    public function test_admin_has_full_access_to_all_staff_and_admin_urls(): void
    {
        $allUrls = [
            route('staff.cashier-tables'),
            route('staff.cashier-register'),
            route('staff.kitchen-board'),
            route('staff.cleaning-board'),
            route('admin.dashboard'),
            route('admin.menus'),
            route('admin.tables'),
            route('admin.users'),
            route('admin.ai-config'),
        ];

        foreach ($allUrls as $url) {
            $response = $this->actingAs($this->admin)->get($url);
            $response->assertStatus(200);
        }
    }

    public function test_customer_cannot_bypass_order_page_without_table_session(): void
    {
        $response = $this->get(route('customer.order'));
        $response->assertRedirect(route('portal'));
    }

    public function test_customer_cannot_bypass_order_page_if_table_is_dirty(): void
    {
        $this->table->update(['status' => 'dirty']);

        $response = $this->withSession([
            'table_id' => $this->table->id,
            'table_number' => $this->table->table_number,
            'customer_name' => 'Tamu Usil',
        ])->get(route('customer.order'));

        $response->assertRedirect(route('portal'));
        $this->assertNull(session('table_id'));
    }
}
