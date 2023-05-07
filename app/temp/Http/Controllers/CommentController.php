<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Services\Comment\CommentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function index(Request $request,CommentService $commentService, Task $task)
    {
      
        $comment = $commentService->fetchComments($task);

        return response()->json($comment);
    }

    public function store(Request $request, CommentService $commentService, Task $task): Comment
    {
        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);

        $comment = $commentService->createComment(
            $task,
            $validatedData['body'],
            $request->user()
        );

        return $comment;
    }
}
