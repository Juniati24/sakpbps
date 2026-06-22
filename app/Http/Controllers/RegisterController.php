<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Tampilkan halaman form pendaftaran staf.
     */
    public function showForm()
    {
        return view('register');
    }

    /**
     * Proses pendaftaran akun staf baru.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:30|unique:users,nip',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nip.unique' => 'NIP sudah terdaftar di sistem.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'password' => Hash::make($validated['password']),
            'role' => 'staf',   // selalu 'staf' untuk pendaftaran mandiri
            'no_telepon' => null,
        ]);

        return redirect('/')
            ->with('registered', true)
            ->with('registered_name', $validated['nama']);
    }
}
