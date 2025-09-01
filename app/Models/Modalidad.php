<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modalidad extends Model
{
    //
    use HasFactory;
    protected $table = 'modalidades';
    protected $fillable = [
        'nombre',
        'nivel',
        'descripcion',
    ];
    // Relación con los proyectos de esta modalidad.
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

}
