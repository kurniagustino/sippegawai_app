<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
     use HasFactory;
        // Tambahkan baris ini
        protected $table = 'pelatihan';

        protected $guarded = ['id'];

        public function pegawai()
        {
            return $this->belongsTo(Pegawai::class);
        }
}
