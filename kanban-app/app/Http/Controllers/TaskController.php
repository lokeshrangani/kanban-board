<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\DeleteTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->all()['user']->id;

        $tasks = Task::where(['created_by' => $user_id, 'deleted_at' => null])->get()->toArray() ?? [];

        if (count($tasks) > 0) {
            $todo = $this->filterTasks($tasks, 'todo');
            $inProgress = $this->filterTasks($tasks, 'inprogress');
            $done = $this->filterTasks($tasks, 'done');
            $tasks = ["todo" => array_values($todo), "inprogress" => array_values($inProgress), "done" => array_values($done)];
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Task fetch Successfully',
            'data' => [
                'task' => $tasks,
            ]
        ]);
    }

    /**
     * Filter tasks by status
     */
    public function filterTasks($tasks, $status)
    {
        return array_filter($tasks, function ($var) use ($status) {
            return ($var['status'] == $status);
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateTaskRequest $request)
    {
        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->created_by = $request->all()['user']->id;
        $task->save();

        return response()->json([
            'success'   => true,
            'message'   => 'Task Created Successfully',
            'data'      => ['id' => $task->id]
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request)
    {
        // update only if task is created by logged in user
        $task = Task::where([
            'id' => $request->task_id,
            'created_by' => $request->all()['user']->id,
            'deleted_at' => null
        ])->first();

        if ($task) {
            $task->status = $request->status;
            $task->save();
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Task Update Successfully',
            'data'      => ['id' => $request->task_id]
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteTaskRequest $request)
    {
        // delete only if task is created by logged in user
        $task = Task::where([
            'id' => $request->task_id,
            'created_by' => $request->all()['user']->id,
        ])->first();

        if ($task) {
            $task->deleted_at = date("Y-m-d H:i:s");
            $task->save();
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Task Deleted Successfully',
            'data'      => []
        ], 201);
    }
}
