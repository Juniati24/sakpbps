<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanUser extends Model
{
    protected $table = 'kegiatan_user';

    protected $fillable = [
        'id_user',
        'id_kegiatan',
        'id_surat',
        'jabatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat');
    }
}
