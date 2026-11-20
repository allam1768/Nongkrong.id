<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE tables MODIFY status ENUM('available', 'occupied', 'reserved', 'dirty') NOT NULL DEFAULT 'available'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::table('tables')->where('status', 'dirty')->update(['status' => 'available']);
            DB::statement("ALTER TABLE tables MODIFY status ENUM('available', 'occupied', 'reserved') NOT NULL DEFAULT 'available'");
        }
    }
};
