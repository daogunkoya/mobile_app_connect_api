<?php

namespace App\Services\Comment;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;

class CommentService
{
    public function fetchComments(Task $task): array
    {
      

       $comments =  optional($task->comments()->orderBy('created_at', 'desc')->get())->toArray();

        return $comments;
    }


    public function createComment(Task $task, string $body, User $user): Comment
    {
        $comment = new Comment();
        $comment->body = $body;
        $comment->user_id = $user->id;

        $task->comments()->save($comment);

        return $comment;
    }
}
