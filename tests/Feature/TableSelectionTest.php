<?php

namespace Tests\Feature;

use App\Livewire\User\TableSelection;
use App\Models\Table;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TableSelectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test table selection page loads with required visual elements and assets.
     */
    public function test_table_selection_page_loads_with_required_elements(): void
    {
        $response = $this->withSession(['customer_name' => 'Budi'])
            ->get('/table');

        $response->assertStatus(200);
        $response->assertSee('Enter your table code !');
        $response->assertSee('OR');
        $response->assertSee('Confirm');
        $response->assertSee('assets/img/Star-ic.svg');
        $response->assertSee('assets/img/NongkrongId-blue.svg');
        $response->assertSee('Budi');
        $response->assertDontSee('Pilih Meja Cepat');
        $this->assertFileExists(public_path('assets/img/Star-ic.svg'));
        $this->assertFileExists(public_path('assets/img/NongkrongId-blue.svg'));
    }

    /**
     * Test table selection Livewire component initializes customer name from session.
     */
    public function test_table_selection_initializes_customer_name_from_session(): void
    {
        session(['customer_name' => 'Siti Rahma']);

        Livewire::test(TableSelection::class)
            ->assertSet('customerName', 'Siti Rahma')
            ->assertSee('Siti Rahma');
    }

    /**
     * Test table selection validates empty table code.
     */
    public function test_table_selection_validates_required_table_code(): void
    {
        Livewire::test(TableSelection::class)
            ->set('tableCode', '')
            ->set('codeDigits', ['', '', '', '', ''])
            ->call('confirmTableCode')
            ->assertHasErrors(['tableCode' => 'required']);
    }

    /**
     * Test table selection shows error for invalid code.
     */
    public function test_table_selection_shows_error_for_nonexistent_table(): void
    {
        Livewire::test(TableSelection::class)
            ->set('tableCode', '99999')
            ->call('confirmTableCode')
            ->assertHasErrors(['tableCode'])
            ->assertSet('showSuccessModal', false);
    }

    /**
     * Test table selection shows error when table is occupied.
     */
    public function test_table_selection_rejects_occupied_table(): void
    {
        // Table 02 is seeded as occupied
        Livewire::test(TableSelection::class)
            ->set('tableCode', '00002')
            ->call('confirmTableCode')
            ->assertHasErrors(['tableCode'])
            ->assertSee('sedang terisi');
    }

    /**
     * Test table selection successfully confirms available table via 5-digit code.
     */
    public function test_table_selection_confirms_available_table(): void
    {
        // Table 01 is available
        Livewire::test(TableSelection::class)
            ->set('tableCode', '00001')
            ->call('confirmTableCode')
            ->assertHasNoErrors()
            ->assertSet('showSuccessModal', true)
            ->assertSet('selectedTableNumber', '01')
            ->assertSessionHas('table_number', '01');
    }

    /**
     * Test table selection supports QR code token scanning.
     */
    public function test_table_selection_confirms_table_via_qr_token(): void
    {
        $table = Table::where('table_number', '04')->first();

        Livewire::test(TableSelection::class)
            ->call('scanQrTable', $table->qr_code_token)
            ->assertHasNoErrors()
            ->assertSet('showSuccessModal', true)
            ->assertSet('selectedTableNumber', '04')
            ->assertSessionHas('table_number', '04');
    }

    /**
     * Test quick fill table shortcut works.
     */
    public function test_table_selection_quick_fill_works(): void
    {
        Livewire::test(TableSelection::class)
            ->call('quickFillTable', '05')
            ->assertHasNoErrors()
            ->assertSet('showSuccessModal', true)
            ->assertSet('selectedTableNumber', '05')
            ->assertSessionHas('table_number', '05');
    }

    /**
     * Test table selection successfully confirms available table via access_code.
     */
    public function test_table_selection_confirms_available_table_via_access_code(): void
    {
        // Table 01 has access_code '7K92A'
        Livewire::test(TableSelection::class)
            ->set('tableCode', '7K92A')
            ->call('confirmTableCode')
            ->assertHasNoErrors()
            ->assertSet('showSuccessModal', true)
            ->assertSet('selectedTableNumber', '01')
            ->assertSet('selectedTableAccessCode', '7K92A')
            ->assertSessionHas('table_number', '01')
            ->assertSessionHas('access_code', '7K92A');

        // Case-insensitive test ('7k92a')
        Livewire::test(TableSelection::class)
            ->set('tableCode', '7k92a')
            ->call('confirmTableCode')
            ->assertHasNoErrors()
            ->assertSet('showSuccessModal', true)
            ->assertSet('selectedTableNumber', '01');
    }

    /**
     * Test table selection rejects occupied table via access_code.
     */
    public function test_table_selection_rejects_occupied_table_via_access_code(): void
    {
        // Table 02 has access_code '3X81B' and is occupied
        Livewire::test(TableSelection::class)
            ->set('tableCode', '3X81B')
            ->call('confirmTableCode')
            ->assertHasErrors(['tableCode'])
            ->assertSee('sedang terisi');
    }

    /**
     * Test change name redirects to portal.
     */
    public function test_change_name_redirects_to_portal(): void
    {
        Livewire::test(TableSelection::class)
            ->call('changeName')
            ->assertRedirect(route('portal'));
    }

    /**
     * Test proceed to order validates customer name is required.
     */
    public function test_proceed_to_order_requires_customer_name(): void
    {
        Livewire::test(TableSelection::class)
            ->set('customerName', '')
            ->call('proceedToOrder')
            ->assertHasErrors(['customerName' => 'required']);
    }

    /**
     * Test proceed to order saves customer name and redirects to order page.
     */
    public function test_proceed_to_order_saves_name_and_redirects(): void
    {
        Livewire::test(TableSelection::class)
            ->set('tableCode', '00001')
            ->call('confirmTableCode')
            ->set('customerName', 'Rian Pratama')
            ->call('proceedToOrder')
            ->assertHasNoErrors()
            ->assertSessionHas('customer_name', 'Rian Pratama')
            ->assertRedirect(route('customer.order'));
    }
}
