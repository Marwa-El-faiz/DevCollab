<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentAdded extends Notification
{
    use Queueable;

    public function __construct(public Comment $comment) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau commentaire — ' . $this->comment->task->title)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line($this->comment->user->name . ' a commenté sur une de tes tâches.')
            ->line('**Tâche :** ' . $this->comment->task->title)
            ->line('**Commentaire :** ' . $this->comment->body)
            ->action('Voir la tâche', url('/projects/' . $this->comment->task->project_id))
            ->line('Merci d\'utiliser DevCollab !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'comment_added',
            'comment_id' => $this->comment->id,
            'task_id'    => $this->comment->task_id,
            'task_title' => $this->comment->task->title,
            'project_id' => $this->comment->task->project_id,
            'from'       => $this->comment->user->name,
            'message'    => $this->comment->user->name . ' a commenté sur : ' . $this->comment->task->title,
        ];
    }
}