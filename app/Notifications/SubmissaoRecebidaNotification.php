<?php

namespace App\Notifications;

use App\Models\Edicao;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissaoRecebidaNotification extends Notification
{
    use Queueable;

    public Edicao $edicao;

    public function __construct(Edicao $edicao)
    {
        $this->edicao = $edicao;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmação de Submissão - Portal FCT Dondo')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('O seu trabalho "' . $this->edicao->titulo . '" foi recebido com sucesso no Portal FCT Dondo.')
            ->line('Estado Atual: Pendente de Moderação.')
            ->action('Verificar no Painel', route('estudante.dashboard'))
            ->line('Obrigado por contribuir para o repositório académico!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo'    => 'Submissão Recebida',
            'mensagem'  => 'O seu trabalho "' . $this->edicao->titulo . '" foi submetido com sucesso.',
            'edicao_id' => $this->edicao->id,
            'link'      => route('estudante.dashboard'),
        ];
    }
}