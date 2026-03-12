<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropColumn([
                'stock_before',
                'stock_after',
                'avg_cost_before',
                'avg_cost_after',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->decimal('stock_before', 15, 4)->after('total_cost');
            $table->decimal('stock_after', 15, 4)->after('stock_before');
            $table->decimal('avg_cost_before', 20, 6)->after('stock_after');
            $table->decimal('avg_cost_after', 20, 6)->after('avg_cost_before');
        });
    }
};
