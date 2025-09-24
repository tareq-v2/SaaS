@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3 col-lg-2 px-0">
            <div class="bg-dark min-vh-100">
                <div class="p-3">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-crown me-2"></i>Super Admin
                    </h5>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link text-white active bg-primary" href="#" id="dashboard-link">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    
                    <!-- User Management -->
                    <div class="nav-item">
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#userManagement" role="button" aria-expanded="false">
                            <i class="fas fa-users me-2"></i>User Management
                            <i class="fas fa-chevron-down float-end mt-1"></i>
                        </a>
                        <div class="collapse" id="userManagement">
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-list me-2"></i>All Users
                            </a>
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-user-plus me-2"></i>Add User
                            </a>
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-user-tag me-2"></i>User Roles
                            </a>
                        </div>
                    </div>
                    
                    <!-- Content Management -->
                    <div class="nav-item">
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#contentManagement" role="button" aria-expanded="false">
                            <i class="fas fa-edit me-2"></i>Content
                            <i class="fas fa-chevron-down float-end mt-1"></i>
                        </a>
                        <div class="collapse" id="contentManagement">
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-newspaper me-2"></i>Posts
                            </a>
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-tags me-2"></i>Categories
                            </a>
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-images me-2"></i>Media Library
                            </a>
                        </div>
                    </div>
                    
                    <!-- System -->
                    <div class="nav-item">
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#systemManagement" role="button" aria-expanded="false">
                            <i class="fas fa-cogs me-2"></i>System
                            <i class="fas fa-chevron-down float-end mt-1"></i>
                        </a>
                        <div class="collapse" id="systemManagement">
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-sliders-h me-2"></i>Settings
                            </a>
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-database me-2"></i>Backup
                            </a>
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-file-alt me-2"></i>System Logs
                            </a>
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-tools me-2"></i>Maintenance
                            </a>
                        </div>
                    </div>
                    
                    <!-- Reports -->
                    <a class="nav-link text-white" href="#">
                        <i class="fas fa-chart-bar me-2"></i>Reports
                    </a>
                    
                    <!-- Analytics -->
                    <a class="nav-link text-white" href="#">
                        <i class="fas fa-analytics me-2"></i>Analytics
                    </a>
                    
                    <!-- Security -->
                    <div class="nav-item">
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#securityManagement" role="button" aria-expanded="false">
                            <i class="fas fa-shield-alt me-2"></i>Security
                            <i class="fas fa-chevron-down float-end mt-1"></i>
                        </a>
                        <div class="collapse" id="securityManagement">
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-key me-2"></i>Permissions
                            </a>
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-history me-2"></i>Audit Trail
                            </a>
                            <a class="nav-link text-light ps-4" href="#">
                                <i class="fas fa-users-cog me-2"></i>Active Sessions
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="col-md-9 col-lg-10">
            <!-- Top Header -->
            <div class="bg-white shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center p-3">
                    <div>
                        <h4 class="text-danger mb-0">
                            <i class="fas fa-tachometer-alt me-2"></i>Super Admin Dashboard
                        </h4>
                        <small class="text-muted">Welcome back, {{ Auth::user()->name }}!</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown me-3">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span class="badge bg-danger">3</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-info-circle me-2"></i>New user registered</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-exclamation-triangle me-2"></i>System update available</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-chart-line me-2"></i>Traffic spike detected</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-danger btn-sm" onclick="toggleFullscreen()">
                            <i class="fas fa-expand" id="fullscreen-icon"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="px-3">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm stats-card" style="background: linear-gradient(45deg, #007bff, #0056b3);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1 opacity-75">Total Users</h6>
                                        <h2 class="mb-0" id="total-users">{{ \App\Models\User::count() }}</h2>
                                        <small class="opacity-75">
                                            <i class="fas fa-arrow-up me-1"></i>+12% this month
                                        </small>
                                    </div>
                                    <div class="opacity-75">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm stats-card" style="background: linear-gradient(45deg, #28a745, #1e7e34);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1 opacity-75">Active Admins</h6>
                                        <h2 class="mb-0" id="admin-count">{{ \App\Models\User::where('role', 'admin')->count() }}</h2>
                                        <small class="opacity-75">
                                            <i class="fas fa-check me-1"></i>All active
                                        </small>
                                    </div>
                                    <div class="opacity-75">
                                        <i class="fas fa-user-shield fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm stats-card" style="background: linear-gradient(45deg, #ffc107, #d39e00);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1 opacity-75">Regular Users</h6>
                                        <h2 class="mb-0" id="user-count">{{ \App\Models\User::where('role', 'user')->count() }}</h2>
                                        <small class="opacity-75">
                                            <i class="fas fa-arrow-up me-1"></i>+8% this week
                                        </small>
                                    </div>
                                    <div class="opacity-75">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm stats-card" style="background: linear-gradient(45deg, #17a2b8, #117a8b);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1 opacity-75">System Health</h6>
                                        <h2 class="mb-0">98%</h2>
                                        <small class="opacity-75">
                                            <i class="fas fa-heartbeat me-1"></i>Excellent
                                        </small>
                                    </div>
                                    <div class="opacity-75">
                                        <i class="fas fa-server fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0">
                                    <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <button class="btn btn-outline-primary w-100 quick-action-btn" onclick="quickAction('add-user')">
                                            <i class="fas fa-user-plus fa-2x mb-2 d-block"></i>
                                            Add User
                                        </button>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <button class="btn btn-outline-success w-100 quick-action-btn" onclick="quickAction('backup')">
                                            <i class="fas fa-database fa-2x mb-2 d-block"></i>
                                            Backup Now
                                        </button>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <button class="btn btn-outline-info w-100 quick-action-btn" onclick="quickAction('logs')">
                                            <i class="fas fa-file-alt fa-2x mb-2 d-block"></i>
                                            View Logs
                                        </button>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <button class="btn btn-outline-warning w-100 quick-action-btn" onclick="quickAction('settings')">
                                            <i class="fas fa-cogs fa-2x mb-2 d-block"></i>
                                            Settings
                                        </button>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <button class="btn btn-outline-danger w-100 quick-action-btn" onclick="quickAction('maintenance')">
                                            <i class="fas fa-tools fa-2x mb-2 d-block"></i>
                                            Maintenance
                                        </button>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <button class="btn btn-outline-secondary w-100 quick-action-btn" onclick="refreshStats()">
                                            <i class="fas fa-sync fa-2x mb-2 d-block"></i>
                                            Refresh Stats
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity & System Status -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0">
                                    <i class="fas fa-history me-2 text-info"></i>Recent Activity
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">New user registration</h6>
                                            <p class="timeline-text text-muted">John Doe registered as a new user</p>
                                            <small class="text-muted">5 minutes ago</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">System backup completed</h6>
                                            <p class="timeline-text text-muted">Automated backup finished successfully</p>
                                            <small class="text-muted">1 hour ago</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Settings updated</h6>
                                            <p class="timeline-text text-muted">Admin changed system configuration</p>
                                            <small class="text-muted">2 hours ago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0">
                                    <i class="fas fa-server me-2 text-success"></i>System Status
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>CPU Usage</span>
                                        <span class="text-success">24%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 24%"></div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>Memory Usage</span>
                                        <span class="text-warning">68%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 68%"></div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>Disk Space</span>
                                        <span class="text-info">45%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 45%"></div>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <small class="text-muted">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.quick-action-btn {
    transition: all 0.3s ease;
    border: 2px solid;
    padding: 20px 10px;
    height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.quick-action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -29px;
    top: 18px;
    width: 2px;
    height: calc(100% + 20px);
    background: #e9ecef;
}

.timeline-item:last-child::before {
    display: none;
}

.nav-link {
    padding: 12px 20px;
    transition: all 0.3s ease;
    border-radius: 0;
}

.nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    padding-left: 25px;
}

.nav-link.active {
    border-radius: 0 25px 25px 0;
    margin-right: 10px;
}
</style>

<script>
function quickAction(action) {
    switch(action) {
        case 'add-user':
            // window.location.href = "#"; // Add your route here later
            alert('Add User action clicked! Add your route here.');
            break;
        case 'backup':
            if(confirm('Start backup process now?')) {
                // Add your backup logic here
                alert('Backup started! You will be notified when complete.');
            }
            break;
        case 'logs':
            // window.location.href = "#"; // Add your route here later
            alert('View Logs action clicked! Add your route here.');
            break;
        case 'settings':
            // window.location.href = "#"; // Add your route here later
            alert('Settings action clicked! Add your route here.');
            break;
        case 'maintenance':
            // window.location.href = "#"; // Add your route here later
            alert('Maintenance action clicked! Add your route here.');
            break;
    }
}

function refreshStats() {
    // Add loading animation
    const buttons = document.querySelectorAll('.quick-action-btn');
    buttons.forEach(btn => btn.disabled = true);
    
    // Simulate API call
    setTimeout(() => {
        // Re-enable buttons
        buttons.forEach(btn => btn.disabled = false);
        
        // Update stats (you would make an AJAX call here in real implementation)
        document.getElementById('total-users').textContent = Math.floor(Math.random() * 1000) + 500;
        document.getElementById('admin-count').textContent = Math.floor(Math.random() * 20) + 5;
        document.getElementById('user-count').textContent = Math.floor(Math.random() * 800) + 400;
        
        // Show success message
        alert('Stats refreshed successfully!');
    }, 2000);
}

function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
        document.getElementById('fullscreen-icon').className = 'fas fa-compress';
    } else {
        document.exitFullscreen();
        document.getElementById('fullscreen-icon').className = 'fas fa-expand';
    }
}

// Auto-refresh stats every 5 minutes
setInterval(refreshStats, 300000);

// Initialize tooltips if Bootstrap is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to navigation
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});
</script>
@endsection