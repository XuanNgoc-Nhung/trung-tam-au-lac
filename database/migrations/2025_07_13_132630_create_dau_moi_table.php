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
        Schema::create('dau_moi', function (Blueprint $table) {
            $table->id();
            $table->string('ma_dau_moi')->unique();
            $table->string('ten_dau_moi');
            $table->integer('so_luong_thi_sinh')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dau_moi');
    }
};
