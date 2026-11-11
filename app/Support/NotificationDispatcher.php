<?php

namespace App\Support;

use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Envoie une notification sans faire échouer l'action métier en cours.
 *
 * Une candidature ne doit pas être perdue parce que le serveur mail tousse.
 * Mais l'échec doit laisser une trace : le code précédent l'avalait dans un
 * `catch` vide, si bien qu'un SMTP en panne était totalement invisible — le
 * recruteur n'était pas prévenu, et personne ne pouvait le savoir.
 *
 * Retourne `true` si l'envoi est parti, `false` sinon, pour que l'appelant
 * cesse d'annoncer un email qui n'est jamais parti.
 */
class NotificationDispatcher
{
    public function __construct(private Dispatcher $notifications) {}

    /**
     * @param  object  $notifiable  destinataire de la notification
     */
    public function send(object $notifiable, Notification $notification): bool
    {
        try {
            $this->notifications->send($notifiable, $notification);

            return true;
        } catch (Throwable $e) {
            Log::error('Échec de l\'envoi d\'une notification', [
                'notification' => $notification::class,
                'notifiable_id' => $notifiable->id ?? null,
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
