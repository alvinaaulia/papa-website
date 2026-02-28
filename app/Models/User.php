<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::uuid();
        });
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



    public function profiles()
    {
        return $this->belongsTo(Profile::class, 'id', 'id_user');
    }

    public function masterSalary()
    {
        return $this->hasOne(MasterSalary::class, 'id_user');
    }

    const ROLE_DIRECTUR = 'direktur';
    const ROLE_PSDM = 'PSDM';
    const ROLE_PROJECT_MANAGER = 'project manager';
    const ROLE_KARYAWAN = 'karyawan';

    public static $roles = [
        self::ROLE_DIRECTUR => 'Direktur',
        self::ROLE_PSDM => 'PSDM/HRD',
        self::ROLE_PROJECT_MANAGER => 'Project Manager',
        self::ROLE_KARYAWAN => 'Karyawan',
    ];

    public function isDirektur()
    {
        return $this->role === self::ROLE_DIRECTUR;
    }

    public function isPSDM()
    {
        return $this->role === self::ROLE_PSDM;
    }

    public function isProjectManager()
    {
        return $this->role === self::ROLE_PROJECT_MANAGER;
    }

    public function isKaryawan()
    {
        return $this->role === self::ROLE_KARYAWAN;
    }

    // Helper untuk display role
    public function getRoleDisplayAttribute()
    {
        return self::$roles[$this->role] ?? $this->role;
    }
}
