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
         // Postulantes
        Schema::table('postulantes', function (Blueprint $table) {
            $table->string('paterno')->nullable()->change();
            $table->string('materno')->nullable()->change();
        });

        // Tutores
        Schema::table('tutores', function (Blueprint $table) {
            $table->string('paterno')->nullable()->change();
            $table->string('materno')->nullable()->change();
        });

        // Tribunales
        Schema::table('tribunales', function (Blueprint $table) {
            $table->string('paterno')->nullable()->change();
            $table->string('materno')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postulantes', function (Blueprint $table) {
            $table->string('paterno')->nullable(false)->change();
            $table->string('materno')->nullable(false)->change();
        });

        Schema::table('tutores', function (Blueprint $table) {
            $table->string('paterno')->nullable(false)->change();
            $table->string('materno')->nullable(false)->change();
        });

        Schema::table('tribunales', function (Blueprint $table) {
            $table->string('paterno')->nullable(false)->change();
            $table->string('materno')->nullable(false)->change();
        });
    }
};
