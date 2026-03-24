<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    protected $table = 'salary';
    protected $primaryKey = 'ID_salary';

    protected $fillable = [
        'ID_master_salary',
        'salary_amount',
        'gross_salary',
        'total_deductions',
        'calculation_facts',
        'rule_engine_result',
        'salary_date',
        'transfer_proof',
    ];

    protected $casts = [
        'salary_amount' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'calculation_facts' => 'array',
        'rule_engine_result' => 'array',
        'salary_date' => 'date',
    ];

    public function masterSalary(): BelongsTo
    {
        return $this->belongsTo(MasterSalary::class, 'ID_master_salary', 'id_master_salary');
    }
}
