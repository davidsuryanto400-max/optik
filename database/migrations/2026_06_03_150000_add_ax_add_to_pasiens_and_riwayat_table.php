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
        Schema::table('pasiens', function (Blueprint $table) {
            $table->string('ax_r')->nullable()->after('cyl_r');
            $table->string('add_r')->nullable()->after('ax_r');
            $table->string('ax_l')->nullable()->after('cyl_l');
            $table->string('add_l')->nullable()->after('ax_l');
        });

        Schema::table('riwayat_pemeriksaans', function (Blueprint $table) {
            $table->string('ax_r')->nullable()->after('cyl_r');
            $table->string('add_r')->nullable()->after('ax_r');
            $table->string('ax_l')->nullable()->after('cyl_l');
            $table->string('add_l')->nullable()->after('ax_l');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            $table->dropColumn(['ax_r', 'add_r', 'ax_l', 'add_l']);
        });

        Schema::table('riwayat_pemeriksaans', function (Blueprint $table) {
            $table->dropColumn(['ax_r', 'add_r', 'ax_l', 'add_l']);
        });
    }
};
