<?php

namespace App\Providers;

use App\Models\Album;
use App\Models\Comment;
use App\Models\ForumPost;
use App\Models\Member;
use App\Models\News;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tanggal berbahasa Indonesia (mis. "07 Juli 2026").
        Carbon::setLocale('id');

        // Morph map: simpan alias pendek di kolom *_type, bukan nama kelas PHP penuh,
        // supaya database tidak terkopel dengan namespace & aman saat refactor.
        // Catatan: karena map ini di-enforce, SETIAP model yang dipakai dalam relasi
        // polymorphic (termasuk sebagai subject di ActivityLog) wajib terdaftar di sini,
        // kalau tidak getMorphClass() akan melempar ClassMorphViolationException.
        Relation::enforceMorphMap([
            'user' => User::class,
            'album' => Album::class,
            'news' => News::class,
            'forum_post' => ForumPost::class,
            'comment' => Comment::class,
            'member' => Member::class,
            'order' => Order::class,
        ]);
    }
}
