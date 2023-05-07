<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
//use App\Services\Task\TaskService;
use App\Services\Task\TaskServiceInterface;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use App\Models\Task;

class TaskController extends Controller
{
    private TaskServiceInterface $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
            $status = $request->input('status')??NULL;
            $assigned_to = $request->input('priority')??NULL;
            $created_at = $request->input('deadline')??NULL;
    
        $tasks = $this->taskService->getAllTasks($status, $assigned_to, $created_at);

        return response()->json($tasks);
    }

    public function show(Request $request, string $id)
    {
        $task = $this->taskService->getById($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'required|date_format:d/m/Y',
        ]);

        $task = $this->taskService->createTask($validatedData);

        return response()->json($task, 201);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'string',
            'description' => 'nullable|string',
            'due_date' => 'required|date_format:d/m/Y',
        ]);

        $task = $this->taskService->updateTask($id, $validatedData);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    public function destroy(Request $request, string $id)
    {
        $deleted = $this->taskService->deleteTask($id);

        if (!$deleted) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json(['message' => 'Task deleted']);
    }


    public function updateProgress(Request $request, $id)
    {

        // $validatedData = $request->validate([
        //     'progress' => 'required|min:1|max:100',
        // ]);
        $progress = $this->taskService->updateTaskProgress($id, $request->input('progress'));

        return response()->json(['progress' => $progress]);
    }

    public function getReport(Request $request)
    {
        $report = $this->taskService->generateTaskReport(
            $request->input('status'),
            $request->input('assigned_to'),
            $request->input('created_by')
        );

        return response()->json(['report' => $report]);
    }


    public function assignUsers(Request $request, Task $task)
    {
        $userIds = $request->input('user_ids');
        $this->taskService->assignUsers($task, $userIds);
        return response()->json(['message' => 'Users assigned to task successfully']);
    }


    //remove user from task
    public function removeUsers(Request $request, Task $task)
    {
        $userIds = $request->input('user_ids');
        $this->taskService->removeUsers($task, $userIds);
        return response()->json(['message' => 'Users removed from task successfully']);
    }
}
