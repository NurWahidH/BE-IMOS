<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Tiket extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tiket';

    protected $fillable = [
        'user_id',
        'aset_id',
        'kondisi',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }
}
