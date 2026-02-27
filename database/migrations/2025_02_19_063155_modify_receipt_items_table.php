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
        Schema::table('receipt_items', function (Blueprint $table) {
            // Drop all columns except `id` and `receipt_mode`
            $columnsToRemove = [
                'party_name',
                'party_full_name',
                'station',
                'receipt_date',
                'amount',
                'short_amount',
                'order_id'
            ];

            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('receipt_items', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Ensure `receipt_mode` exists
            if (!Schema::hasColumn('receipt_items', 'receipt_mode')) {
                $table->string('receipt_mode')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_items', function (Blueprint $table) {
            // Remove only the 'receipt_mode' column if it exists
            if (Schema::hasColumn('receipt_items', 'receipt_mode')) {
                $table->dropColumn('receipt_mode');
            }
        });
    }
};
