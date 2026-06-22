<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    protected $table = "laporan_kegiatan";

    protected $fillable = [
        'id_user',
        'id_kegiatan',
        'periode_laporan',
        'tanggal_laporan',
        'capaian',
        'target_persen',
        'realisasi_persen',
        'kendala',
        'file_laporan',
        'status',
    ];

    public function kegiatan() {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }
}
