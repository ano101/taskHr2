<?php


namespace App\Services;

use App\Http\Requests\TaskRequest;
use App\Models\Task;

class TaskService

{
    public function create(array $data): Task
    {
        return Task::create($data);
    }
}
