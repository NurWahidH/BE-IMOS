<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Lokasi extends Model
{
    use LogsActivity;

    protected $table = 'lokasi';

    protected $fillable = [
        'unit', 'nama_ruangan'
    ];
}
