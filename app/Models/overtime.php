<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Overtime extends Model
{
    use HasFactory;

    protected $table = 'overtime';

    protected $primaryKey = 'id_overtime';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_user',
        'date',
        'start_overtime',
        'end_overtime',
        'total_overtime',
        'description',
        'proof',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'start_overtime' => 'datetime:H:i',
        'end_overtime' => 'datetime:H:i',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_overtime)) {
                $model->id_overtime = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
