Taruh foto berita di folder ini.

Nama file harus cocok dengan field 'thumbnail' di database/seeders/NewsSeeder.php:

  comeback-mini-album.jpg
  highlight-medley.jpg
  fan-meeting-asia-tour.jpg

Contoh path di seeder: /img/news/comeback-mini-album.jpg
-> berarti file-nya = public/img/news/comeback-mini-album.jpg

SPESIFIKASI FOTO
- Rasio 16:9 (landscape). Kartu berita memakai aspect-video.
- Ukuran ideal 1600x900 px (minimal 1200x675 px).
- Gaya editorial / press photo, bukan selfie close-up:
    Comeback -> foto konsep / teaser
    Rilis    -> still MV atau foto panggung
    Event    -> suasana fan meeting / panggung / kerumunan fans

Chip kategori (pink) menempel di pojok kiri-atas foto. Sudah ada gradien gelap
di atas foto supaya chip tetap terbaca walau fotonya terang.

Berita tanpa thumbnail otomatis tampil kotak gradien + inisial, bukan gambar rusak.
