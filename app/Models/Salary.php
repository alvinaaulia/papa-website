<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $table = 'salary';
    protected $primaryKey = 'ID_salary';

    protected $fillable = [
        'ID_master_salary',
        'salary_amount',
        'salary_date',
        'transfer_proof',
    ];

    public function masterSalary()
    {
        return $this->belongsTo(MasterSalary::class, 'ID_master_salary', 'id_master_salary');
    }
}
