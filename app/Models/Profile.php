<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $table = 'profiles';

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id_profile';
    protected $guarded = ['id_profile'];

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($model) {

            $model->id_profile = (string) \Illuminate\Support\Str::uuid();

        });
    }
    public function user()
    {
        return $this->hasOne(User::class,'id_user','id');
    }

}
