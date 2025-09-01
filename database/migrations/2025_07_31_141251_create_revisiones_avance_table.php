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
         Schema::create('revisiones_avance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avance_id');
            $table->unsignedBigInteger('revisor_id'); 
            $table->enum('rol', ['tutor', 'tribunal']);
            $table->enum('estado', ['pendiente', 'en_revision', 'aprobado', 'observado'])->default('pendiente');
            $table->text('observaciones');
            $table->string('informe_path')->nullable();
            $table->timestamps();

            $table->foreign('avance_id')->references('id')->on('avances')->onDelete('cascade');
            $table->foreign('revisor_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['avance_id', 'revisor_id']); // Evita duplicidad de revisión
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisiones_avance');
    }
};
