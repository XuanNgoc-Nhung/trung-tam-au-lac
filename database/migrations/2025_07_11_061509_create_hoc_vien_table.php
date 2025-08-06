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
        Schema::create('hoc_vien', function (Blueprint $table) {
            $table->id();
            $table->string('ho')->nullable();
            $table->string('ten')->nullable();
            $table->string('ngay_sinh')->nullable();
            $table->string('cccd')->nullable();
            $table->string('dia_chi')->nullable();
            $table->string('khoa_hoc')->nullable();
            $table->string('noi_dung_thi')->nullable();
            $table->string('ngay_sat_hach')->nullable();
            $table->string('dau_moi')->nullable();
            $table->string('ly_thuyet')->nullable();
            $table->string('mo_phong')->nullable();
            $table->string('thuc_hanh')->nullable();
            $table->string('duong_truong')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoc_vien');
    }
};
