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
        Schema::create('avances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyecto_id');
            $table->unsignedBigInteger('user_id'); // ID del usuario que hizo el avance

            $table->string('titulo'); 
            $table->text('descripcion')->nullable(); 
            $table->string('archivo_path')->nullable(); 
            $table->date('fecha_entrega'); // fecha en la que se hizo el avance
            $table->date('fecha_limite_revision')->nullable(); // fecha máxima para que lo revisen
            $table->enum('estado', ['pendiente', 'en_revision', 'aprobado', 'observado'])->default('pendiente');
            $table->text('observaciones'); // comentarios del revisor
            $table->timestamps();
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avances');
    }
};
