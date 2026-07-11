Taruh 3 foto galeri per member di folder ini.

Nama file harus cocok dengan field 'gallery' di database/seeders/MemberSeeder.php:

  ruka-1.jpg     ruka-2.jpg     ruka-3.jpg
  pharita-1.jpg  pharita-2.jpg  pharita-3.jpg
  asa-1.jpg      asa-2.jpg      asa-3.jpg
  ahyeon-1.jpg   ahyeon-2.jpg   ahyeon-3.jpg
  rami-1.jpg     rami-2.jpg     rami-3.jpg
  rora-1.jpg     rora-2.jpg     rora-3.jpg
  chiquita-1.jpg chiquita-2.jpg chiquita-3.jpg

Contoh path di seeder: /img/members/gallery/ruka-1.jpg
-> berarti file-nya = public/img/members/gallery/ruka-1.jpg

Kartu galeri memakai rasio 4:3 (landscape). Foto dipotong otomatis (object-cover),
jadi pakai foto landscape supaya hasilnya paling rapi.

Slot yang dikosongkan (null) otomatis tampil sebagai kotak gradien, bukan gambar rusak.
