<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Comment $comment)
    {
        $this->comment->loadMissing(['post', 'user']);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $post = $this->comment->post;
        $commenter = $this->comment->user?->name ?? 'Someone';

        return (new MailMessage)
            ->subject('New comment on your post: '.$post->title)
            ->greeting('Hello '.$notifiable->name.',')
            ->line($commenter.' commented on your post.')
            ->line($this->excerpt($this->comment->content))
            ->action('View Post', route('blog.show', $post->slug))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'post_title' => $this->comment->post?->title,
            'post_slug' => $this->comment->post?->slug,
            'commenter_id' => $this->comment->user_id,
            'commenter_name' => $this->comment->user?->name,
            'content' => $this->excerpt($this->comment->content),
            'url' => route('blog.show', $this->comment->post?->slug),
        ];
    }

    private function excerpt(string $content): string
    {
        return str($content)->limit(150)->toString();
    }
}
