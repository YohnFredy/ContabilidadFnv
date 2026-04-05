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
            $table->dropColumn('category');
            $table->foreignId('accounting_rule_category_id')->nullable()->after('name')->constrained('accounting_rule_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_rules', function (Blueprint $table) {
            $table->dropForeign(['accounting_rule_category_id']);
            $table->dropColumn('accounting_rule_category_id');
            $table->string('category')->nullable()->after('name');
        });
    }
};
