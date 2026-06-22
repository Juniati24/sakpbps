<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;

class RegisterMitraController extends Controller
{
    /**
     * Daftar 14 kecamatan di Kabupaten Maros.
     */
    private array $kecamatanMaros = [
        'Bantimurung',
        'Bontoa',
        'Cenrana',
        'Camba',
        'Lau',
        'Mallawa',
        'Mandai',
        'Maros Baru',
        'Marusu',
        'Moncongloe',
        'Simbang',
        'Tanralili',
        'Tompobulu',
        'Turikale',
    ];

    /**
     * Tampilkan halaman form pendaftaran mitra.
     */
    public function showForm()
    {
        return view('register-mitra', [
            'kecamatanList' => $this->kecamatanMaros,
        ]);
    }

    /**
     * Proses pendaftaran mitra baru.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kecamatan' => 'required|string|in:' . implode(',', $this->kecamatanMaros),
        ], [
            'kecamatan.in' => 'Kecamatan yang dipilih tidak valid.',
        ]);

        Mitra::create($validated);

        return redirect('/')
            ->with('mitra_registered', true)
            ->with('mitra_registered_name', $validated['nama']);
    }
}
