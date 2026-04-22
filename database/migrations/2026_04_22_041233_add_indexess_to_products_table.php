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
               Schema::table('products', function (Blueprint $table) {
            $table->index(['company_id', 'is_delete'], 'idx_company_delete');

            $table->index(['company_id', 'is_delete', 'id'], 'idx_company_delete_id');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
              $table->dropIndex('idx_company_delete');
            $table->dropIndex('idx_company_delete_id');
        });
    }
};
