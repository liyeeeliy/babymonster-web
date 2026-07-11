<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    // Catatan keamanan: 'role' & 'is_active' sengaja TIDAK mass-assignable.
    // Keduanya hanya boleh diubah lewat aksi admin khusus (UserManagementController),
    // untuk mencegah privilege escalation via form register/profil.
    protected $fillable = [
        'name', 'username', 'email', 'password',
        'avatar', 'bio', 'favorite_member_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ---- Role helpers ----
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isStaff(): bool { return $this->role === 'staff'; }
    public function isMember(): bool { return $this->role === 'member'; }

    // ---- Relationships ----
    public function favoriteMember()
    {
        return $this->belongsTo(Member::class, 'favorite_member_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    /** Koleksi album yang sudah dibeli (dari order yang statusnya bukan pending/cancelled) */
    public function purchasedAlbums()
    {
        return Album::whereHas('orderItems.order', function ($q) {
            $q->where('user_id', $this->id)->whereNotIn('status', ['pending', 'cancelled']);
        });
    }
}
