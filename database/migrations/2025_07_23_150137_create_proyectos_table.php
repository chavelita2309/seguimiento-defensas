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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('resolucion');
            $table->date('fecha');
            $table->unsignedBigInteger('postulante_id');
            $table->unsignedBigInteger('tutor_id');
            $table->unsignedBigInteger('modalidad_id');
            $table->foreign('postulante_id')->references('id')->on('postulantes')->onDelete('cascade');
            $table->foreign('tutor_id')->references('id')->on('tutores')->onDelete('cascade');
            $table->foreign('modalidad_id')->references('id')->on('modalidades')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
