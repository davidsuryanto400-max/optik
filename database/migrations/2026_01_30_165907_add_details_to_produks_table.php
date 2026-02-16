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
        Schema::table('produks', function (Blueprint $table) {
            $table->string('merek')->nullable()->after('nama');
            $table->string('warna')->nullable()->after('kategori_id');
            $table->foreignId('gudang_id')->nullable()->constrained('gudangs')->onDelete('set null')->after('warna');
            $table->integer('stok_minimum')->default(0)->after('stok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropForeign(['gudang_id']);
            $table->dropColumn(['merek', 'warna', 'gudang_id', 'stok_minimum']);
        });
    }
};
