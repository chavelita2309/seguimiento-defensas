<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avance extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'avances';
    protected $fillable = [
        'proyecto_id',
        'user_id',
        'titulo',
        'descripcion',
        'archivo_path',
        'fecha_entrega',
        'fecha_limite_revision',
        'estado',
        'observaciones',
        'deleted_by',
        'informe_path', // Nuevo campo para registrar la ruta del informe del revisor
    ];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'fecha_entrega' => 'datetime',
    ];
    /**
     * Proyecto al que pertenece este avance.
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Usuario que registró el avance (tribunal o postulante).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Usuario que eliminó el avance (solo si fue eliminado).
    public function eliminador()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    // Alcance local para obtener avances que pueden ser revisados (registrados hace más de una hora).
    public function scopeRevisables($query)
    {
        return $query->where('created_at', '<=', now()->subHour());
    }
    // Relación con las revisiones del avance.
    public function revisiones()
    {
        return $this->hasMany(RevisionAvance::class, 'avance_id');
    }
}
