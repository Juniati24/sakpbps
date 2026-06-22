<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlasifikasiKegiatan extends Model
{
    protected $table = 'klasifikasi_kegiatan';

    protected $fillable = [
        'jenis_kegiatan',
        'kode',
        'label',
        'aktif',
        'urutan',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
