<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function readAll() {
        Notifikasi::where('id_user', auth()->id())->update(['is_read' => true]);
        return back();
    }
}
