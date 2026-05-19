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
        Schema::table('payment_mode', function (Blueprint $table) {
            $table->unsignedBigInteger('login_user_id')->nullable()->after('settlement_id');
        });

        DB::statement("
            UPDATE payment_mode pm
            INNER JOIN orders o ON o.id = pm.order_id
            SET pm.login_user_id = o.user_id
            WHERE pm.login_user_id IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_mode', function (Blueprint $table) {
            $table->dropColumn('login_user_id');
        });
    }
};
