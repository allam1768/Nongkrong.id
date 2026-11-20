<?php

namespace Tests\Feature;

use App\Livewire\Admin\AdminTable;
use App\Livewire\User\TableSelection;
use App\Models\Table;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminTableTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin NONGKRONG',
            'email' => 'admin@smartcafe.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
    }

    public function test_guest_cannot_access_admin_tables_page(): void
    {
        $response = $this->get('/admin/tables');
        $response->assertRedirect('/portal');
    }

    public function test_admin_can_access_admin_tables_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/tables');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Meja & Cetak QR');
    }

    public function test_admin_can_view_table_list_with_qr_and_access_codes(): void
    {
        Livewire::actingAs($this->admin)
            ->test(AdminTable::class)
            ->assertStatus(200)
            ->assertSee('Meja 01')
            ->assertSee('Meja 02')
            ->assertSee('Cetak Stiker QR');
    }

    public function test_admin_can_add_new_table_with_auto_generated_qr_token(): void
    {
        Livewire::actingAs($this->admin)
            ->test(AdminTable::class)
            ->call('openCreateModal')
            ->set('tableNumber', '07')
            ->set('capacity', 6)
            ->set('status', 'available')
            ->call('saveTable')
            ->assertHasNoErrors()
            ->assertSet('showFormModal', false);

        $this->assertDatabaseHas('tables', [
            'table_number' => '07',
            'capacity' => 6,
            'status' => 'available',
        ]);

        $created = Table::where('table_number', '07')->first();
        $this->assertNotNull($created);
        $this->assertNotEmpty($created->qr_code_token);
        $this->assertNotEmpty($created->access_code);
        $this->assertStringStartsWith('QR-TBL-07-', $created->qr_code_token);
        $this->assertEquals(5, strlen($created->access_code));
    }

    public function test_admin_can_toggle_table_status(): void
    {
        $table = Table::where('table_number', '01')->first();
        $this->assertEquals('available', $table->status);

        Livewire::actingAs($this->admin)
            ->test(AdminTable::class)
            ->call('toggleStatus', $table->id);

        $this->assertEquals('occupied', $table->fresh()->status);

        Livewire::actingAs($this->admin)
            ->test(AdminTable::class)
            ->call('toggleStatus', $table->id);

        $this->assertEquals('available', $table->fresh()->status);
    }

    public function test_admin_can_open_and_close_print_modal(): void
    {
        $table = Table::where('table_number', '01')->first();

        Livewire::actingAs($this->admin)
            ->test(AdminTable::class)
            ->call('openPrintModal', $table->id)
            ->assertSet('showPrintModal', true)
            ->assertSet('printTableNumber', '01')
            ->assertSet('printAccessCode', $table->access_code)
            ->call('closePrintModal')
            ->assertSet('showPrintModal', false);
    }

    public function test_admin_can_download_table_sticker_image(): void
    {
        $table = Table::where('table_number', '01')->first();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.tables.download-sticker', $table->id));

        $response->assertOk();
        $response->assertDownload("stiker-meja-01.png");
    }

    public function test_customer_can_auto_select_table_via_qr_query_parameter(): void
    {
        $table = Table::where('table_number', '01')->first();

        // Customer accesses /table?qr=QR-TBL-01-A9X1
        $component = Livewire::withQueryParams(['qr' => $table->qr_code_token])
            ->test(TableSelection::class);

        $component->assertSet('selectedTableId', $table->id)
            ->assertSet('selectedTableNumber', '01')
            ->assertSet('showSuccessModal', true);
    }
}
