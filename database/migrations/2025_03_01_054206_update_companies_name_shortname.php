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
        DB::table('companies')->update([
            'name' => 'DigitalCloudies',
            'short_name' => 'DigitalCloudies'
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('companies')->update([
            'name' => 'Stockifly',
            'short_name' => 'Stockifly'
        ]);

    }
};
