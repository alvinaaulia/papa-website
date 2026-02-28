<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    /**
     * Tabel yang digunakan oleh model.
     */
    protected $table = 'presence';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id_presence';

    /**
     * Kolom yang boleh diisi mass-assignment.
     */
    protected $fillable = [
        'id_user',
        'date',
        'check_in',
        'check_out',
        'work_time',
        'in_photo',
        'out_photo',
    ];

    /**
     * Relasi ke user pemilik presensi.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}

