<?php

namespace App\Models\Rules;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleVersionComponent extends Model
{
    use HasFactory;

    protected $table = 'rule_version_components';

    protected $fillable = [
        'rule_version_id',
        'component_id',
    ];
}
