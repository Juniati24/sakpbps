<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisiSurat extends Model
{
    protected $table = "revisi_surat";

    protected $fillable = [
        'id_surat',
        'catatan_admin',
        'deadline',
        'status',
    ];

    public function surat() {
        return $this->belongsTo(Surat::class, 'id_surat');
    }

    public function detail() {
        return $this->hasMany(RevisiSuratDetail::class, 'id_revisi');
    }
}
