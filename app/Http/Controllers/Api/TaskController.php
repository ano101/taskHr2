<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $service
    ) {}

    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('completed')) {
            $query->where('is_completed', $request->boolean('completed'));
        }

        return TaskResource::collection(
            $query->latest()->paginate(10)
        );
    }

    public function store(TaskRequest $request)
    {
        $task = $this->service->create($request->validated());

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task = $this->service->update($task, $request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->service->delete($task);

        return response()->noContent();
    }

    public function complete(Task $task)
    {
        $task = $this->service->complete($task);

        return new TaskResource($task);
    }
}
