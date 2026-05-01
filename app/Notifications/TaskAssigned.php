<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(' Nouvelle tâche assignée — ' . $this->task->title)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Une nouvelle tâche t\'a été assignée dans DevCollab.')
            ->line('**Tâche :** ' . $this->task->title)
            ->line('**Projet :** ' . $this->task->project->name)
            ->line('**Priorité :** ' . ucfirst($this->task->priority))
            ->action('Voir la tâche', url('/projects/' . $this->task->project_id))
            ->line('Merci d\'utiliser DevCollab !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'task_assigned',
            'task_id'    => $this->task->id,
            'task_title' => $this->task->title,
            'project_id' => $this->task->project_id,
            'project'    => $this->task->project->name,
            'message'    => 'Tâche assignée : ' . $this->task->title,
        ];
    }
}