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
        Schema::table('hoc_phi', function (Blueprint $table) {
            $table->string('ngay_thi')->default('13/08/2025');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hoc_phi', function (Blueprint $table) {
            $table->dropColumn('ngay_thi');
        });
    }
};
