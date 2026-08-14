<?php

namespace Tests\Support;

use Illuminate\Mail\SentMessage;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Notification;
use RuntimeException;

/**
 * Canal mail qui échoue systématiquement, pour simuler un SMTP en panne.
 *
 * Une vraie classe plutôt qu'un mock : le test vérifie ainsi le comportement
 * réel du code d'envoi, et non celui d'un double configuré pour lui donner
 * raison.
 */
class FailingMailChannel extends MailChannel
{
    public function __construct()
    {
        // Le canal réel exige un Mailer et un moteur Markdown. Celui-ci
        // n'envoie jamais rien, il n'en a besoin d'aucun.
    }

    /**
     * @param  mixed  $notifiable
     */
    public function send($notifiable, Notification $notification): ?SentMessage
    {
        throw new RuntimeException('Connection to SMTP server failed');
    }
}
