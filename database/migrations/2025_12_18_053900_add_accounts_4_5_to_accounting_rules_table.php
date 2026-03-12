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
        Schema::table('accounting_rules', function (Blueprint $table) {
            $table->foreignId('nomenclature_id_4')->nullable()->after('nature_3')->constrained('nomenclatures')->nullOnDelete();
            $table->string('nature_4')->nullable()->after('nomenclature_id_4');

            $table->foreignId('nomenclature_id_5')->nullable()->after('nature_4')->constrained('nomenclatures')->nullOnDelete();
            $table->string('nature_5')->nullable()->after('nomenclature_id_5');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_rules', function (Blueprint $table) {
            $table->dropForeign(['nomenclature_id_4']);
            $table->dropColumn(['nomenclature_id_4', 'nature_4']);
            
            $table->dropForeign(['nomenclature_id_5']);
            $table->dropColumn(['nomenclature_id_5', 'nature_5']);
        });
    }
};
