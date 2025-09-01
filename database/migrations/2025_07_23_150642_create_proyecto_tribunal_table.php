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
        Schema::create('proyecto_tribunal', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('proyecto_id');
            $table->unsignedBigInteger('tribunal_id');
            $table->string('rol'); // lider
            $table->timestamps();

            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('cascade');
            $table->foreign('tribunal_id')->references('id')->on('tribunales')->onDelete('cascade');

            $table->unique(['proyecto_id', 'tribunal_id']);           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto_tribunal');
    }
};
