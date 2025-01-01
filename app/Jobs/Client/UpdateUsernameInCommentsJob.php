<?php

namespace App\Jobs\Client;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\Comments\Models\Comment;

class UpdateUsernameInCommentsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(protected User $user)
    {}

    public function handle(): void
    {
        $comments = Comment::whereLike('original_text', "%data-mention=\"{$this->user->id}\"%")->get();

        $comments->each(function($comment) {
            $comment->save();
        });
    }
}
