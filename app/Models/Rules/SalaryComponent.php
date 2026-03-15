<?php

namespace App\Models\Rules;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Rules\RuleVersion;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $table = 'salary_components';
    protected $primaryKey = 'component_id';

    protected $fillable = [
        'component_code',
        'component_name',
        'component_type',
        'description',
        'is_taxable',
        'is_active',
    ];

    protected $casts = [
        'is_taxable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function ruleVersions()
    {
        return $this->belongsToMany(
            \App\Models\Rules\RuleVersion::class,
            'rule_version_components',
            'component_id',
            'rule_version_id'
        );
    }
}
