<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPendidikan extends Model
{
    use HasFactory;
        // Tambahkan baris ini
        protected $table = 'riwayat_pendidikan';

        protected $guarded = ['id'];

        public function pegawai()
        {
            return $this->belongsTo(Pegawai::class);
        }
}
