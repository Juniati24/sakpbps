<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = "surat";

    protected $fillable = [
        'id_user',
        'id_kegiatan',
        'kegiatan_manual',

        // identitas surat
        'no_surat',
        'mode_nomor',
        'jenis_surat',
        'perihal',
        'tujuan_surat',

        // isi surat
        'lampiran',
        'isi_surat',
        'tembusan',
        'referensi_surat',

        // waktu
        'tanggal_ajuan',
        'tanggal_berlaku',
        'tanggal_berakhir',

        // tambahan
        'tingkat_urgensi',
        'dasar_hukum',
        'catatan_admin',
        'catatan_pimpinan',

        // file
        'file_surat_draft',
        'file_surat_revisi',
        'file_surat_final',

        // status
        'status',

        // tanda tangan
        'approved_by',
        'tanggal_ttd',
        'qr_token',
        'file_qr',
        'mode_ttd',
        'alasan_tolak',
        'tanggal_tolak',
        'hash_verifikasi',
    ];

    protected $casts = [
        'tanggal_ajuan' => 'datetime',
        'tanggal_berlaku' => 'datetime',
        'tanggal_berakhir' => 'datetime',
        'tanggal_ttd' => 'datetime',
        'tanggal_tolak' => 'datetime',
    ];

    /**
     * Relasi ke pihak surat
     */
    public function pihak()
    {
        return $this->hasMany(SuratPihak::class, 'id_surat');
    }

    /**
     * Relasi ke user pemohon
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relasi ke kegiatan
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    /**
     * Revisi terbaru
     */
    public function revisiTerbaru()
    {
        return $this->hasOne(RevisiSurat::class, 'id_surat')->latest();
    }

    // Relasi ke user approver
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}