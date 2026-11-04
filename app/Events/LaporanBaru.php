<?php

namespace App\Events;

use App\Models\Laporan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LaporanBaru implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Laporan $laporan)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard-admin'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id'          => $this->laporan->id,
            'nama_barang' => $this->laporan->nama_barang,
            'user_name'   => $this->laporan->user->name ?? 'User Dihapus',
            'status'      => $this->laporan->status,
            'created_at'  => $this->laporan->created_at->format('d M Y H:i'),
        ];
    }
}