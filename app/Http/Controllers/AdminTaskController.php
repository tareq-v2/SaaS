<?php


namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTaskController extends Controller
{
    public function tasks()
    {
        $tasks = Task::with(['creator', 'assignedUsers'])
            ->latest()
            ->paginate(10);

        $users = User::where('id', '!=', Auth::id())->get();
        return view('admin.tasks.index', compact('tasks', 'users'));
    }

    public function index()
    {
        $tasks = Task::with(['creator', 'assignedUsers'])
                    ->latest()
                    ->paginate(10);

        $users = User::where('id', '!=', Auth::id())->get();

        return view('admin.tasks.index', compact('tasks', 'users'));
    }

    public function store(Request $request)
    {
        $assignedUsers = [];
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'assigned_users' => 'required|string',
            'assigned_users.*' => 'exists:users,id'
        ]);

        $assignedUsers = explode(',', $request->assigned_users);   

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'created_by' => Auth::id(),
            'status' => 'pending'
        ]);

        $task->assignedUsers()->sync($assignedUsers);

        return redirect()->route('admin.tasks')->with('success', 'Task created successfully!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $task->update(['status' => $request->status]);

        return back()->with('success', 'Task status updated!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Task deleted successfully!');
    }
}
