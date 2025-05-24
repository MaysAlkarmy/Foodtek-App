<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $content;
    protected $via;

    public function __construct($t, $c, $v = ['mail'])
    {
        
        $this->title = $t;
        $this->content = $c;
        $this->via = $v;
        
    }

    
    public function via(object $notifiable): array
    {
       return $this->via;
    }

    public function toMail(object $notifiable): MailMessage
    {
         return (new MailMessage)
            ->subject($this->title)
            ->line($this->content);
    }

   
    public function toArray(object $notifiable): array
    {
       return [
        //'title' => $this->title . ' ' . $notifiable->name,  --> for send user name
            'title' => $this->title ,
            'content' => $this->content,
        ];
    }
}
