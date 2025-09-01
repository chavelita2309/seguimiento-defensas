<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Postulante extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'postulantes';
    protected $fillable = [
        'nombre',
        'paterno',
        'materno',
        'fecha_nacimiento',
        'ci',
        'ru',
        'email',
        'telefono',
        'user_id',
    ];
    protected $dates = ['deleted_at'];

    // Relación del usuario con post
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relación del postulante con proyecto
    public function proyecto()
    {
        return $this->hasOne(Proyecto::class);
    }
    
}
