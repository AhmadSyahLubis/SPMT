<?php

namespace App\Events;

use App\Models\Application;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InternshipStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $application;

    /**
     * Create a new event instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('application.' . $this->application->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'internship.status.updated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'status' => $this->application->status_magang,
            'status_label' => $this->getStatusLabel($this->application->status_magang),
            'started_at' => $this->application->started_at ? $this->application->started_at->format('d M Y H:i') : null,
        ];
    }

    /**
     * Get the label for the status.
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'menunggu' => 'Menunggu',
            'diterima' => 'Diterima',
            'in_progress' => 'Sedang Berjalan',
            'completed' => 'Selesai',
            'ditolak' => 'Ditolak'
        ];

        return $labels[$status] ?? 'Tidak Diketahui';
    }
}
