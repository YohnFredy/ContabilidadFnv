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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['entrada', 'salida', 'ajuste_positivo', 'ajuste_negativo']);
            $table->decimal('quantity', 15, 4);
            $table->decimal('unit_cost', 20, 6);
            $table->decimal('total_cost', 20, 6);
            $table->decimal('stock_before', 15, 4);
            $table->decimal('stock_after', 15, 4);
            $table->decimal('avg_cost_before', 20, 6);
            $table->decimal('avg_cost_after', 20, 6);
            $table->string('reference', 100)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->dateTime('movement_date');
            $table->timestamps();

            $table->index(['product_id', 'movement_date']);
            $table->index(['type']);
            $table->index(['movement_date']);
            $table->index(['reference']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
