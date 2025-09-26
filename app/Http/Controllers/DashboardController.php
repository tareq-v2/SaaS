<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Redirect based on user role
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    public function superAdminDashboard()
    {
        // Using Gate authorization
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        return view('dashboard.super_admin');
    }

    public function adminDashboard()
    {
        // Using Gate authorization
        if (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        return view('dashboard.admin');
    }

    public function userDashboard()
    {
        $user = auth()->user();
        
        // Get tasks assigned to this user
        $assignedTasks = $user->assignedTasks()
            ->with(['creator', 'assignedUsers'])
            ->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->get();

        // Get overdue tasks
        $overdueTasks = $assignedTasks->filter(function($task) {
            return $task->isOverdue();
        });

        // Get pending tasks (not completed)
        $pendingTasks = $assignedTasks->where('status', '!=', 'completed');

        // Get recent completed tasks
        $completedTasks = $assignedTasks->where('status', 'completed')->take(5);

        return view('dashboard.user', compact(
            'assignedTasks',
            'overdueTasks',
            'pendingTasks',
            'completedTasks'
        ));
    }
}