<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KlasifikasiKegiatan;

class KlasifikasiKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Survei' => [
                ['Perencanaan Survei', 'VS.020'],
                ['Persiapan Survei', 'VS.100'],
                ['Pelatihan Instruktur', 'VS.210'],
                ['Pelatihan Petugas', 'VS.220'],
                ['Pelaksanaan Lapangan', 'VS.300'],
                ['Pengumpulan Data', 'VS.330'],
                ['Pengawasan Lapangan', 'VS.350'],
                ['Monitoring Kualitas', 'VS.360'],
                ['Pengolahan Data', 'VS.400'],
                ['Analisis dan Penyajian Hasil', 'VS.500'],
                ['Diseminasi Hasil Survei', 'VS.600'],
            ],
            'Sensus' => [
                ['Perencanaan Sensus', 'SS.020'],
                ['Persiapan Sensus', 'SS.100'],
                ['Pelatihan Instruktur', 'SS.210'],
                ['Pelatihan Petugas', 'SS.220'],
                ['Pelaksanaan Lapangan Sensus', 'SS.300'],
                ['Pengumpulan Data Sensus', 'SS.330'],
                ['Pengawasan Lapangan Sensus', 'SS.350'],
                ['Pengolahan Sensus', 'SS.400'],
                ['Analisis dan Penyajian Sensus', 'SS.500'],
                ['Diseminasi Hasil Sensus', 'SS.600'],
            ],
            'Pelatihan' => [
                ['Pelatihan Instruktur', 'VS.210'],
                ['Pelatihan Petugas Lapangan', 'VS.220'],
                ['Bimbingan Teknis', 'DL.200'],
                ['Diklat Kepemimpinan', 'DL.220'],
                ['Diklat Teknis', 'DL.230'],
                ['Diklat Fungsional', 'DL.240'],
            ],
            'Pendataan' => [
                ['Pengumpulan Data Lapangan', 'VS.330'],
                ['Pemeriksaan Data', 'VS.340'],
                ['Pengolahan Data', 'VS.400'],
                ['Tabulasi Data', 'VS.440'],
            ],
            'Rapat/Koordinasi' => [
                ['Koordinasi Internal / Eksternal', 'VS.190'],
                ['Rapat Teknis', 'HM.300'],
                ['Hubungan Antar Lembaga', 'HM.310'],
                ['Kerjasama dengan Perguruan Tinggi / PKL', 'HM.340'],
            ],
            'Lainnya' => [
                ['Administrasi Umum', 'KA.100'],
                ['Pengurusan Surat Masuk/Keluar', 'KA.110'],
                ['Kearsipan', 'KA.200'],
                ['Kepegawaian - Surat Tugas', 'KP.650'],
                ['Perjalanan Dinas', 'KU.340'],
            ],
        ];

        foreach ($data as $jenis => $items) {
            foreach ($items as $i => [$label, $kode]) {
                KlasifikasiKegiatan::create([
                    'jenis_kegiatan' => $jenis,
                    'kode'           => $kode,
                    'label'          => $label,
                    'urutan'         => $i,
                    'aktif'          => true,
                ]);
            }
        }
    }
}
