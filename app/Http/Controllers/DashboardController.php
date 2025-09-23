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
        // All authenticated users can access
        return view('dashboard.user');
    }
}