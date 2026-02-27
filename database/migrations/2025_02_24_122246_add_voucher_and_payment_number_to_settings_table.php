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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('setting_type', 50)->change(); // Ensure VARCHAR(50)
        });

        DB::table('settings')->insert([
            [
                'is_global' => 0,
                'company_id' => null,
                'setting_type' => 'voucher_number',
                'name' => '',
                'name_key' => '',
                'credentials' => null,
                'other_data' => null,
                'status' => 0,
                'verified' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'recent_bill_number' => 1
            ],
            [
                'is_global' => 0,
                'company_id' => null,
                'setting_type' => 'payment_voucher',
                'name' => '',
                'name_key' => '',
                'credentials' => null,
                'other_data' => null,
                'status' => 0,
                'verified' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'recent_bill_number' => 1
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('setting_type', ['voucher_number', 'payment_number'])->delete();
    }
};
