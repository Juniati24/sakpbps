<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'jabatan' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'email_kantor'=> 'nullable|email',
            'no_telepon' => 'nullable|string|max:20',
            'foto'=> 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]); 

        $userId = Auth::id();
        if (!$userId) {
            return back()->withErrors(['message' => 'User tidak ditemukan.']);
        }

         $data = $request->only([
            'jabatan',
            'unit_kerja',
            'email_kantor',
            'no_telepon'
        ]);

         $profil = Profil::where('user_id', $userId)->first();

        // update foto 
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();

            // hapus foto lama jika ada
            if ($profil && $profil->foto) {
                $oldPath = public_path('foto_profil/' . $profil->foto);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file->move(public_path('foto_profil'), $filename);
            
            $data['foto'] = $filename;
        }

         Profil::updateOrCreate(
            ['user_id' => $userId],
            $data
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak benar.']);
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
