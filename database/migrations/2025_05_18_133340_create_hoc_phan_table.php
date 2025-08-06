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
        Schema::create('hoc_phan', function (Blueprint $table) {
            $table->id();
            $table->string('cccd', 12);
            $table->string('giao_vien');
            $table->time('gio_hoc');
            $table->date('ngay_hoc');
            $table->date('ngay_sinh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoc_phan');
    }
};
