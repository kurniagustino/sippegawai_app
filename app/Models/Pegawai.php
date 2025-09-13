<?php

namespace App\Models;

use App\Models\Jabatan;
use App\Models\Pelatihan;
use App\Models\RiwayatPendidikan;
use App\Models\User;
use App\Models\Berkas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $guarded = ['id'];


    // Tambahkan method ini di dalam model Pegawai
    public function berkas()
    {
        return $this->hasMany(Berkas::class, 'pegawai_id');
    }

    /**
     * Mendefinisikan relasi ke model User (satu pegawai milik satu user).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }

    /**
     * Mendefinisikan relasi ke Riwayat Pendidikan (satu pegawai punya banyak riwayat).
     */
    public function riwayatPendidikans()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'pegawai_id');
    }

    /**
     * Mendefinisikan relasi ke Pelatihan (satu pegawai punya banyak pelatihan).
     */
    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'pegawai_id');
    }

    // Tambahkan relasi ini untuk menghubungkan ke tabel jabatans
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
