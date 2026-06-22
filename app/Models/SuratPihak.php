<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPihak extends Model
{
    protected $table = "surat_pihak";

    protected $fillable = [
        'id_surat',
        'nama',
        'jabatan',
        'tanggal_berlaku',
        'tanggal_berakhir',
        'tujuan_surat',
        'no_surat'
    ] ;

    // relasi dengan surat
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat');
    }
}
