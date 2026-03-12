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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('unit', 20)->default('UND');
            $table->string('category', 100)->nullable();
            $table->decimal('min_stock', 15, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->decimal('current_stock', 15, 4)->default(0);
            $table->decimal('current_avg_cost', 20, 6)->default(0);
            $table->timestamps();

            $table->index(['code']);
            $table->index(['category']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
