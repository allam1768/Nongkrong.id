<?php

namespace App\Providers;

use App\Models\Table;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer for Admin Sidebar - extract DB logic away from blade
        View::composer('livewire.admin.partials.sidebar', function ($view) {
            $totalTables = Table::count();
            $occupiedCount = Table::where('status', 'occupied')->count();
            $occupancyPct = $totalTables > 0 ? (int) round(($occupiedCount / $totalTables) * 100) : 0;

            $view->with([
                'totalTables' => $totalTables,
                'occupiedCount' => $occupiedCount,
                'occupancyPct' => $occupancyPct,
            ]);
        });
    }
}
