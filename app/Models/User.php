<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Relación del usuario con tribunal, postulante o tutor
    public function postulante()
    {
        return $this->hasOne(Postulante::class);
    }
    // relación del usuario con avances
    public function avances()
    {
        return $this->hasMany(Avance::class);
    }
    // relación del usuario con tribunal
    public function tribunales()
    {
        return $this->hasOne(Tribunal::class);
    }
    // relación del usuario con proyectos como tribunal
    public function proyectosComoTribunal()
    {
        return $this->belongsToMany(
            Proyecto::class,
            'proyecto_tribunal',
            'tribunal_id',   // ← columna en la tabla pivote que apunta a `users.id`
            'proyecto_id'    // ← columna que apunta a `proyectos.id`
        )->withPivot('rol')
            ->withTimestamps();
    }
}
