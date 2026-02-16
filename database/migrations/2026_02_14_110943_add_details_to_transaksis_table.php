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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('nota_manual')->nullable()->after('no_transaksi');
            $table->string('payment_method')->nullable()->after('status');
            $table->decimal('subtotal', 15, 2)->default(0)->after('payment_method');
            $table->decimal('diskon', 15, 2)->default(0)->after('subtotal');
            $table->decimal('pajak', 15, 2)->default(0)->after('diskon');
            $table->decimal('bayar', 15, 2)->default(0)->after('total_harga');
            $table->decimal('kembalian', 15, 2)->default(0)->after('bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['nota_manual', 'payment_method', 'subtotal', 'diskon', 'pajak', 'bayar', 'kembalian']);
        });
    }
};
