<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Task::query();

        if(request()->has('completed')){
            $query->where('is_completed', request('completed'));
        }

        return TaskResource::collection(
            $query->latest()->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request, TaskService $taskService)
    {
//        $task = Task::create($request->validated());

        $task = $taskService->create($request->validated());

        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
         return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        if($task->is_completed && $request->has('title')){
            return response()->json([
                'message' => 'Если is_completed = true, то нельзя менять title',
                422
            ]);
        }

        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }

    public function complete(Task $task){
        if($task->is_completed){
            return response()->json([
                'message' => 'Задача уже выполнена',
                422
            ]);
        }

        $task->update(['is_completed' => true]);

        return new TaskResource($task);
    }
}
