<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        auth()->user()->tasks()->create($request->all());

        return redirect()->back()->with('success', 'Tâche ajoutée !');
    }

    public function toggle(\App\Models\Task $task)
    {
        $this->authorize('update', $task);
        $task->update(['is_completed' => !$task->is_completed]);
        return redirect()->back();
    }

    public function destroy(\App\Models\Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->back()->with('success', 'Tâche supprimée.');
    }
}
