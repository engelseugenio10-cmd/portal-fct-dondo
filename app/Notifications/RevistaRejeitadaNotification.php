<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Revista;

class RevistaRejeitadaNotification extends Notification
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
                    ->subject('Atualização sobre o seu trabalho submetido')
                    ->greeting('Olá, ' . $notifiable->name . '.')
                    ->line('Informamos que a sua submissão do trabalho "' . $this->revista->titulo . '" necessita de correções ou foi rejeitada.')
                    ->line('**Motivo / Parecer do Administrador:**')
                    ->line('"' . $this->revista->motivo_rejeicao . '"')
                    ->action('Aceder ao Painel de Submissões', route('estudante.dashboard'))
                    ->line('Pode efetuar as correções necessárias e resubmeter o ficheiro no seu painel.');
    }
}