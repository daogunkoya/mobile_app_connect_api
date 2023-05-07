<?php

namespace App\Services\Task;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskServiceInterface
{
    public function getAllTasks(?string $status = '', ?string $priority = '', ?string $created_at = ''): array;

    public function createTask(array $data): array;

    public function getTaskById(string $id): ?Task;

    public function getById(string $id): ?array;

    public function updateTaskProgress(string $taskId, int $progress): int;

    public function updateTask(string $id, array $data): ?array;

    public function generateTaskReport(string $status = null, int $assignedTo = null, int $createdBy = null): array;

    public function assignUsers(Task $task, array $userIds);

    public function removeUsers(Task $task, array $userIds);

    public function deleteTask(string $id): bool;
   
    public function addTaskComment(): ?Task;
   
    public function updateProgress():bool;
    
    public function getReport(): ?Task;


}
