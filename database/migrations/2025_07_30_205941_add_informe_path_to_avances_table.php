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
        Schema::table('avances', function (Blueprint $table) {
        $table->string('informe_path')->nullable(); // ruta del informe del revisor
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avances', function (Blueprint $table) {
            $table->dropColumn('informe_path');
        });
    }
};
