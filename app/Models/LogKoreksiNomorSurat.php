<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogKoreksiNomorSurat extends Model
{
    protected $table = 'log_koreksi_nomor_surat';

    protected $fillable = [
        'surat_id',
        'nomor_lama',
        'nomor_baru',
        'alasan',
        'user_id'
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
