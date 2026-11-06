<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Edicao;

class StatusSubmissoAtualizadoNotification extends Notification
{
    use Queueable;

    public $revista;

    public function __construct(Edicao $revista)
    {
        $this->revista = $revista;
    }

    /**
     * Define os canais de notificação: E-mail e Base de Dados (para o Sininho).
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Conteúdo enviado por E-mail.
     */
    public function toMail($notifiable): MailMessage
    {
        $status = ucfirst($this->revista->status);
        $titulo = $this->revista->titulo;
        $url = route('revistas.show', $this->revista->id);

        $message = (new MailMessage)
            ->subject("Atualização do trabalho: {$titulo}")
            ->greeting("Olá, {$notifiable->name}!")
            ->line("O seu trabalho intitulado **\"{$titulo}\"** mudou de estado.")
            ->line("Estado atual: **{$status}**");

        if ($this->revista->status === 'rejeitado' && !empty($this->revista->motivo_rejeicao)) {
            $message->line("Motivo da rejeição: " . $this->revista->motivo_rejeicao);
        } elseif ($this->revista->status === 'aprovado') {
            $message->line("Parabéns! O seu trabalho foi aprovado e já pode descarregar o Certificado de Publicação.");
        }

        return $message
            ->action('Ver Trabalho', $url)
            ->line('Obrigado por utilizar o Portal FTC Dondo!');
    }

    /**
     * Conteúdo guardado na Base de Dados (para aparecer no Sininho).
     */
    public function toArray($notifiable): array
    {
        return [
            'revista_id' => $this->revista->id,
            'titulo'     => $this->revista->titulo,
            'status'     => $this->revista->status,
            'mensagem'   => "O seu trabalho \"{$this->revista->titulo}\" foi " . $this->revista->status . ".",
            'url'        => route('revistas.show', $this->revista->id),
        ];
    }
}