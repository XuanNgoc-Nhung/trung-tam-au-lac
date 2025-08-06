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
        Schema::table('sat_hach', function (Blueprint $table) {
            $table->string('dau_moi')->nullable()->after('ngay_thi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sat_hach', function (Blueprint $table) {
            $table->dropColumn('dau_moi');
        });
    }
};
