<?php

namespace App\Traits;

use App\Models\LogUser;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity('create', $model, "Menambahkan data " . class_basename($model));
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            $original = [];
            $new = [];
            foreach ($dirty as $key => $value) {
                if ($key !== 'updated_at') {
                    $original[$key] = $model->getOriginal($key);
                    $new[$key] = $value;
                }
            }
            
            $payload = [
                'old' => $original,
                'new' => $new
            ];

            static::logActivity('update', $model, "Mengubah data " . class_basename($model), $payload);
        });

        static::deleted(function ($model) {
            $payload = ['deleted_data' => $model->toArray()];
            static::logActivity('delete', $model, "Menghapus data " . class_basename($model), $payload);
        });
    }

    protected static function logActivity(string $action, $model, string $description, ?array $payload = null)
    {
        $userId = Auth::guard('api')->id() ?? Auth::id();
        
        if ($userId) {
            LogUser::create([
                'idUser' => $userId,
                'action' => $action,
                'target_table' => $model->getTable(),
                'target_id' => $model->getKey(),
                'description' => $description . " (ID: " . ($model->getKey() ?? '-') . ")",
                'payload' => $payload,
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);
        }
    }
}
