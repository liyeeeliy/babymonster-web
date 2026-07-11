<?php

namespace Database\Seeders;

use App\Models\Album;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AlbumSeeder extends Seeder
{
    public function run(): void
    {
        $albums = [
            [
                'name' => 'CHOOM - 3rd Mini Album',
                'cover' => '/img/choomAlbum.jpg',
                'price' => 350000,
                'stock' => 500,
                'tracklist' => ['CHOOM (Intro)', 'CHOOM', 'I LIKE IT', 'MOON'],
                // MP3 per lagu, urutan sama persis dengan tracklist di atas.
                // Taruh file di public/audio/ lalu ganti nama file di bawah.
                'track_audio' => [
                    '/audio/choom-intro.mp3',
                    '/audio/choom.mp3',
                    '/audio/i-like-it.mp3',
                    '/audio/moon.mp3',
                ],
                'release_date' => '2026-07-01',
                'description' => 'Mini album comeback terbaru BABYMONSTER dengan konsep bold dan energi penuh.',
            ],
            [
                'name' => 'BABYMONS7ER',
                'cover' => '/img/BABYMONS7ER.jpg',
                'price' => 240000,
                'stock' => 300,
                'tracklist' => ['MONSTERS (Intro)', 'SHEESH', 'BATTER UP', 'LIKE THAT'],
                'track_audio' => [
                    '/audio/monsters-intro.mp3',
                    '/audio/sheesh.mp3',
                    '/audio/batter-up.mp3',
                    '/audio/like-that.mp3',
                ],
                'release_date' => '2025-04-01',
                'description' => 'Album penuh pertama BABYMONSTER.',
            ],
            [
                'name' => 'DRIP',
                'cover' => '/img/dripAlbum.jpg',
                'price' => 250000,
                'stock' => 400,
                'tracklist' => ['DRIP', 'CLIK CLAK', 'BILLIONARE', 'FOREVER', 'LOVE IN MY HEART'],
                'track_audio' => [
                    '/audio/drip.mp3',
                    '/audio/clik-clak.mp3',
                    '/audio/billionare.mp3',
                    '/audio/forever.mp3',
                    '/audio/love-in-my-heart.mp3',
                ],
                'release_date' => '2024-11-01',
                'description' => 'Album studio perdana dengan pesona hip-hop YG sejati! Menghadirkan double title tracks "CLIK CLAK" & "DRIP" yang ditulis oleh G-Dragon.',
            ],
            [
                'name' => 'WE GO UP',
                'cover' => '/img/wegoupAlbum.jpg',
                'price' => 360000,
                'stock' => 250,
                'tracklist' => ['WE GO UP', 'PSYCHO', 'SUPA DUPA LUV', 'WILD'],
                'track_audio' => [
                    '/audio/we-go-up.mp3',
                    '/audio/psycho.mp3',
                    '/audio/supa-dupa-luv.mp3',
                    '/audio/wild.mp3',
                ],
                'release_date' => '2025-10-10',
                'description' => 'Energi baru dari BABYMONSTER lewat mini album kedua dengan formasi 6 anggota! Menghadirkan lagu utama "WE GO UP" yang penuh semangat dan ambisi, lengkap dengan 3 lagu baru yang adiktif.',
            ],
        ];

        foreach ($albums as $a) {
            Album::create([
                'name' => $a['name'],
                'slug' => Str::slug($a['name']) . '-' . Str::random(6),
                'cover' => $a['cover'] ?? null,
                'price' => $a['price'],
                'stock' => $a['stock'],
                'tracklist' => $a['tracklist'],
                'track_audio' => $a['track_audio'] ?? null,
                'release_date' => $a['release_date'],
                'description' => $a['description'],
                'is_active' => true,
            ]);
        }
    }
}
