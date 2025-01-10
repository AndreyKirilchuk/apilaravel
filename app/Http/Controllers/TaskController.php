<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class TaskController extends Controller
{
    public function index(Request $request, $project_id)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $tasks = Task::where("project_id" , $project_id);
        $total_items = $tasks->count();

        $tasks = $tasks->skip(($page - 1) * $limit)->take($limit)->get();

        $taskData = $tasks->map(function ($task) {
            return [
                "id" => $task->id,
                "title" => $task->title,
                "status" => $task->status,
                "due_date" => $task->due_date
            ];
        });

        return response()->json([
            "message" => "Status successfully updated",
            "data" => $taskData,
            "pagination" =>[
                "current_page" => $page,
                "total_pages" => ceil($total_items / $limit),
                "items_per_page" => 2,
                "total_items" => $total_items,
            ],
        ], 200);
    }

    public function create(Request $request)
    {
        $v = Validator::make($request->all(), [
            "project_id" => "required|integer",
            "title" => "required|string",
            "description" => "required|string",
            "assignee_id" => "required|integer",
            "due_date" => "required|date",
            "status" => "required|string",
        ]);

        if($v->fails())
        {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $v ->errors(),
            ], 422);
        }

        $task = Task::create([
            "title" => $request->title,
            "description" => $request->description,
            "assignee_id" => $request->assignee_id,
            "due_date" => $request->due_date,
            "status" => $request->status,
            "project_id" => $request->project_id
        ]);

        $user = User::find($request->assignee_id);

        return response()->json([
            "message" => "Task successfully created",
            "data" =>[
                "id" => $task->id,
                "title" => $task->title,
                "description" => $task->description,
                "assignee" => [
                    "id" => $user->id,
                    "name" => $user->name
                ],
            ],
            "due_date" => $task->due_date,
            "status" => $task->status
        ], 200);
    }

    public function update(Request $request, $task_id)
    {
        $v = Validator::make($request->all(), [
            "status" => "required|string",
        ]);

        if($v->fails())
        {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $v ->errors(),
            ], 422);
        }

        $task = Task::find($task_id);
        $task->status = request()->status;
        $task->save();

        return response()->json([
            "message" => "Status successfully updated",
            "data" =>[
                "id" => $task_id,
                "status" => "completed"
            ],
        ], 200);
    }
}
