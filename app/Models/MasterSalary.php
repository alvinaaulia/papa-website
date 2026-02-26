<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSalary extends Model
{
    use HasFactory;

    protected $table = 'master_salary';
    protected $primaryKey = 'id_master_salary';
    public $incrementing = true;

    protected $fillable = [
        'id_user',
        'salary_amount',
        'pph21',
        'net_salary',
        'status'
    ];

    protected $casts = [
        'salary_amount' => 'decimal:2',
        'pph21' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
