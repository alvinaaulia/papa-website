<?php
// app/Models/MasterDocument.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_name',
        'status'
    ];
}