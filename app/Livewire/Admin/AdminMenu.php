<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\HasLogout;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kelola Menu & Rasa - Admin NONGKRONG.id')]
class AdminMenu extends Component
{
    use HasLogout;

    public string $search = '';

    public int $selectedCategoryId = 0;

    public bool $showCreateModal = false;

    // Form attributes
    public string $name = '';

    public int $categoryId = 1;

    public string $description = '';

    public float $price = 20000;

    public string $tasteProfile = 'Nutty, Bold';

    public string $sweetnessLevel = 'medium';

    public string $caffeineLevel = 'medium';

    public bool $isRecommended = false;

    public ?string $toastMessage = null;

    public function toggleAvailability(int $menuId): void
    {
        $menu = Menu::find($menuId);
        if ($menu) {
            $menu->update([
                'is_available' => ! $menu->is_available,
            ]);
            $status = $menu->is_available ? 'Tersedia' : 'Habis';
            $this->triggerToast("Status {$menu->name} diubah menjadi {$status}!");
        }
    }

    public function openCreateModal(): void
    {
        $this->reset(['name', 'description', 'price', 'tasteProfile']);
        $firstCategory = Category::first();
        if ($firstCategory) {
            $this->categoryId = $firstCategory->id;
        }
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function saveMenu(): void
    {
        $this->validate([
            'name' => 'required|min:3|max:100',
            'categoryId' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1000',
            'description' => 'nullable|string',
        ]);

        Menu::create([
            'category_id' => $this->categoryId,
            'name' => $this->name,
            'slug' => Str::slug($this->name).'-'.rand(100, 999),
            'description' => $this->description,
            'price' => $this->price,
            'is_available' => true,
            'taste_profile' => $this->tasteProfile,
            'sweetness_level' => $this->sweetnessLevel,
            'caffeine_level' => $this->caffeineLevel,
            'is_recommended' => $this->isRecommended,
        ]);

        $this->showCreateModal = false;
        $this->triggerToast("Menu {$this->name} berhasil ditambahkan!");
    }

    public function deleteMenu(int $menuId): void
    {
        $menu = Menu::withCount('orderItems')->find($menuId);
        if (! $menu) {
            return;
        }

        $name = $menu->name;

        if ($menu->order_items_count > 0) {
            $menu->update(['is_available' => false]);
            $this->triggerToast("Menu '{$name}' memiliki riwayat pesanan sehingga tidak dapat dihapus fisik. Statusnya telah otomatis dinonaktifkan (Habis).");

            return;
        }

        $menu->delete();
        $this->triggerToast("Menu '{$name}' berhasil dihapus.");
    }

    protected function triggerToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->dispatch('toast-notify');
    }

    public function render()
    {
        $categories = Category::all();

        $query = Menu::with('category')->latest();

        if ($this->selectedCategoryId > 0) {
            $query->where('category_id', $this->selectedCategoryId);
        }

        if (trim($this->search) !== '') {
            $query->where('name', 'like', '%'.trim($this->search).'%');
        }

        $menus = $query->paginate(15);

        return view('livewire.admin.AdminMenu', [
            'categories' => $categories,
            'menus' => $menus,
        ]);
    }
}
