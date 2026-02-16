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
            $table->date('last_exam_date')->nullable()->after('tgl_lahir');
            $table->decimal('sph_r', 5, 2)->nullable()->after('last_exam_date');
            $table->decimal('cyl_r', 5, 2)->nullable()->after('sph_r');
            $table->decimal('sph_l', 5, 2)->nullable()->after('cyl_r');
            $table->decimal('cyl_l', 5, 2)->nullable()->after('sph_l');
            $table->string('pd')->nullable()->after('cyl_l');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            $table->dropColumn(['last_exam_date', 'sph_r', 'cyl_r', 'sph_l', 'cyl_l', 'pd']);
        });
    }
};
