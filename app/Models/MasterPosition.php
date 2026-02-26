<?php
// app/Models/MasterPosition.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPosition extends Model
{
    use HasFactory;

    protected $table = 'master_positions';
    protected $primaryKey = 'id_master_position';

    protected $fillable = [
        'position_name',
        'status'
    ];

    public $timestamps = true;

    public function positions()
    {
        return $this->hasMany(Position::class, 'id_master_position', 'id_master_position');
    }

}
