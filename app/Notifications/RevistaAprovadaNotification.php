<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Revista;

class RevistaAprovadaNotification extends Notification
{
    use Queueable;

    public $revista;

    public function __construct(Revista $revista)
    {
        $this->revista = $revista;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('O seu trabalho foi Aprovado! 🎉')
                    ->greeting('Olá, ' . $notifiable->name . '!')
                    ->line('Temos o prazer de informar que o seu trabalho "' . $this->revista->titulo . '" foi aprovado e já se encontra publicado no Portal.')
                    ->action('Ver Artigo Publicado', route('revistas.show', $this->revista->id))
                    ->line('Obrigado por contribuir para a nossa comunidade científica!');
    }
}