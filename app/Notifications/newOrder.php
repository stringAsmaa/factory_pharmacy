<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class newOrder extends Notification
{
    use Queueable;
     private $message;
// public $id,$order_by,$title;
    /**
     * Create a new notification instance.
     */
     public function __construct($message)
     {
   $this->message=$message;



     }

    
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
       
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {

        return [
           
'message'=>$this->message,

        ];
    }


    // public function routeNotificationForMail(Notification $notification): array|string
    // {
    //     // Return email address only...
    //     return $this->database_addrress;
 
    //     // Return email address and name...
    //     return [$this->email_address => $this->name];
    // }



  
    }





