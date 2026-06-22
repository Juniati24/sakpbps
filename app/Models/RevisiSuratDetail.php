<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisiSuratDetail extends Model
{
    protected $table = "revisi_surat_detail";

    protected $fillable = [
        'id_revisi',
        'field_revisi',
        'poin_revisi',
        'is_checked',
    ];

    public function revisiTerbaru() {
        return $this->belongsTo(RevisiSurat::class, 'id_revisi');
    }
}
