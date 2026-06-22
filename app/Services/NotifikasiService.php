<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;
use App\Events\NotifikasiEvent;
use Carbon\Carbon;

class NotifikasiService
{
    // =====================================================
    // FORMAT NOMOR
    // =====================================================
    private static function formatNomor($nomor)
    {
        return preg_replace('/^0/', '62', $nomor);
    }

    // =====================================================
    // DEADLINE
    // =====================================================
    private static function getDeadline($kegiatan)
    {
        if (!$kegiatan || !$kegiatan->tanggal_selesai) {
            return null;
        }

        return Carbon::parse($kegiatan->tanggal_selesai)
            ->locale('id')
            ->addDays(7)
            ->translatedFormat('d F Y');
    }

    // =====================================================
// LINK SISTEM
// =====================================================
    private static function getLinkSistem()
    {
        return "https://domain-kamu.com";
        // nanti ganti saat hosting
    }

    // =====================================================
// FORMAT PESAN WHATSAPP
// =====================================================
    private static function wa($text)
    {
        $lines = explode("\n", $text);

        $lines = array_map(function ($line) {
            return trim($line);
        }, $lines);

        return trim(implode("\n", $lines));
    }

    // =====================================================
    // SIMPAN NOTIFIKASI
    // =====================================================
    private static function simpanNotifikasi(
        $idUser,
        $judul,
        $pesan,
        $tipe,
        $status
    ) {

        $notif = Notifikasi::create([
            'id_user' => $idUser,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'status' => $status,
            'is_read' => false,
        ]);

        event(new NotifikasiEvent(
            $notif->judul,
            $notif->pesan,
            $notif->id_user
        ));
    }

    // =====================================================
    // KIRIM WA
    // =====================================================
    private static function kirimWhatsapp($nomor, $pesan)
    {
        if (!$nomor || !$pesan) {
            return;
        }

        $nomor = self::formatNomor($nomor);

        WhatsappService::send($nomor, $pesan);
    }

    // =====================================================
    // GET USER BERDASARKAN ROLE
    // =====================================================
    private static function getUsersByRoles(array $roles)
    {
        return User::with('profil')
            ->whereIn('role', $roles)
            ->get();
    }

    // =====================================================
    // AMBIL NOMOR HP USER
    // =====================================================
    private static function getNoHp($user)
    {
        // staf -> ambil dari profil
        if ($user->role === 'staf') {

            return $user->profil->no_telepon
                ?? $user->no_telepon
                ?? null;
        }

        // admin & pimpinan
        return $user->no_telepon ?? null;
    }

    // =====================================================
    // KIRIM KE USER TERTENTU
    // =====================================================
    private static function kirimKeUser(
        $user,
        string $judul,
        string $pesan,
        string $tipe,
        string $status,
        ?string $wa = null
    ) {

        if (!$user) {
            return;
        }

        // =====================
        // SIMPAN NOTIF
        // =====================
        self::simpanNotifikasi(
            $user->id,
            $judul,
            $pesan,
            $tipe,
            $status
        );

        // =====================
        // WHATSAPP
        // =====================
        $noHp = self::getNoHp($user);

        self::kirimWhatsapp(
            $noHp,
            $wa
        );
    }

    // =====================================================
    // KIRIM KE ROLE
    // =====================================================
    private static function kirimKeRole(
        array $roles,
        array $templateRole,
        string $tipe,
        string $status
    ) {

        $users = self::getUsersByRoles($roles);

        foreach ($users as $user) {

            $role = $user->role;

            if (!isset($templateRole[$role])) {
                continue;
            }

            $data = $templateRole[$role];

            // =====================
            // SIMPAN NOTIF
            // =====================
            self::simpanNotifikasi(
                $user->id,
                $data['judul'],
                $data['pesan'],
                $tipe,
                $status
            );

            // =====================
            // WHATSAPP
            // =====================
            $noHp = self::getNoHp($user);

            self::kirimWhatsapp(
                $noHp,
                $data['wa'] ?? null
            );
        }
    }

