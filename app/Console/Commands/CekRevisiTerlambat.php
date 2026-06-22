<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RevisiSurat;
use App\Services\NotifikasiService;

class CekRevisiTerlambat extends Command
{
    protected $signature = 'revisi:cek-terlambat';

    protected $description = 'Cek revisi yang melewati deadline';

    public function handle()
    {
        $revisiTerlambat = RevisiSurat::with('surat')
            ->where('status', 'pending')
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->get();

        foreach ($revisiTerlambat as $revisi) {

            NotifikasiService::reminderRevisiTerlambat($revisi);
        }

        $this->info('Cek revisi terlambat selesai.');
    }
}