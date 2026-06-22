<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $table = "profil";
    protected $fillable = [
        'user_id',
        'jabatan',
        'unit_kerja',
        'email_kantor',
        'no_telepon',
        'foto',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
