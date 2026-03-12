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
        Schema::create('accounting_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->foreignId('nomenclature_id_1')->nullable()->constrained('nomenclatures')->nullOnDelete();
            $table->string('nature_1')->nullable(); // 'Débito' or 'Crédito'

            $table->foreignId('nomenclature_id_2')->nullable()->constrained('nomenclatures')->nullOnDelete();
            $table->string('nature_2')->nullable();

            $table->foreignId('nomenclature_id_3')->nullable()->constrained('nomenclatures')->nullOnDelete();
            $table->string('nature_3')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_rules');
    }
};
