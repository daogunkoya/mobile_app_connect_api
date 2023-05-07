<?php

namespace App\Services\Task;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Task\TaskServiceInterface;
use Carbon\Carbon;

class TaskService implements TaskServiceInterface
{
    private Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }


     public function getAllTasks(?string $status = '', ?string $priority = '', ?string $created_at = ''): array

        {
            
            
            // Create a base query to fetch all tasks for the authenticated user
            $query = $this->task->where('user_id', Auth::id())
                ->with('assignedUsers')
                ->select('id as task_id', 'title', 'description', 'due_date', 'status', 'priority', 'created_at')
                ->orderBy('created_at', 'desc');
        
            // Apply filters if provided
            if (!empty($status)) {
                $query = $query->where('status', $status);
            }
            if (!empty($priority)) {
                $query = $query->where('priority', $priority);
            }
            if (!empty($created_at)) {
                //$created_at_dt = Carbon::createFromFormat('d/m/Y', $created_at);
                $created_at_dt = Carbon::createFromFormat('Y-m-d', $created_at);
                if ($created_at_dt) {
                    $query = $query->whereDate('due_date', $created_at_dt->format('Y-m-d'));
                }
            }
        
            // Execute the query and return the result
            $tasks = optional($query->get())->toArray();
            $count = count($tasks);
            return [
                'count' => $count,
                'tasks' => $tasks
            ];
        }
        
    

    public function getTaskById(string $id): ?Task
    {
        return optional($this->task->where('user_id', Auth::id())
                            ->select('id as task_id', 'title', 'description', 'due_date', 'status', 'priority','created_at')
                            ->with('comments')
                            ->get())->toArray();
    }

    public function getById(string $id): ?array
    {
        return optional($this->task->where('user_id', Auth::id())
                        ->select('id as task_id', 'title', 'description', 'due_date', 'status','priority','created_at')
                    ->with('comments')
                    ->find($id))->toArray();
    }

    public function createTask(array $data): array
    {
        $data['user_id'] = Auth::id();

        $date = Carbon::createFromFormat('d/m/Y', $data['due_date']);
        $formattedDate = $date->format('Y-m-d H:i:s');
        $data['due_date'] = $formattedDate;

        return $this->task->create($data)->toArray();
    }

    public function updateTask(string $id, array $data): ?array
    {
        $task = $this->task->where('user_id', Auth::id())->find($id);

        $date = Carbon::createFromFormat('d/m/Y', $data['due_date']);
        $formattedDate = $date->format('Y-m-d H:i:s');
        $data['due_date'] = $formattedDate;

        if (!$task) return null;


        $task->update($data);

        return $task->toArray();
    }

    public function deleteTask(string $id): bool
    {
        $task = $this->task->where('user_id', Auth::id())->find($id);

        if (!$task) {
            return false;
        }

        $task->delete();

        return true;
    }


    public function addTaskComment(): ?Task
    {
        return null;
    }


    public function updateProgress(): bool
    {
        return true;
    }


    public function getReport(): ?Task
    {
        return null;
    }


    public function updateTaskProgress(string $taskId, int $progress): int
    {
        $task = Task::findOrFail($taskId);
        $task->progress = $progress;
        $task->save();

        return $task->progress;
    }

    public function generateTaskReport(string $status = null, int $assignedTo = null, int $createdBy = null): array
    {
        $query = Task::query();

        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter by assigned user
        if ($assignedTo) {
            $query->where('assigned_to', $assignedTo);
        }

        // Filter by created user
        if ($createdBy) {
            $query->where('created_by', $createdBy);
        }

        // Get the total count of tasks
        $totalCount = $query->count();

        // Group the tasks by status
        $groupedTasks = $query->groupBy('status')->select('status', DB::raw('count(*) as count'))->get();

        // Format the report data
        $report = [
            'total_count' => $totalCount,
            'grouped_tasks' => $groupedTasks->map(function ($group) {
                return [
                    'status' => $group->status,
                    'count' => $group->count,
                ];
            })->toArray(),
        ];

        return $report;
    }




    //assign task to users
    public function assignUsers(Task $task, array $userIds)
    {
        $task->users()->sync($userIds);
        return;
    }


    //remove task from user
    public function removeUsers(Task $task, array $userIds)
    {
        $task->users()->detach($userIds);
        return;
    }
}
