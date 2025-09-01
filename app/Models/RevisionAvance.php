<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisionAvance extends Model
{
    protected $table = 'revisiones_avance';

    protected $fillable = [
        'avance_id',
        'revisor_id',
        'rol',
        'estado',
        'observaciones',
        'informe_path',
        'fecha_revision',
    ];
    // Relación de la revisión con el avance
    public function avance()
    {
        return $this->belongsTo(Avance::class);
    }
    // Relación de la revisión con el revisor
    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisor_id');
    }
    // Relación de la revisión con el tribunal
    public function tribunal()
    {
        return $this->belongsTo(Tribunal::class, 'revisor_id', 'user_id')
            ->withPivot('rol')
            ->withTimestamps();
    }
}