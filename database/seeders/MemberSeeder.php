<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan array = urutan tampil (sort_order = $i + 1).
        //
        // 'gallery' = 3 foto di halaman detail member. Taruh file di public/img/members/gallery/.
        //             Item boleh berupa string path, atau ['src' => path, 'pos' => '50% 20%']
        //             bila titik crop foto perlu digeser.
        //
        // 'biodata' = pasangan label => nilai. Di halaman detail member tiap label
        //             tampil pada barisnya sendiri, dengan nama label dicetak tebal.
        $members = [
            [
                'name' => 'Ruka',
                'position' => 'The Hip-Hop Queen / YG DNA [Main Rapper, Main Dancer]',
                'nationality' => 'Jepang',
                'biodata' => [
                    'Nama Lengkap' => 'Kawai Ruka (河井 瑠花)',
                    'Nama Panggung' => 'Ruka (루카)',
                    'Tempat Lahir' => 'Okayama, Jepang',
                    'Tanggal Lahir' => '20 Maret 2002',
                    'Zodiak' => 'Pisces',
                    'Shio' => 'Kuda',
                    'Golongan Darah' => 'O',
                    'Tinggi Badan' => '± 158 cm',
                    'Kebangsaan' => 'Jepang',
                ],
                'gallery' => [
                    '/img/members/gallery/ruka-1.jpg',
                    '/img/members/gallery/ruka-2.jpg',
                    ['src' => '/img/members/gallery/ruka-3.jpg', 'pos' => '50% 20%'],
                ],
            ],
            [
                'name' => 'Pharita',
                'position' => 'The Disney Princess Visual [Main Vocalist, Visual]',
                'nationality' => 'Thailand',
                'biodata' => [
                    'Nama Lengkap' => 'Pharita Chaikong (ภาริตา ไชยคง)',
                    'Nama Panggung' => 'Pharita (파리타)',
                    'Nama Panggilan' => 'Rita, Look Pear',
                    'Tempat Lahir' => 'Bangkok, Thailand',
                    'Tanggal Lahir' => '26 Agustus 2005',
                    'Zodiak' => 'Virgo',
                    'Shio' => 'Ayam',
                    'Golongan Darah' => 'Antara O atau A',
                    'Tinggi Badan' => '± 170 cm',
                    'Kebangsaan' => 'Thailand',
                ],
                'gallery' => [
                    ['src' => '/img/members/gallery/pharita-1.jpg', 'pos' => '50% 20%'],
                    '/img/members/gallery/pharita-2.jpg',
                    ['src' => '/img/members/gallery/pharita-3.jpg', 'pos' => '50% 20%'],
                ],
            ],
            [
                'name' => 'Asa',
                'position' => 'The Rap Prodigy [Main Rapper, Lead Dancer, Sub-Vocalist]',
                'nationality' => 'Jepang',
                'biodata' => [
                    'Nama Lengkap' => 'Enami Asa (榎並 杏紗)',
                    'Nama Panggung' => 'Asa (아사)',
                    'Tempat Lahir' => 'Prefektur Okayama, Jepang',
                    'Tanggal Lahir' => '17 April 2006',
                    'Zodiak' => 'Aries',
                    'Shio' => 'Anjing',
                    'Golongan Darah' => 'O',
                    'Tinggi Badan' => '± 163 cm',
                    'Kebangsaan' => 'Jepang',
                ],
                'gallery' => [
                    '/img/members/gallery/asa-1.jpg',
                    '/img/members/gallery/asa-2.jpg',
                    '/img/members/gallery/asa-3.jpg',
                ],
            ],
            [
                'name' => 'Ahyeon',
                'position' => 'All-Rounder [Center, Main Vocalist, Lead Rapper]',
                'nationality' => 'Korea Selatan',
                'biodata' => [
                    'Nama Lengkap' => 'Jung Ahyeon (정아현)',
                    'Nama Panggung' => 'Ahyeon (아현)',
                    'Tempat Lahir' => 'Toegye-dong, Chuncheon, Gangwon-do, Korea Selatan',
                    'Tanggal Lahir' => '11 April 2007',
                    'Zodiak' => 'Aries',
                    'Shio' => 'Babi',
                    'Golongan Darah' => 'A',
                    'Tinggi Badan' => '± 163 cm',
                    'Kebangsaan' => 'Korea Selatan',
                ],
                'gallery' => [
                    ['src' => '/img/members/gallery/ahyeon-1.jpg', 'pos' => '50% 20%'],
                    '/img/members/gallery/ahyeon-2.jpg',
                    '/img/members/gallery/ahyeon-3.jpg',
                ],
            ],
            [
                'name' => 'Rami',
                'position' => 'The Vocal Monster [Main Vocalist]',
                'nationality' => 'Korea Selatan',
                'biodata' => [
                    'Nama Lengkap' => 'Shin Haram (신하람)',
                    'Nama Panggung' => 'Rami (라미) – sebelumnya dikenal sebagai Haram',
                    'Tempat Lahir' => 'Sangam-dong, Mapo-gu, Seoul, Korea Selatan',
                    'Tanggal Lahir' => '17 Oktober 2007',
                    'Zodiak' => 'Libra',
                    'Shio' => 'Babi',
                    'Golongan Darah' => 'O',
                    'Tinggi Badan' => '± 172 cm',
                    'Kebangsaan' => 'Korea Selatan',
                ],
                'gallery' => [
                    '/img/members/gallery/rami-1.jpg',
                    '/img/members/gallery/rami-2.jpg',
                    '/img/members/gallery/rami-3.jpg',
                ],
            ],
            [
                'name' => 'Rora',
                'position' => 'The Aurora Voice [Lead Vocalist, Lead Dancer, Visual]',
                'nationality' => 'Korea Selatan',
                'biodata' => [
                    'Nama Lengkap' => 'Lee Dain (이다인)',
                    'Nama Panggung' => 'Rora (로라)',
                    'Tempat Lahir' => 'Gangneung, Gangwon-do, Korea Selatan',
                    'Tanggal Lahir' => '14 Agustus 2008',
                    'Zodiak' => 'Leo',
                    'Shio' => 'Tikus',
                    'Golongan Darah' => 'O',
                    'Tinggi Badan' => '± 170 cm',
                    'Kebangsaan' => 'Korea Selatan',
                ],
                'gallery' => [
                    ['src' => '/img/members/gallery/rora-1.jpg', 'pos' => '50% 20%'],
                    '/img/members/gallery/rora-2.jpg',
                    '/img/members/gallery/rora-3.jpg',
                ],
            ],
            [
                'name' => 'Chiquita',
                'position' => 'The Monster Rookie [Lead Vocalist, Lead Dancer, Sub-Rapper]',
                'nationality' => 'Amerika Serikat',
                'biodata' => [
                    'Nama Lengkap' => 'Riracha Phondechaphiphat (ริราชา พรเดชาพิพัฒน์)',
                    'Nama Panggung' => 'Chiquita (치키타)',
                    'Nama Panggilan' => 'Canny',
                    'Tempat Lahir' => 'Nakhon Ratchasima, Thailand',
                    'Tanggal Lahir' => '17 Februari 2009',
                    'Zodiak' => 'Aquarius',
                    'Shio' => 'Kerbau',
                    'Golongan Darah' => 'O',
                    'Tinggi Badan' => '± 165 cm',
                    'Kebangsaan' => 'Thailand',
                ],
                'gallery' => [
                    ['src' => '/img/members/gallery/chiquita-1.jpg', 'pos' => '50% 20%'],
                    '/img/members/gallery/chiquita-2.jpg',
                    ['src' => '/img/members/gallery/chiquita-3.jpg', 'pos' => '50% 20%'],
                ],
            ],
        ];

        foreach ($members as $i => $m) {
            $slug = Str::slug($m['name']);
            Member::create([
                'name' => $m['name'],
                'slug' => $slug,
                'photo' => '/img/members/' . $slug . '.jpg',
                'gallery' => $m['gallery'] ?? null,
                'biodata' => $m['biodata'] ?? null,
                'position' => $m['position'],
                'nationality' => $m['nationality'],
                'description' => $m['name'] . ' adalah member BABYMONSTER dengan peran sebagai ' . strtolower($m['position']) . '.',
                'facts' => $m['facts'] ?? null,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }
    }
}
