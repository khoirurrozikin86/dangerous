<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class PeminjamanUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

        /** 
     * Create a new event instance.
     */
    public $itemCount;
    public $employeeCount;
    public $peminjamanCount;
    public $itemOutCount;

    public function __construct($itemCount, $employeeCount, $peminjamanCount, $itemOutCount)
    {
        $this->itemCount = $itemCount;
        $this->employeeCount = $employeeCount;
        $this->peminjamanCount = $peminjamanCount;
        $this->itemOutCount = $itemOutCount;
    }

    public function broadcastOn()
    {
        return ['peminjaman-channel'];
    }   

    public function broadcastAs()
    {
        return 'peminjaman-updated';
    }
}
