<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogUser extends Model
{
    use HasFactory;

    protected $table = 'log_user';

    protected $fillable = [
        'idUser',
        'action',
        'target_table',
        'target_id',
        'description',
        'payload',
        'ip_address',
        'user_agent',
    ];

    protected $dispatchesEvents = [
        'created' => \App\Events\ActivityLogged::class,
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUser');
    }
}
