<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('role', 'staff')->first() ?? User::first();

        // 'thumbnail' = foto berita rasio 16:9 (kartu memakai aspect-video).
        //               Taruh file di public/img/news/.
        // 'excerpt'   = kalimat lead, sengaja BERBEDA dari judul.
        $news = [
            [
                'title' => 'BABYMONSTER umumkan comeback mini album terbaru',
                'category' => 'Comeback',
                'date' => '2026-07-01',
                'thumbnail' => '/img/news/comeback-mini-album.jpg',
                'excerpt' => 'Grup asuhan YG ini dikonfirmasi kembali lewat mini album ketiga mereka. Pre-order dibuka lebih awal khusus untuk MONBEBE.',
                'content' => <<<'TEXT'
                    Agensi resmi mengumumkan bahwa BABYMONSTER akan kembali menyapa penggemar lewat mini album ketiga mereka. Pengumuman ini disampaikan melalui kanal resmi grup, lengkap dengan jadwal rilis dan rangkaian promosi yang akan berlangsung selama beberapa pekan ke depan.

                    Mini album ini disebut membawa warna yang lebih berani dibanding rilisan sebelumnya. Seluruh anggota dikabarkan terlibat lebih dalam pada proses produksi, mulai dari penulisan lirik hingga penyusunan koreografi untuk lagu utama.

                    Pre-order dibuka lebih awal bagi MONBEBE, dengan bonus photocard acak dan poster edisi terbatas untuk pembelian pada periode pertama. Pihak agensi mengimbau penggemar untuk membeli hanya melalui kanal resmi guna menghindari produk tidak resmi.

                    Rangkaian promosi akan dimulai tak lama setelah tanggal rilis, mencakup penampilan di sejumlah program musik serta sesi jumpa penggemar yang jadwal lengkapnya akan diumumkan menyusul.
                    TEXT,
            ],
            [
                'title' => 'Highlight medley resmi dirilis, fans sambut antusias',
                'category' => 'Rilis',
                'date' => '2026-06-28',
                'thumbnail' => '/img/news/highlight-medley.jpg',
                'excerpt' => 'Cuplikan seluruh trek dirilis melalui kanal resmi. Kolom komentar langsung dibanjiri reaksi MONBEBE dari berbagai negara.',
                'content' => <<<'TEXT'
                    Cuplikan seluruh trek dalam album terbaru BABYMONSTER akhirnya dirilis melalui kanal resmi grup. Video berdurasi beberapa menit itu menampilkan potongan singkat dari setiap lagu, memberi gambaran arah musik yang diusung pada era kali ini.

                    Respons penggemar datang nyaris seketika. Kolom komentar dipenuhi reaksi MONBEBE dari berbagai negara, dengan banyak di antaranya menyoroti perbedaan warna vokal dan bagian rap yang terasa lebih matang dibanding rilisan sebelumnya.

                    Beberapa trek disebut memiliki nuansa yang lebih eksperimental, sementara lagu utama tetap mempertahankan energi khas yang selama ini menjadi ciri grup. Sejumlah penggemar juga menyoroti pembagian part yang dinilai lebih merata antaranggota.

                    Album lengkapnya dijadwalkan tersedia di layanan streaming pada tanggal rilis resmi, bersamaan dengan peluncuran video musik untuk lagu utama.
                    TEXT,
            ],
            [
                'title' => 'Jadwal fan meeting Asia Tour diumumkan',
                'category' => 'Event',
                'date' => '2026-06-25',
                'thumbnail' => '/img/news/fan-meeting-asia-tour.jpg',
                'excerpt' => 'Tur sapa penggemar akan menyambangi sejumlah kota besar di Asia. Detail tanggal, venue, dan penjualan tiket diumumkan bertahap.',
                'content' => <<<'TEXT'
                    BABYMONSTER dipastikan menggelar rangkaian fan meeting yang akan menyambangi sejumlah kota besar di Asia. Pengumuman resmi memuat daftar kota beserta gambaran umum rangkaian acara yang disiapkan untuk penggemar.

                    Setiap sesi direncanakan mencakup penampilan langsung, segmen permainan bersama penggemar, serta sesi tanya jawab. Pihak penyelenggara menyebut format acara dirancang agar interaksi anggota dengan penggemar terasa lebih dekat dibanding konser reguler.

                    Detail tanggal, venue, dan skema penjualan tiket akan diumumkan secara bertahap melalui kanal resmi masing-masing negara. Penggemar diimbau memantau pengumuman agar tidak melewatkan periode penjualan awal.

                    Kapasitas tiap venue disebut terbatas, sehingga penyelenggara membuka mekanisme antrean daring untuk mencegah penumpukan pada hari penjualan.
                    TEXT,
            ],
        ];

        foreach ($news as $n) {
            News::create([
                'title' => $n['title'],
                'slug' => Str::slug($n['title']) . '-' . Str::random(6),
                'thumbnail' => $n['thumbnail'] ?? null,
                'content' => $n['content'],
                'excerpt' => $n['excerpt'],
                'category' => $n['category'],
                'author_id' => $author->id,
                'status' => 'published',
                'published_at' => $n['date'],
            ]);
        }
    }
}
