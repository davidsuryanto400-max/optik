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
        Schema::table('gudangs', function (Blueprint $table) {
            $table->foreignId('cabang_id')->nullable()->constrained('cabangs')->onDelete('set null')->after('alamat');
            $table->boolean('is_active')->default(true)->after('cabang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudangs', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropColumn(['cabang_id', 'is_active']);
        });
    }
};
