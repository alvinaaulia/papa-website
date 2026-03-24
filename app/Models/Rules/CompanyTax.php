<?php

namespace App\Models\Rules;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyTax extends Model
{
    use HasFactory;

    protected $table = 'company_taxes';
    protected $primaryKey = 'tax_id';

    protected $fillable = [
        'tax_code',
        'tax_name',
        'tax_type',
        'calculation_method',
        'tax_rate',
        'income_min',
        'income_max',
        'effective_date',
        'end_date',
        'description',
        'is_active',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:4',
        'income_min' => 'decimal:2',
        'income_max' => 'decimal:2',
        'effective_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
}
