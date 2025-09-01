<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'proyectos';
    protected $fillable = [
        'titulo',
        'descripcion',
        'resolucion',
        'fecha',
        'postulante_id',
        'tutor_id',
        'modalidad_id',

    ];
    protected $dates = ['deleted_at'];
    // Relación del proyecto con postulante
    public function postulante()
    {
        return $this->belongsTo(Postulante::class);
    }
    // Relación del proyecto con tutor
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
    // Relación del proyecto con modalidad
    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class);
    }
    // Relación del proyecto con tribunales
    public function tribunales()
    {
        // La relación pertenece a la clase Tribunal, no a User
        return $this->belongsToMany(\App\Models\Tribunal::class, 'proyecto_tribunal', 'proyecto_id', 'tribunal_id')
            ->withPivot('rol');
    }
    // Relación del proyecto con avances
    public function avances()
    {
        return $this->hasMany(Avance::class);
    }
}
