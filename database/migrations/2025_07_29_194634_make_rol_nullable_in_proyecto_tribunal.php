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
        Schema::table('proyecto_tribunal', function (Blueprint $table) {
            //
             $table->string('rol')->nullable()->change(); //cambio porque el rol puede ser nulo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyecto_tribunal', function (Blueprint $table) {
            //
             $table->string('rol')->nullable(false)->change();
        });
    }
};
