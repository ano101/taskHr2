<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Validation\ValidationException;

class TaskService
{
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        if (
            $task->is_completed &&
            array_key_exists('title', $data) &&
            $data['title'] !== $task->title
        ) {
            throw ValidationException::withMessages([
                'title' => 'Нельзя менять title у выполненной задачи'
            ]);
        }

        $task->update($data);

        return $task->refresh();
    }

    public function complete(Task $task): Task
    {
        if ($task->is_completed) {
            throw ValidationException::withMessages([
                'task' => 'Задача уже выполнена'
            ]);
        }

        $task->update([
            'is_completed' => true
        ]);

        return $task->refresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function list(?bool $completed = null, int $perPage = 10)
    {
        $query = Task::query();

        if (!is_null($completed)) {
            $query->where('is_completed', $completed);
        }

        return $query
            ->latest()
            ->paginate($perPage);
    }
}
