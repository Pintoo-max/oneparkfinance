<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Throwable;

class TaskController extends Controller
{
    public function index()
    {
        try {
            $tasks = Task::latest()->get();

            return TaskResource::collection($tasks);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to fetch tasks',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function store(TaskRequest $request)
    {
        try {
            $task = Task::create($request->validated());

            return response()->json([
                'message' => 'Task created successfully',
                'data'    => new TaskResource($task),
            ], 201);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Task creation failed',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function update(TaskRequest $request, Task $task)
    {
        try {
            $task->update($request->validated());

            return response()->json([
                'message' => 'Task updated successfully',
                'data'    => new TaskResource($task),
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Task update failed',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $task->delete();

            return response()->json([
                'message' => 'Task deleted successfully'
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Task deletion failed',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function updateStatus(Request $request, Task $task)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,completed',
            ]);

            $task->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'message' => 'Status updated successfully',
                'data'    => new TaskResource($task),
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Status update failed',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}