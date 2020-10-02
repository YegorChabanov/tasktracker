<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $sort = request()->get('sort');
        if ($sort) {
            $tasks = $this->getSortedTasks($sort);
        } else {
            $tasks = Task::all();
        }

        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $userId = Auth::id();

        $task = new Task();
        $task->title = $request->get('title');
        $task->description = $request->get('description');
        $task->status = $request->get('status');
        $task->user_id = $userId;
        $task->save();

        return new TaskResource($task);
    }

    public function show($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => "Task $id not found"], 404);
        }

        if ($task->user_id != Auth::id()) {
            return response()->json(['message' => "You can not see tasks of other users"], 403);
        }

        return new TaskResource($task);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255'
        ]);

        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => "Task $id not found"], 404);
        }

        if ($task->user_id != Auth::id()) {
            return response()->json(['message' => "You can not edit tasks of other users"], 403);
        }

        $task->title = $request->get('title');
        $task->description = $request->get('description');
        $task->save();

        return new TaskResource($task);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => "Task $id not found"], 404);
        }

        if ($task->user_id != Auth::id()) {
            return response()->json(['message' => "You can not delete tasks of other users"], 403);
        }

        $task->delete();

        return response()->json(['message' => "Task $id deleted successfully"], 201);
    }

    public function changeTaskStatus(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => "Task $id not found"], 404);
        }

        if ($task->user_id != Auth::id()) {
            return response()->json(['message' => "You can not change status in other user's tasks"], 403);
        }

        $request->validate([
            'status' => 'required|in:view,in_progress,done',
        ]);

        $task->status = $request->get('status');
        $task->save();

        return new TaskResource($task);
    }

    public function changeTaskUser(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => "Task $id not found"], 404);
        }

        if ($task->user_id != Auth::id()) {
            return response()->json(['message' => "You can not change user in other user's tasks"], 403);
        }

        $request->validate([
            'user_id' => 'required|integer|exists:App\User,id'
        ]);

        $task->user_id = $request->get('user_id');
        $task->save();

        return new TaskResource($task);
    }

    public function getSortedTasks($sort) {
        $tasks = [];
        switch ($sort) {
            case 'status':
                $tasks = Task::all()
                    ->sortBy('status');
                break;
            case 'recent-users':
                $tasks = Task::query()
                    ->join('users', 'users.id', '=', 'tasks.user_id')
                    ->select('tasks.*')
                    ->orderBy('users.created_at', 'DESC')->get();
                break;
            case 'latest-users':
                $tasks = Task::query()
                    ->join('users', 'users.id', '=', 'tasks.user_id')
                    ->select('tasks.*')
                    ->orderBy('users.created_at', 'ASC')->get();
                break;
            default:
                $tasks = Task::all();
        }

        return $tasks;
    }
}
