<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat User untuk Admin
        $adminUser = User::create([
            'username'      => 'admin',
            'name'          => 'Administrator',
            'email'         => 'admin@sippegawai.com',
            'password'      => Hash::make('password'),
            'role'          => 'admin',
        ]);

        Pegawai::create([
            'idUser'        => $adminUser->id,
            'nama'          => 'Administrator',
            'nip'           => 'ADMIN001',
            'jenis_kelamin' => 'L',
            'jabatan_id'    => null, // Ganti menjadi null agar tidak ada error
        ]);

        // 2. Buat User untuk Pegawai
        $pegawaiUser = User::create([
            'username'      => 'noris',
            'name'          => 'Norisman Novlin',
            'email'         => 'noris@sippegawai.com',
            'password'      => Hash::make('password'),
            'role'          => 'pegawai',
        ]);

        Pegawai::create([
            'idUser'        => $pegawaiUser->id,
            'nama'          => 'Norisman Novlin',
            'nip'           => '1505061111890003',
            'jenis_kelamin' => 'L',
            'jabatan_id'    => null, // Ganti menjadi null juga di sini
            'tempatlahir'   => 'Jambi',
            'tanggal_lahir' => '1989-11-11',
        ]);
    }
}