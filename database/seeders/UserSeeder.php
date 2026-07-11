<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->createUser('Super Admin', 'admin', 'admin@monsterhub.test', 'admin');
        $this->createUser('Staff Konten', 'staff', 'staff@monsterhub.test', 'staff');
        $this->createUser('Monster Fan ID', 'monsterluv_id', 'fan@monsterhub.test', 'member', 'Fan berat BABYMONSTER sejak debut!');
    }

    /**
     * 'role' & 'email_verified_at' TIDAK ada di User::$fillable (disengaja, agar
     * peran tidak bisa diubah lewat form register/profil). Karena itu keduanya
     * tidak bisa dikirim lewat User::create() — harus diset eksplisit,
     * kalau tidak nilainya diam-diam dibuang dan role jatuh ke default 'member'.
     */
    private function createUser(string $name, string $username, string $email, string $role, ?string $bio = null): void
    {
        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make('password'),
            'bio' => $bio,
        ]);

        $user->forceFill([
            'role' => $role,
            'email_verified_at' => now(),
        ])->save();
    }
}
