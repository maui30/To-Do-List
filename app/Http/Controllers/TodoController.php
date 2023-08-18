<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(){
        $tasks = Task::all();
        return view('tasks.index')->with('tasks', $tasks);
    }

    public function store(Request $request){
        $data = $request->validate([
            'task' => 'required'
        ]);

        Task::create($data);

        return redirect(route('tasks.index'));
    }

    public function update(Task $task, Request $request){
        $data = $request->validate([
            'task' => 'required'
        ]);

        $task->update($data);

        return redirect(route('tasks.index'));
    }

    public function delete(Task $task){
        $task->delete();
        return redirect(route('tasks.index'));
    }

    public function deleteAll(Task $task){
        Task::getQuery()->delete();
        return redirect(route('tasks.index'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->update([
            'done' => $request->input('done') ? 1 : 0,
        ]);

        return response()->json(['message' => 'Task status updated successfully']);
    }
}
