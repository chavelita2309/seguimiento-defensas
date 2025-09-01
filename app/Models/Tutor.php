<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'tutores';
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
    // Relación del tutor con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relación del tutor con proyectos
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }
    
}
