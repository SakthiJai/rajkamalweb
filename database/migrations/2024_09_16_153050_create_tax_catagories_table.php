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
        Schema::create('tax_catagories', function (Blueprint $table) {
            $table->id();
            $table->string('sales_type', 250); // Sales type with max length 250
            $table->string('local'); // Local string column
            $table->string('central'); // Central string column
            $table->bigInteger('lgst'); // LGST integer column
            $table->bigInteger('cgst'); // CGST integer column
            $table->bigInteger('sgst'); // SGST integer column
            $table->bigInteger('cess'); // Cess integer column
            $table->integer('nature_of_transaction'); // Nature of transaction integer column
            $table->integer('tax_ability'); // Taxability integer column
            $table->bigInteger('lgst_ledger'); // LGST ledger integer column
            $table->bigInteger('cgst_ledger'); // CGST ledger integer column
            $table->bigInteger('sgst_ledger'); // SGST ledger integer column
            $table->bigInteger('cess_ledger'); // Cess ledger integer column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_catagories');
    }
};
