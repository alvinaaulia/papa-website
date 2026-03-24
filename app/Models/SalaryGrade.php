<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryGrade extends Model
{
    use HasFactory;

    protected $table = 'salary_grades';
    protected $primaryKey = 'id_salary_grade';
    public $incrementing = true;

    protected $fillable = [
        'grade_code',
        'grade_name',
        'min_score',
        'max_score',
        'base_salary',
        'status',
        'description',
    ];

    protected $casts = [
        'min_score' => 'integer',
        'max_score' => 'integer',
        'base_salary' => 'decimal:2',
    ];
}
