<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AddStockOUTNotification extends Notification
{
  use Queueable;
  protected $user;


  public function __construct($user)
  {
      $this->user=$user;
  }


  public function via($notifiable)
  {
      return ['mail'];
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
      ->subject('Nouvelle Sortie de Stock')
      ->line('Bienvenue dans GolfMaroc-APP votre application de gestion des points de vente du groupe GolfMaroc .')
      ->line('Nous vous informons qu\'une nouvelle sortie de stock a été effectué au magasin '. \App\Models\Magasin::getLibelle($this->user->id_magasin))
      ->action('Vous pouvez acceder a votre application ici ', url('http://golfmaroc-001-site1.itempurl.com/public/home'))
      ->line('Merci d\'avoir utilisé notre application' );
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
          //
      ];
  }
}
