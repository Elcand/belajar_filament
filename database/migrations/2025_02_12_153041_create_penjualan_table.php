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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_faktur');
            $table->date('tanggal_faktur');
            $table->bigInteger('jumlah');
            $table->foreignId('customer_id')->constrained('customer', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('faktur_id')->constrained('faktur', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
