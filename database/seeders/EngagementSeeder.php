<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\ForumPost;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class EngagementSeeder extends Seeder
{
    public function run(): void
    {
        $fan = User::where('username', 'monsterluv_id')->first();
        $staff = User::where('role', 'staff')->first();
        $admin = User::where('role', 'admin')->first();
        $users = collect([$fan, $staff, $admin])->filter();

        // Review pada album
        $reviews = [
            ['rating' => 5, 'review' => 'Kualitas albumnya premium banget, photocard-nya cakep! 💖'],
            ['rating' => 4, 'review' => 'Packaging rapi, pengiriman cepat. Recommended!'],
            ['rating' => 5, 'review' => 'Wajib punya buat MONBEBE sejati 🔥'],
        ];
        foreach (Album::take(3)->get() as $i => $album) {
            $album->reviews()->create(array_merge($reviews[$i % count($reviews)], ['user_id' => $fan->id]));
        }

        // Komentar + like pada forum
        foreach (ForumPost::all() as $post) {
            $post->comments()->create(['user_id' => ($staff->id ?? $fan->id), 'content' => 'Setuju banget! Ditunggu update selanjutnya 🙌']);
            $post->comments()->create(['user_id' => ($fan->id), 'content' => 'Real sih ini, BABYMONSTER emang keren.']);
            foreach ($users as $u) {
                $post->likes()->create(['user_id' => $u->id]);
            }
        }

        // Komentar pada berita
        foreach (News::take(2)->get() as $news) {
            $news->comments()->create(['user_id' => $fan->id, 'content' => 'Akhirnya comeback juga! 😭💚']);
        }
    }
}
