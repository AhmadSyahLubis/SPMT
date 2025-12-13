<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Console\Command;

class TestNotification extends Command
{
    protected $signature = 'notification:test {user_id} {status}';
    protected $description = 'Test notification sending';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $status = $this->argument('status');

        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found!");
            return 1;
        }

        $application = $user->applications()->first();
        
        if (!$application) {
            $this->error("No application found for user {$user->id}");
            return 1;
        }

        $title = 'Status Magang Diperbarui';
        $message = "Status magang Anda telah diperbarui menjadi: " . ucfirst($status);
        $url = url("/applications/" . $application->id);

        try {
            $user->notify(new ApplicationStatusUpdated($application, $status, $title, $message, $url));
            $this->info("Notification sent successfully to user {$user->name} ({$user->email})");
            $this->info("Status: {$status}");
            $this->info("Message: {$message}");
            
            // Tampilkan notifikasi yang baru saja dibuat
            $notification = $user->notifications()->latest()->first();
            $this->info("\nNotification data in database:");
            $this->table(
                ['Field', 'Value'],
                [
                    ['ID', $notification->id],
                    ['Type', get_class($notification)],
                    ['Notifiable Type', $notification->notifiable_type],
                    ['Notifiable ID', $notification->notifiable_id],
                    ['Read At', $notification->read_at ?: 'Belum dibaca'],
                    ['Created At', $notification->created_at],
                    ['Data', json_encode($notification->data, JSON_PRETTY_PRINT)]
                ]
            );
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to send notification: " . $e->getMessage());
            if ($e->getPrevious()) {
                $this->error("Previous: " . $e->getPrevious()->getMessage());
            }
            return 1;
        }
    }
}
