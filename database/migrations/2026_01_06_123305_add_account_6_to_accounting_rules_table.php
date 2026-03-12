<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accounting_rules', function (Blueprint $table) {
            $table->foreignId('nomenclature_id_6')->nullable()->after('nature_5')->constrained('nomenclatures')->nullOnDelete();
            $table->string('nature_6')->nullable()->after('nomenclature_id_6');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_rules', function (Blueprint $table) {
            $table->dropForeign(['nomenclature_id_6']);
            $table->dropColumn(['nomenclature_id_6', 'nature_6']);
        });
    }
};
