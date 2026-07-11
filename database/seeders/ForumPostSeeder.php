<?php

namespace Database\Seeders;

use App\Models\ForumPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class ForumPostSeeder extends Seeder
{
    public function run(): void
    {
        $fan = User::where('username', 'monsterluv_id')->first() ?? User::first();

        ForumPost::create([
            'user_id' => $fan->id,
            'title' => 'Preview lagu baru!',
            'content' => 'Preview lagu barunya enak banget, siapa yang udah dengerin?',
        ]);

        ForumPost::create([
            'user_id' => $fan->id,
            'title' => 'Koleksi photocard lengkap',
            'content' => 'Koleksi photocard gue akhirnya lengkap semua member!',
        ]);
    }
}
