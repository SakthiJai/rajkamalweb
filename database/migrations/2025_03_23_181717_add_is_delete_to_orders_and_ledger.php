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
        {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'is_delete')) {
                    $table->tinyInteger('is_delete')->default(0);
                }
            });

            Schema::table('ledger', function (Blueprint $table) {
                if (!Schema::hasColumn('ledger', 'is_delete')) {
                    $table->tinyInteger('is_delete')->default(0);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'is_delete')) {
                $table->dropColumn('is_delete');
            }
        });

        Schema::table('ledger', function (Blueprint $table) {
            if (Schema::hasColumn('ledger', 'is_delete')) {
                $table->dropColumn('is_delete');
            }
        });

    }
};
