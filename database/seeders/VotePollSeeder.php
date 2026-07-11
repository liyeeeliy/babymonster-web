<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\VotePoll;
use Illuminate\Database\Seeder;

class VotePollSeeder extends Seeder
{
    public function run(): void
    {
        $poll = VotePoll::create([
            'title' => 'Favorite Member Bulan Ini',
            'type' => 'favorite_member',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'is_active' => true,
        ]);

        // Data sesuai persentase awal di halaman voting HTML
        $initial = ['Ahyeon' => 24, 'Ruka' => 19, 'Asa' => 17, 'Rora' => 15, 'Pharita' => 11, 'Rami' => 8, 'Chiquita' => 6];

        foreach ($initial as $name => $pct) {
            $poll->options()->create([
                'label' => $name,
                'image' => Member::where('name', $name)->value('photo'),
                'vote_count' => $pct * 10, // skala dummy awal
            ]);
        }
    }
}
