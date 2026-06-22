<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Kegiatan extends Model
{
    protected $table = "kegiatan";
    protected $fillable = [
        'id_pengusul',
        'nama_kegiatan',
        'kode_kegiatan',
        'klasifikasi_kegiatan',
        'jenis_kegiatan',
        'prioritas',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
        'target',
        'lokasi',
        'anggaran',
        'sumber_dana',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengusul');
    }

    /**
     * Relasi ke surat-surat yang terkait kegiatan ini
     */
    public function surat()
    {
        return $this->hasMany(Surat::class, 'id_kegiatan');
    }

    public function getProgressAttribute()
    {
        $map = [
            'pending' => 10,
            'aktif' => 60,
            'selesai' => 100
        ];

        return $map[$this->status] ?? 0;
    }
}
