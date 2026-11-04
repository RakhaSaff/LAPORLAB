<?php

namespace App\Events;

use App\Models\Laporan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusLaporanUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Laporan $laporan)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('laporan-user.' . $this->laporan->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'laporan_id' => $this->laporan->id,
            'status'     => $this->laporan->status,
        ];
    }
}