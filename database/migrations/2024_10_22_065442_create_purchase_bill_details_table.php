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
        Schema::create('purchase_bill_details', function (Blueprint $table) {
            $table->id();
            $table->string('party_name');
            $table->date('order_date');
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('show_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_bill_details');
    }
};
