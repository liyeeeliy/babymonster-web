<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MemberSeeder::class,
            AlbumSeeder::class,
            NewsSeeder::class,
            ForumPostSeeder::class,
            VotePollSeeder::class,
            EngagementSeeder::class,   // review, komentar, like
            DemoOrderSeeder::class,    // pesanan demo + pembayaran
        ]);
    }
}
