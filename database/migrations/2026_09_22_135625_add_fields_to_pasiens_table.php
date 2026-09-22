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
        Schema::table('pasiens', function (Blueprint $table) {
            $table->string('no_resep')->nullable()->after('id');
            $table->string('resep')->nullable()->after('nama');
            $table->string('frame')->nullable()->after('resep');
            $table->string('lensa')->nullable()->after('frame');
        });
        
        Schema::table('riwayat_pemeriksaans', function (Blueprint $table) {
            $table->string('no_resep')->nullable()->after('id');
            $table->string('resep')->nullable()->after('pasien_id');
            $table->string('frame')->nullable()->after('resep');
            $table->string('lensa')->nullable()->after('frame');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            $table->dropColumn(['no_resep', 'resep', 'frame', 'lensa']);
        });
        
        Schema::table('riwayat_pemeriksaans', function (Blueprint $table) {
            $table->dropColumn(['no_resep', 'resep', 'frame', 'lensa']);
        });
    }
};
