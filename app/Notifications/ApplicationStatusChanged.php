<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Application;

class ApplicationStatusChanged extends Notification
{
    use Queueable;

    protected $application;
    protected $status;

    public function __construct(Application $application, string $status)
    {
        $this->application = $application;
        $this->status = $status;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $job = $this->application->job;
        $company = $job->company ?? 'l\'entreprise';
        
        if ($this->status === 'accepted') {
            return (new MailMessage)
                ->subject('🎉 Votre candidature a été acceptée !')
                ->greeting('Félicitations !')
                ->line("Votre candidature pour le poste **{$job->title}** chez **{$company}** a été acceptée.")
                ->line('Le recruteur a été impressionné par votre profil et souhaite poursuivre le processus de recrutement avec vous.')
                ->line("**Détails de l'offre :**")
                ->line("- Poste : {$job->title}")
                ->line("- Entreprise : {$company}")
                ->line("- Localisation : {$job->location}")
                ->action('Voir mes candidatures', url('/candidate/applications'))
                ->line('Le recruteur vous contactera prochainement pour les prochaines étapes.')
                ->line('Bonne chance pour la suite du processus de recrutement !');
        } else {
            return (new MailMessage)
                ->subject('Information sur votre candidature')
                ->greeting('Bonjour,')
                ->line("Nous vous informons que votre candidature pour le poste **{$job->title}** chez **{$company}** n'a malheureusement pas été retenue.")
                ->line('Nous vous remercions pour l\'intérêt que vous portez à cette entreprise et pour le temps que vous avez consacré à votre candidature.')
                ->line("N'hésitez pas à consulter nos autres offres d'emploi qui pourraient correspondre à votre profil.")
                ->action('Voir les offres disponibles', url('/jobs'))
                ->line('Nous vous souhaitons bonne chance dans votre recherche d\'emploi !');
        }
    }

    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'job_title' => $this->application->job->title,
            'status' => $this->status,
        ];
    }
}
