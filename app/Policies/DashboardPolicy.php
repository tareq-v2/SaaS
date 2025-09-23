<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    public function viewSuperAdminDashboard(User $user)
    {
        return $user->isSuperAdmin();
    }

    public function viewAdminDashboard(User $user)
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    public function viewUserDashboard(User $user)
    {
        return true; // All authenticated users can access user dashboard
    }
}