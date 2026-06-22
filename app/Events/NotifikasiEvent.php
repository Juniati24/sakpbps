<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifikasiEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $judul;
    public $pesan;
    public $userId;

    public function __construct($judul, $pesan, $userId)
    {
        $this->judul = $judul;
        $this->pesan = $pesan;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('notifikasi.' . $this->userId)
        ];
    }

    public function broadcastAs()
    {
        return 'notifikasi-event';
    }
}