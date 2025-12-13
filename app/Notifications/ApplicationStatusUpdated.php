<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Application;

class ApplicationStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;
    protected $status;
    protected $title;
    protected $message;
    protected $url;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Application  $application
     * @param  string  $status
     * @param  string  $title
     * @param  string  $message
     * @param  string  $url
     * @return void
     */
    public function __construct(Application $application, $status, $title, $message, $url = null)
    {
        $this->application = $application;
        $this->status = $status;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url ?? route('applications.show', $application->id);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->title)
            ->line($this->message)
            ->action('Lihat Detail', $this->url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'type' => 'application_updated',
            'application_id' => $this->application->id,
            'status' => $this->status,
            'internship_title' => $this->application->internship ? $this->application->internship->title : 'Magang',
        ];
    }
}
