<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';
    protected $primaryKey = 'id_position';

    protected $fillable = [
        'id_master_position',
        'id_user',
        'entry_date'
    ];

    protected $casts = [
        'entry_date' => 'date:Y-m-d' // Format konsisten
    ];

    public function masterPosition()
    {
        return $this->belongsTo(MasterPosition::class, 'id_master_position', 'id_master_position');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
