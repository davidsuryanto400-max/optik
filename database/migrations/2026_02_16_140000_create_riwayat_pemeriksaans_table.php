<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksis')->onDelete('set null');
            $table->string('sph_r')->nullable();
            $table->string('cyl_r')->nullable();
            $table->string('sph_l')->nullable();
            $table->string('cyl_l')->nullable();
            $table->string('pd')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pemeriksaans');
    }
};
