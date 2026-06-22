<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request, $role)
    {
        request()->validate([
            'nama' => 'required|string',
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('nip', $request->nip)
            ->where('role', $role)
            ->first();

        if (!$user) {
            return back()->withErrors(['message' => 'Nama atau NIP tidak ditemukan.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['message' => 'Password salah.']);
        }

        Auth::login($user);

        // Redirect berdasarkan role
        if ($user->role == 'staf') {
            return redirect('/dashboardstaf');
        } elseif ($user->role == 'admin') {
            return redirect('/dashboardadmin');
        } elseif ($user->role == 'pimpinan') {
            return redirect('/dashboardpimpinan');
        }
        return redirect('/'); // Default redirect jika role tidak dikenali
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
