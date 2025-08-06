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
        Schema::create('hoc_phi', function (Blueprint $table) {
            $table->id();
            $table->string('ho_va_ten')->nullable();
            $table->string('so_cccd')->nullable();
            $table->string('ngay_sinh')->nullable();
            $table->string('dia_chi')->nullable();
            $table->string('hang')->nullable();
            $table->string('dau_moi')->nullable();
            $table->string('le_phi')->nullable();
            $table->string('trang_thai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoc_phi');
    }
};
