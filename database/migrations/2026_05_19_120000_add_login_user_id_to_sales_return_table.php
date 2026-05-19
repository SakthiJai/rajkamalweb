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
        Schema::table('sales_return', function (Blueprint $table) {
            $table->unsignedBigInteger('login_user_id')->nullable()->after('return_by');
        });

        DB::statement('UPDATE sales_return SET login_user_id = return_by WHERE login_user_id IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_return', function (Blueprint $table) {
            $table->dropColumn('login_user_id');
        });
    }
};
