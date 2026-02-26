<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    protected $table = 'leave';

    protected $primaryKey = 'id_leave';

    public $incrementing = true;

    protected $fillable = [
        'id_user',
        'leave_type',
        'reason',
        'start_of_leave',
        'end_of_leave',
        'amount_of_leave',
        'notes',
        'leave_address',
        'phone_number',
        'status_pm',
        'status_hrd',
        'status_director',
    ];

    protected $casts = [
        'start_of_leave' => 'date',
        'end_of_leave' => 'date',
    ];

    protected $appends = ['display_status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * Status tampilan: approved jika semua setuju, rejected jika ada yang reject, selain itu pending.
     */
    public function getDisplayStatusAttribute(): string
    {
        foreach (['status_pm', 'status_hrd', 'status_director'] as $col) {
            if (($this->{$col} ?? 'pending') === 'rejected') {
                return 'rejected';
            }
        }
        $all = [
            $this->status_pm ?? 'pending',
            $this->status_hrd ?? 'pending',
            $this->status_director ?? 'pending',
        ];
        return in_array('pending', $all) ? 'pending' : 'approved';
    }
}
