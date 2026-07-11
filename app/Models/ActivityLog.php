<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'description', 'subject_type', 'subject_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Catat satu aktivitas admin/staff. Aman dipanggil tanpa subject. */
    public static function record(string $action, string $description, ?Model $subject = null): void
    {
        static::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
        ]);
    }
}
