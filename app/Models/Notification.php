<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends \Illuminate\Notifications\DatabaseNotification
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_read', 'title', 'message', 'action_url'];

    /**
     * Get the title attribute.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->data['title'] ?? 'Notifikasi Baru';
    }
    
    /**
     * Get the message attribute.
     *
     * @return string
     */
    public function getMessageAttribute()
    {
        return $this->data['message'] ?? '';
    }
    
    /**
     * Get the action URL attribute.
     *
     * @return string|null
     */
    public function getActionUrlAttribute()
    {
        return $this->data['action_url'] ?? null;
    }
    
    /**
     * Get the notification type.
     *
     * @return string
     */
    public function getNotificationTypeAttribute()
    {
        return $this->data['type'] ?? 'info';
    }
    
    /**
     * Get the is_read attribute.
     *
     * @return bool
     */
    public function getIsReadAttribute()
    {
        return $this->read();
    }

    /**
     * Scope a query to only include notifications for the authenticated user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $user = null)
    {
        $user = $user ?? Auth::user();
        return $query->where('notifiable_type', get_class($user))
                    ->where('notifiable_id', $user->id);
    }
}
