Taruh file MP3 lagu di folder ini.

Nama file harus cocok dengan yang ditulis di database/seeders/AlbumSeeder.php
(field 'track_audio' pada tiap album), contoh: choom.mp3, sheesh.mp3, dst.

Di seeder path ditulis dengan awalan "/audio/", contoh: /audio/choom.mp3
-> berarti file-nya = public/audio/choom.mp3

Track yang belum ada file MP3-nya otomatis kembali ke mode simulasi.
