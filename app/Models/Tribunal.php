<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tribunal extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'tribunales';
    protected $fillable = [
        'nombre',
        'paterno',
        'materno',
        'fecha_nacimiento',
        'ci',
        'email',
        'telefono',
        'grado',
        'user_id',
    ];
    protected $dates = ['deleted_at'];
    // Relación del tribunal con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relación del tribunal con proyectos
    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'proyecto_tribunal', 'tribunal_id', 'proyecto_id')
            ->withPivot('rol')
            ->withTimestamps();
    }
}
