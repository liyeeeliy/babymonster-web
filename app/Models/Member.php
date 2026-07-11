<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'birth_date', 'position', 'description',
        'photo', 'gallery', 'biodata', 'facts', 'nationality', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'gallery' => 'array',
            'biodata' => 'array',
            'facts' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function fans()
    {
        return $this->hasMany(User::class, 'favorite_member_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
