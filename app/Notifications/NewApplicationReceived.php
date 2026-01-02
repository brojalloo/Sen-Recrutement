<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Application;

class NewApplicationReceived extends Notification
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $candidate = $this->application->user;
        $job = $this->application->job;
        
        return (new MailMessage)
            ->subject('📩 Nouvelle candidature reçue')
            ->greeting('Bonjour,')
            ->line("Vous avez reçu une nouvelle candidature pour l'offre **{$job->title}**.")
            ->line("**Informations du candidat :**")
            ->line("- Nom : {$candidate->full_name}")
            ->line("- Email : {$candidate->email}")
            ->line("- Téléphone : " . ($candidate->phone ?? 'Non renseigné'))
            ->when($this->application->cover_letter, function($message) {
                return $message->line("**Lettre de motivation :**")
                    ->line(substr($this->application->cover_letter, 0, 200) . (strlen($this->application->cover_letter) > 200 ? '...' : ''));
            })
            ->action('Voir la candidature', url('/recruiter/dashboard'))
            ->line('Connectez-vous à votre tableau de bord pour consulter les détails complets et gérer cette candidature.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'job_title' => $this->application->job->title,
            'candidate_name' => $this->application->user->full_name,
        ];
    }
}