    // =====================================================
    // NOTIF SURAT
    // =====================================================
    public static function surat($surat, $status)
    {
        switch ($status) {

            // =========================================
            // VERIFIKASI ADMIN
            // =========================================
            case 'verifikasi_admin':

                // STAFF PEMILIK
                self::kirimKeUser(
                    $surat->user,
                    'Pengajuan Surat Diterima',
                    "Pengajuan surat \"{$surat->perihal}\" sedang diverifikasi admin.",
                    'surat',
                    'verifikasi_admin',
                    self::wa(
                        "📄 *PENGAJUAN SURAT DITERIMA*

                        Assalamu'alaikum Wr. Wb.

                        Yth. {$surat->user->nama},

                        Pengajuan surat yang Anda buat telah berhasil diterima oleh Sistem Administrasi Persuratan BPS dan saat ini sedang dalam proses verifikasi oleh Admin.

                        📌 *Perihal*
                        {$surat->perihal}

                        📌 *Status*
                        Menunggu Verifikasi Admin

                        Mohon menunggu proses pemeriksaan dokumen. Notifikasi akan kembali dikirim apabila terdapat revisi atau surat telah diteruskan ke pimpinan.

                        🔗 *Akses Sistem*
                        " . self::getLinkSistem() . "

                        Terima kasih atas perhatian dan kerja samanya.

                        Wassalamu'alaikum Wr. Wb.
                        _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                        "
                    )
                );

                // ADMIN
                self::kirimKeRole(
                    ['admin'],
                    [
                        'admin' => [
                            'judul' => 'Surat Baru Masuk',
                            'pesan' => "Terdapat pengajuan surat baru \"{$surat->perihal}\".",
                            'wa' => self::wa(
                                "📥 *NOTIFIKASI SURAT BARU MASUK*

                                    Assalamu'alaikum Wr. Wb.

                                    Terdapat pengajuan surat baru yang memerlukan proses verifikasi.

                                    📌 *Perihal*
                                    {$surat->perihal}

                                    👤 *Pemohon*
                                    {$surat->user->nama}

                                    Mohon segera melakukan pemeriksaan kelengkapan dokumen dan validasi data agar proses persuratan dapat berjalan sesuai standar pelayanan.

                                    🔗 *Buka Sistem:*
                                    " . self::getLinkSistem() . "

                                    Terima kasih.

                                    Wassalamu'alaikum Wr. Wb.
                                    _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                                    "
                            )
                        ]
                    ],
                    'surat',
                    'verifikasi_admin'
                );

                break;

            // =========================================
            // REVISI
            // =========================================
            case 'revisi':

                self::kirimKeUser(
                    $surat->user,
                    'Revisi Surat',
                    "Surat \"{$surat->perihal}\" perlu direvisi.",
                    'surat',
                    'revisi',
                    self::wa(
                        "⚠️ *SURAT MEMERLUKAN REVISI*

                        Assalamu'alaikum Wr. Wb.

                        Yth. {$surat->user->nama},

                        Berdasarkan hasil pemeriksaan Admin, surat yang Anda ajukan memerlukan perbaikan sebelum dapat diproses lebih lanjut.

                        📌 *Perihal*
                        {$surat->perihal}

                        Mohon segera melakukan revisi sesuai catatan yang tersedia pada sistem agar proses persetujuan dapat dilanjutkan.

                        🔗 *Akses Sistem*:
                        " . self::getLinkSistem() . "

                        Terima kasih atas kerja samanya.

                        Wassalamu'alaikum Wr. Wb.
                        _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                    "
                    )
                );

                break;

            // =========================================
            // DI PIMPINAN
            // =========================================
            case 'di_pimpinan':

                self::kirimKeUser(
                    $surat->user,
                    'Surat Diproses Pimpinan',
                    "Surat \"{$surat->perihal}\" sedang menunggu tanda tangan pimpinan.",
                    'surat',
                    'di_pimpinan',
                    self::wa(
                        "🖊️ *SURAT DALAM PROSES PERSETUJUAN PIMPINAN*

                            Assalamu'alaikum Wr. Wb.

                            Yth. {$surat->user->nama},

                            Surat yang Anda ajukan telah selesai diverifikasi oleh Admin dan saat ini sedang dalam proses persetujuan serta penandatanganan oleh pimpinan.

                            📄 *Nomor Surat*:
                            {$surat->no_surat}

                            📌 *Perihal*:
                            {$surat->perihal}

                            Mohon menunggu pemberitahuan selanjutnya melalui sistem.

                            🔗 *Akses Sistem*:
                            " . self::getLinkSistem() . "

                            Terima kasih.

                            Wassalamu'alaikum Wr. Wb.
                            _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                            "
                    )
                );

                // PIMPINAN
                self::kirimKeRole(
                    ['pimpinan'],
                    [
                        'pimpinan' => [
                            'judul' => 'Surat Baru Masuk untuk Ditandatangani',
                            'pesan' => "Terdapat pengajuan surat baru \"{$surat->perihal}\"." . " Mohon melakukan penandatanganan.",
                            'wa' => self::wa(
                                "📥 *NOTIFIKASI SURAT BARU MASUK*

                                    Assalamu'alaikum Wr. Wb.

                                    Yth. Pimpinan,

                                    Terdapat pengajuan surat baru yang memerlukan proses penandatanganan. Mohon segera melakukan pemeriksaan dan penandatanganan agar proses persuratan dapat berjalan sesuai standar pelayanan. 

                                    📌 *Perihal*
                                    {$surat->perihal}

                                    📄 *Nomor Surat*:
                                    {$surat->no_surat}

                                    🔗 *Buka Sistem:*
                                    " . self::getLinkSistem() . "

                                    Terima kasih.

                                    Wassalamu'alaikum Wr. Wb.
                                    _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                                    "
                            )
                        ]
                    ],
                    'surat',
                    'di_pimpinan'
                );

                break;

            // =========================================
            // SELESAI
            // =========================================
            case 'selesai':

                // STAFF PEMILIK
                self::kirimKeUser(
                    $surat->user,
                    'Surat Anda Selesai',
                    "Surat \"{$surat->perihal}\" telah selesai diproses.",
                    'surat',
                    'selesai',
                    self::wa(
                        "✅ *SURAT TELAH SELESAI DIPROSES*

                            Assalamu'alaikum Wr. Wb.

                            Yth. {$surat->user->nama},

                            Surat yang Anda ajukan telah selesai diproses dan telah mendapatkan persetujuan sesuai prosedur yang berlaku.

                            📄 *Nomor Surat*:
                            {$surat->no_surat}

                            📌 *Perihal*:
                            {$surat->perihal}

                            Dokumen surat sudah dapat diakses dan diunduh melalui sistem.

                            🔗 *Akses Sistem*:
                            " . self::getLinkSistem() . "

                            Terima kasih telah menggunakan Sistem Administrasi Persuratan BPS.

                            Wassalamu'alaikum Wr. Wb.
                            _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                            "
                    )
                );

                // ADMIN
                self::kirimKeRole(
                    ['admin'],
                    [
                        'admin' => [
                            'judul' => 'Surat Selesai',
                            'pesan' => "Surat \"{$surat->perihal}\" telah selesai diproses.",
                            'wa' => self::wa(
                                "✅ *SURAT TELAH SELESAI DIPROSES*

                                    Assalamu'alaikum Wr. Wb.

                                    Informasi bahwa surat berikut telah selesai diproses dan ditandatangani.

                                    📄 *Nomor Surat*:
                                    {$surat->no_surat}

                                    📌 *Perihal*:
                                    {$surat->perihal}

                                    Dokumen telah tersedia pada arsip digital sistem dan siap digunakan sesuai kebutuhan.

                                    🔗 *Akses Sistem*:
                                    " . self::getLinkSistem() . "

                                    Terima kasih.

                                    Wassalamu'alaikum Wr. Wb.
                                    _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                                    "
                            )
                        ]
                    ],
                    'surat',
                    'selesai'
                );

                break;

             // =========================================
            // DITOLAK PIMPINAN
            // =========================================

            case 'ditolak':

                self::kirimKeUser(
                    $surat->user,
                    'Surat Ditolak Pimpinan',
                    "Surat \"{$surat->perihal}\" ditolak oleh pimpinan.",
                    'surat',
                    'ditolak',
                    self::wa("
            ❌ *SURAT DITOLAK PIMPINAN*

            Assalamu'alaikum Wr. Wb.

            Yth. {$surat->user->nama},

            Surat yang Anda ajukan telah ditinjau oleh pimpinan dan
            tidak disetujui untuk diteruskan.

            📄 *Nomor Surat*
            {$surat->no_surat}

            📌 *Perihal*
            {$surat->perihal}

            📝 *Alasan*
            {$surat->alasan_tolak}

            Mohon koordinasikan dengan admin persuratan untuk
            langkah selanjutnya.

            🔗 *Akses Sistem*
            " . self::getLinkSistem() . "

            Wassalamu'alaikum Wr. Wb.
            _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
        ")
                );

                // ADMIN juga diberi tahu
                self::kirimKeRole(
                    ['admin'],
                    [
                        'admin' => [
                            'judul' => 'Surat Ditolak Pimpinan',
                            'pesan' => "Surat \"{$surat->perihal}\" ditolak pimpinan.",
                            'wa' => self::wa("
                    ❌ *SURAT DITOLAK PIMPINAN*

                    Yth. Admin,

                    Surat berikut ditolak oleh pimpinan dan perlu ditindaklanjuti.

                    📄 *Nomor Surat*
                    {$surat->no_surat}

                    📌 *Perihal*
                    {$surat->perihal}

                    👤 *Pemohon*
                    {$surat->user->nama}

                    📝 *Alasan*
                    {$surat->alasan_tolak}

                    🔗 *Buka Sistem*
                    " . self::getLinkSistem() . "

                    _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                ")
                        ]
                    ],
                    'surat',
                    'ditolak'
                );

                break;
        }
    }

    // =====================================================
// KOREKSI NOMOR SURAT
// =====================================================
    public static function koreksiNomorSurat(
        $surat,
        $nomorLama,
        $nomorBaru,
        $alasan
    ) {

        // ======================================
        // STAFF PEMILIK SURAT
        // ======================================
        self::kirimKeUser(
            $surat->user,
            'Nomor Surat Dikoreksi',
            "Nomor surat telah dikoreksi dari {$nomorLama} menjadi {$nomorBaru}.",
            'surat',
            'koreksi_nomor',
            self::wa("
            📄 *KOREKSI NOMOR SURAT*

            Assalamu'alaikum Wr. Wb.

            Yth. {$surat->user->nama},

            Nomor surat yang sebelumnya telah diterbitkan mengalami koreksi administrasi.

            📌 *Perihal*
            {$surat->perihal}

            📄 *Nomor Lama*
            {$nomorLama}

            ✅ *Nomor Baru*
            {$nomorBaru}

            📝 *Alasan Koreksi*
            {$alasan}

            Mohon menggunakan nomor surat terbaru untuk seluruh kebutuhan administrasi dan arsip.

            🔗 *Akses Sistem*
            " . self::getLinkSistem() . "

            Terima kasih.

            Wassalamu'alaikum Wr. Wb.
            _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
        ")
        );

        // ======================================
        // PIMPINAN (JIKA MASIH MENUNGGU TTD)
        // ======================================
        if ($surat->status === 'di_pimpinan') {

            self::kirimKeRole(
                ['pimpinan'],
                [
                    'pimpinan' => [
                        'judul' => 'Nomor Surat Dikoreksi',
                        'pesan' =>
                            "Nomor surat yang menunggu tanda tangan telah dikoreksi.",
                        'wa' => self::wa("
                        🖊️ *PEMBERITAHUAN KOREKSI NOMOR SURAT*

                        Assalamu'alaikum Wr. Wb.

                        Yth. Pimpinan,

                        Terdapat perubahan nomor surat yang saat ini berada pada tahap persetujuan pimpinan.

                        📄 Nomor Lama
                        {$nomorLama}

                        ✅ Nomor Baru
                        {$nomorBaru}

                        📌 Perihal
                        {$surat->perihal}

                        Mohon menggunakan nomor terbaru pada proses penandatanganan.

                        Wassalamu'alaikum Wr. Wb.
                        _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                    ")
                    ]
                ],
                'surat',
                'koreksi_nomor'
            );
        }
    }

    // =====================================================
// NOTIF PENUGASAN (ST/SK)
// =====================================================
    public static function penugasan($userPihak, $surat)
    {
        $jenis = strtoupper($surat->jenis_surat);

        // Ambil nama kegiatan — prioritas: kegiatan relasi → manual → perihal
        $namaKegiatan = $surat->kegiatan?->nama_kegiatan
            ?? $surat->kegiatan_manual
            ?? $surat->perihal;

        self::kirimKeUser(
            $userPihak,
            "Anda Ditugaskan dalam {$jenis}",
            "Anda tercantum sebagai pihak dalam {$jenis} kegiatan \"{$namaKegiatan}\". Surat sedang diproses admin.",
            'surat',
            'aktif',
            self::wa("
            📋 *PEMBERITAHUAN PENUGASAN*

            Assalamu'alaikum Wr. Wb.

            Yth. {$userPihak->nama},

            Anda telah ditetapkan sebagai petugas dalam kegiatan berikut melalui {$jenis} yang sedang diproses.

            📌 *Kegiatan*
            {$namaKegiatan}

            📄 *Perihal Surat*
            {$surat->perihal}

            👤 *Pengaju*
            {$surat->user->nama}

            Surat saat ini sedang dalam proses verifikasi admin. Kegiatan akan muncul pada dashboard Anda setelah surat disetujui.

            🔗 *Akses Sistem*
            " . self::getLinkSistem() . "

            Terima kasih atas perhatian dan kerja samanya.

            Wassalamu'alaikum Wr. Wb.
            _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
        ")
        );
    }

    // =====================================================
    // NOTIF KEGIATAN
    // =====================================================
    public static function kegiatan($kegiatan, $status)
    {
        switch ($status) {

            // =========================================
            // PENDING
            // =========================================
            case 'pending':

                self::kirimKeRole(
                    ['pimpinan'],
                    [
                        'pimpinan' => [
                            'judul' => 'Persetujuan Kegiatan',
                            'pesan' => "Kegiatan \"{$kegiatan->nama_kegiatan}\" menunggu persetujuan pimpinan.",
                            'wa' => self::wa(
                                "🖊️ *PERSETUJUAN KEGIATAN*

                                    Assalamu'alaikum Wr. Wb.

                                    Yth. Pimpinan,

                                    Terdapat pengajuan kegiatan baru yang memerlukan persetujuan Anda untuk dapat dilaksanakan.

                                    📌 *Nama Kegiatan*
                                    {$kegiatan->nama_kegiatan}

                                    👤 *Pengaju*
                                    {$kegiatan->user->nama}

                                    Mohon melakukan review terhadap rencana kegiatan, tujuan, serta jadwal pelaksanaan yang telah diajukan agar proses administrasi dapat berjalan sesuai ketentuan.

                                    🔗 *Buka Sistem*
                                    " . self::getLinkSistem() . "

                                    Terima kasih atas perhatian dan kerja samanya.

                                    Wassalamu'alaikum Wr. Wb.
                                    _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                            "
                            )
                        ],
                    ],
                    'kegiatan',
                    'pending'
                );

                break;

            // =========================================
            // AKTIF
            // =========================================
            case 'aktif':

                self::kirimKeRole(
                    ['pimpinan'],
                    [
                        'pimpinan' => [
                            'judul' => 'Kegiatan Disetujui',
                            'pesan' => "Kegiatan \"{$kegiatan->nama_kegiatan}\" telah aktif.",
                            'wa' => self::wa("
                                ✅ *KEGIATAN DISETUJUI*

                                Assalamu'alaikum Wr. Wb.

                                Yth. Pimpinan,

                                Informasi bahwa kegiatan berikut telah berhasil diaktifkan dalam sistem.

                                📌 *Nama Kegiatan*
                                {$kegiatan->nama_kegiatan}

                                Status kegiatan saat ini adalah aktif dan dapat dilaksanakan sesuai jadwal yang telah ditetapkan.

                                🔗 *Buka Sistem*
                                " . self::getLinkSistem() . "

                                Terima kasih.

                                Wassalamu'alaikum Wr. Wb.
                                _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                            ")
                        ],
                    ],
                    'kegiatan',
                    'aktif'
                );

                break;

            // =========================================
            // SELESAI
            // =========================================
            case 'selesai':

                self::kirimKeRole(
                    ['pimpinan'],
                    [
                        'pimpinan' => [
                            'judul' => 'Laporan Kegiatan',
                            'pesan' => "Kegiatan \"{$kegiatan->nama_kegiatan}\" telah selesai dilaksanakan.",
                            'wa' => self::wa("
                                📊 *LAPORAN KEGIATAN SELESAI*

                                Assalamu'alaikum Wr. Wb.

                                Yth. Pimpinan,

                                Kegiatan berikut telah selesai dilaksanakan.

                                📌 *Nama Kegiatan*
                                {$kegiatan->nama_kegiatan}

                                Silakan melakukan peninjauan terhadap laporan hasil kegiatan dan capaian yang telah dilaksanakan sebagai bahan evaluasi.

                                🔗 *Buka Sistem*
                                " . self::getLinkSistem() . "

                                Terima kasih.

                                Wassalamu'alaikum Wr. Wb.
                                _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                            ")
                        ],
                    ],
                    'kegiatan',
                    'selesai'
                );

                break;
        }
    }

    // =====================================================
    // NOTIF REVISI
    // =====================================================
    public static function revisi($revisi, $status)
    {
        switch ($status) {

            // =========================================
            // PENDING
            // =========================================
            case 'pending':

                self::kirimKeUser(
                    $revisi->surat->user,
                    'Surat Perlu Revisi',
                    "Surat \"{$revisi->surat->perihal}\" perlu diperbaiki sesuai catatan admin.",
                    'revisi',
                    'pending',
                    self::wa("
                        ⚠️ *SURAT MEMERLUKAN REVISI*

                        Assalamu'alaikum Wr. Wb.

                        Yth. {$revisi->surat->user->nama},

                        Berdasarkan hasil pemeriksaan administrasi, surat yang Anda ajukan memerlukan perbaikan sebelum dapat diproses lebih lanjut.

                        📄 *Nomor Surat*
                        {$revisi->surat->no_surat}

                        📌 *Perihal*
                        {$revisi->surat->perihal}

                        Mohon segera melakukan revisi sesuai catatan yang telah diberikan pada sistem agar proses persetujuan dapat dilanjutkan.

                        Apabila revisi tidak segera dilakukan, proses penerbitan surat dapat mengalami keterlambatan.

                        🔗 Akses Sistem:
                        " . self::getLinkSistem() . "

                        Terima kasih atas perhatian dan kerja samanya.

                        Wassalamu'alaikum Wr. Wb.
                        _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                    ")
                );

                break;

            // =========================================
            // DIPERBAIKI
            // =========================================
            case 'diperbaiki':

                self::kirimKeRole(
                    ['admin'],
                    [
                        'admin' => [
                            'judul' => 'Revisi Diperbaiki',
                            'pesan' => "Revisi telah diperbaiki oleh staf.",
                            'wa' => self::wa("
                                🛠️ *REVISI TELAH DIPERBAIKI*

                                Assalamu'alaikum Wr. Wb.

                                Yth. Admin,

                                Staf telah melakukan perbaikan terhadap surat yang sebelumnya memerlukan revisi.

                                📌 *Perihal*
                                {$revisi->surat->perihal}

                                Mohon melakukan pemeriksaan ulang terhadap dokumen yang telah diperbarui untuk menentukan proses selanjutnya.

                                🔗 *Buka Sistem*
                                " . self::getLinkSistem() . "

                                Terima kasih.

                                Wassalamu'alaikum Wr. Wb.
                                _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                            ")
                        ]
                    ],
                    'revisi',
                    'diperbaiki'
                );

                break;

            // =========================================
            // SELESAI
            // =========================================
            case 'selesai':

                self::kirimKeUser(
                    $revisi->surat->user,
                    'Revisi Disetujui',
                    'Revisi telah disetujui admin.',
                    'revisi',
                    'selesai',
                    self::wa("
                        ✅ *REVISI DISETUJUI*

                        Assalamu'alaikum Wr. Wb.

                        Yth. {$revisi->surat->user->nama},

                        Perbaikan yang Anda lakukan telah diperiksa dan disetujui oleh Admin.

                        📄 *Nomor Surat*
                        {$revisi->surat->no_surat}

                        📌 *Perihal*
                        {$revisi->surat->perihal}

                        Surat akan kembali diproses sesuai tahapan administrasi yang berlaku.

                        Notifikasi berikutnya akan dikirimkan apabila terdapat perubahan status pada surat tersebut.

                        🔗 *Akses Sistem*
                        " . self::getLinkSistem() . "

                        Terima kasih atas kerja samanya.

                        Wassalamu'alaikum Wr. Wb.
                        _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                    ")
                );

                break;
        }
    }

    // =====================================================
    // NOTIF LAPORAN
    // =====================================================
    public static function laporan($laporan, $status)
    {
        $deadline = self::getDeadline($laporan->kegiatan);

        switch ($status) {

            // =========================================
            // DRAFT
            // =========================================
            case 'draft':

                self::kirimKeUser(
                    $laporan->user,
                    'Draft Laporan',
                    "Draft laporan {$laporan->periode_laporan} telah disimpan."
                    . ($deadline ? " Deadline {$deadline}." : ""),
                    'laporan',
                    'draft',
                    self::wa("
                        📝 *DRAFT LAPORAN TERSIMPAN*

                        Assalamu'alaikum Wr. Wb.

                        Yth. {$laporan->user->nama},

                        Draft laporan kegiatan telah berhasil disimpan ke dalam sistem.

                        📌 *Periode Laporan*
                        {$laporan->periode_laporan}

                        📋 *Kegiatan*
                        {$laporan->kegiatan->nama_kegiatan}

                        " . ($deadline ? "⏰ *Deadline Pengumpulan*
                        {$deadline}

                        " : "") . "

                        Silakan melengkapi data laporan sebelum dikirim untuk proses verifikasi.

                        🔗 *Akses Sistem*
                        " . self::getLinkSistem() . "

                        Terima kasih atas perhatian dan kerja samanya.

                        Wassalamu'alaikum Wr. Wb.
                        _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                    ")
                );

                break;

            // =========================================
            // DIKIRIM
            // =========================================
            case 'dikirim':

                // STAFF
                self::kirimKeUser(
                    $laporan->user,
                    'Laporan Berhasil Dikirim',
                    "Laporan {$laporan->periode_laporan} berhasil dikirim.",
                    'laporan',
                    'dikirim',
                    self::wa("
                        📤 *LAPORAN BERHASIL DIKIRIM*

                        Assalamu'alaikum Wr. Wb.

                        Yth. {$laporan->user->nama},

                        Laporan kegiatan telah berhasil dikirim dan saat ini sedang menunggu proses pemeriksaan oleh Admin.

                        📌 *Periode Laporan*
                        {$laporan->periode_laporan}

                        📋 *Kegiatan*
                        {$laporan->kegiatan->nama_kegiatan}

                        Mohon menunggu proses verifikasi. Informasi selanjutnya akan dikirimkan melalui sistem.

                        🔗 *Akses Sistem*
                        " . self::getLinkSistem() . "

                        Terima kasih.

                        Wassalamu'alaikum Wr. Wb.
                        _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                    ")
                );

                // ADMIN
                self::kirimKeRole(
                    ['admin'],
                    [
                        'admin' => [
                            'judul' => 'Laporan Baru',
                            'pesan' => "Laporan {$laporan->periode_laporan} telah dikirim.",
                            'wa' => self::wa("
                                📥 *LAPORAN BARU MASUK*

                                Assalamu'alaikum Wr. Wb.

                                Yth. Admin,

                                Terdapat laporan kegiatan baru yang memerlukan proses pemeriksaan dan verifikasi.

                                📌 *Periode Laporan*
                                {$laporan->periode_laporan}

                                📋 *Kegiatan*
                                {$laporan->kegiatan->nama_kegiatan}

                                👤 *Pengirim*
                                {$laporan->user->nama}

                                Mohon melakukan pemeriksaan terhadap isi laporan dan dokumen pendukung yang telah diunggah.

                                🔗 *Buka Sistem*
                                " . self::getLinkSistem() . "

                                Terima kasih.

                                Wassalamu'alaikum Wr. Wb.
                                _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                            ")
                        ]
                    ],
                    'laporan',
                    'dikirim'
                );

                break;

            // =========================================
            // DISETUJUI
            // =========================================
            case 'disetujui':

                self::kirimKeUser(
                    $laporan->user,
                    'Laporan Disetujui',
                    "Laporan {$laporan->periode_laporan} telah disetujui.",
                    'laporan',
                    'disetujui',
                    self::wa("
                        ✅ *LAPORAN DISETUJUI*

                        Assalamu'alaikum Wr. Wb.

                        Yth. {$laporan->user->nama},

                        Laporan kegiatan yang telah Anda kirim berhasil diperiksa dan disetujui.

                        📌 *Periode Laporan*
                        {$laporan->periode_laporan}

                        📋 *Kegiatan*
                        {$laporan->kegiatan->nama_kegiatan}

                        Terima kasih atas kelengkapan dan ketepatan waktu dalam penyampaian laporan kegiatan.

                        🔗 *Akses Sistem*
                        " . self::getLinkSistem() . "

                        Semoga kegiatan yang telah dilaksanakan dapat memberikan manfaat dan mendukung pencapaian target organisasi.

                        Wassalamu'alaikum Wr. Wb.
                        _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                    ")
                );

                break;
        }
    }

    // =====================================================
    // REMINDER LAPORAN
    // =====================================================
    public static function reminderLaporan($user, $kegiatan)
    {
        $noHp = self::getNoHp($user);

        if (!$noHp) {
            return;
        }

        $deadline = self::getDeadline($kegiatan);

        $pesan = self::wa("
                ⏰ *PENGINGAT PENGIRIMAN LAPORAN*

                Assalamu'alaikum Wr. Wb.

                Yth. {$user->nama},

                Berdasarkan data pada Sistem Administrasi Kegiatan BPS, laporan kegiatan berikut belum dikirimkan dan masih menunggu penyelesaian dari pihak pelaksana.

                📋 *Nama Kegiatan*
                {$kegiatan->nama_kegiatan}

                " . ($deadline ? "📅 *Batas Pengiriman*
                {$deadline}\n\n" : "") . "

                Mohon segera melengkapi dan mengirimkan laporan kegiatan sebelum batas waktu yang ditentukan agar proses administrasi dan pelaporan dapat berjalan dengan baik.

                🔗 *Akses Sistem*
                " . self::getLinkSistem() . "

                Apabila laporan telah dikirimkan, mohon abaikan pesan ini.

                Terima kasih atas perhatian dan kerja samanya.

                Wassalamu'alaikum Wr. Wb.
                _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
        ");

        self::kirimWhatsapp($noHp, $pesan);
    }

    // =====================================================
// REMINDER REVISI TERLAMBAT
// =====================================================
    public static function reminderRevisiTerlambat($revisi)
    {
        if (!$revisi || !$revisi->surat) {
            return;
        }

        self::kirimKeUser(
            $revisi->surat->user,
            'Revisi Melewati Deadline',
            'Revisi surat Anda telah melewati batas waktu perbaikan.',
            'revisi',
            'terlambat',
            self::wa("
                ⏰ *PENGINGAT REVISI SURAT*

                Assalamu'alaikum Wr. Wb.

                Yth. {$revisi->surat->user->nama},

                Sistem mendeteksi bahwa surat yang memerlukan revisi masih belum diperbaiki hingga melewati batas waktu yang telah ditentukan.

                📄 *Perihal Surat*
                {$revisi->surat->perihal}

                Status Saat Ini:
                Belum dilakukan perbaikan.

                Mohon segera melakukan perbaikan sesuai catatan revisi yang diberikan agar proses persuratan dapat dilanjutkan.

                ⚠️ Keterlambatan perbaikan dapat menyebabkan proses penerbitan surat tertunda.

                🔗 *Akses Sistem*
                " . self::getLinkSistem() . "

                Terima kasih atas perhatian dan kerja samanya.

                Wassalamu'alaikum Wr. Wb.
                _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
            ")
        );

        self::kirimKeRole(
            ['admin'],
            [
                'admin' => [
                    'judul' => 'Revisi Melewati Deadline',
                    'pesan' =>
                        "Surat \"{$revisi->surat->perihal}\" belum diperbaiki staf hingga melewati deadline revisi.",
                    'wa' => self::wa("
                        ⏰ *PEMBERITAHUAN REVISI MELEWATI BATAS WAKTU*

                        Assalamu'alaikum Wr. Wb.

                        Yth. Admin,

                        Sistem mendeteksi bahwa revisi surat berikut belum diperbaiki oleh pemohon hingga melewati batas waktu yang telah ditentukan.

                        📄 *Perihal Surat*
                        {$revisi->surat->perihal}

                        👤 *Pemohon*
                        {$revisi->surat->user->nama}

                        Status saat ini:
                        Belum dilakukan perbaikan oleh pemohon.

                        Mohon untuk melakukan tindak lanjut atau koordinasi dengan pihak terkait agar proses persuratan dapat segera diselesaikan.

                        🔗 *Akses Sistem*
                        " . self::getLinkSistem() . "

                        Terima kasih.

                        Wassalamu'alaikum Wr. Wb.
                        _Sistem Administrasi Kegiatan dan Persuratan BPS Kabupaten Maros_
                    ")
                ]
            ],
            'revisi',
            'terlambat'
        );
    }
}