@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Notification Toast Container -->
            <div aria-live="polite" aria-atomic="true" class="position-relative">
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
                    <!-- Toast notifications will appear here -->
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">User Dashboard</h4>
                        <span class="badge bg-warning" id="notificationBadge" style="display: none;">0</span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Welcome Message with Task Stats -->
                    <div class="alert alert-success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Welcome, {{ auth()->user()->name }}! You have 
                                <strong>{{ $pendingTasks->count() }} pending tasks</strong>
                                @if($overdueTasks->count() > 0)
                                    <span class="text-danger">({{ $overdueTasks->count() }} overdue)</span>
                                @endif
                            </div>
                            <div>
                                <span class="badge bg-success">Completed: {{ $completedTasks->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Real-time Notifications Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-bell me-2"></i>Recent Notifications
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="realtimeNotifications">
                                        <div class="text-center text-muted py-3" id="noNotifications">
                                            <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                            <p>No new notifications</p>
                                        </div>
                                        <!-- Notifications will appear here dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rest of your existing dashboard content -->
                    <div class="row">
                        <!-- ... your existing profile and tasks content ... -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pass PHP data to JavaScript safely --}}
<script type="text/javascript">
    window.dashboardData = {
        userId: {{ auth()->id() }},
        userName: @json(auth()->user()->name),
        csrfToken: @json(csrf_token())
    };
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== ECHO DEBUGGING START ===');
    
    // Check Bootstrap availability
    if (typeof bootstrap !== 'undefined') {
        console.log('✅ Bootstrap JS is available');
    } else {
        console.warn('⚠️ Bootstrap JS not available - using fallback methods');
    }
    
    // Check if Echo is available
    if (typeof window.Echo === 'undefined') {
        console.error('❌ Echo is not available!');
        showErrorAlert('Echo library not loaded. Please run npm run dev and check console.');
        return;
    }

    console.log('✅ Echo is available');
    
    // Get user ID from window object (safer than inline PHP)
    const userId = window.dashboardData.userId;
    console.log('Current user ID:', userId);
    
    if (!userId) {
        console.error('❌ User ID not available');
        showErrorAlert('User authentication error. Please refresh the page.');
        return;
    }
    
    // Check if Pusher connector exists
    if (!window.Echo.connector || !window.Echo.connector.pusher) {
        console.error('❌ Pusher connector not available');
        showErrorAlert('Real-time connection not available. Check your internet connection.');
        return;
    }

    // Set up connection state monitoring
    const pusher = window.Echo.connector.pusher;
    
    pusher.connection.bind('state_change', function(states) {
        console.log('🔄 Pusher state:', states.previous, '→', states.current);
    });

    pusher.connection.bind('connected', function() {
        console.log('✅ Connected to Pusher');
        setupPrivateChannel(userId);
    });

    pusher.connection.bind('disconnected', function() {
        console.log('⚠️ Disconnected from Pusher');
        showErrorAlert('Real-time connection lost. Trying to reconnect...');
    });

    pusher.connection.bind('error', function(err) {
        console.error('❌ Pusher error:', err);
        showErrorAlert('Connection error: ' + (err.error?.message || 'Unknown error'));
    });

    // If already connected, set up channel immediately
    if (pusher.connection.state === 'connected') {
        setupPrivateChannel(userId);
    }
});

function setupPrivateChannel(userId) {
    console.log('🔧 Setting up private channel: user.' + userId);
    
    try {
        const channel = window.Echo.private(`user.${userId}`);
        
        // Listen for successful subscription (correct method for private channels)
        channel.subscribed(() => {
            console.log('✅ Private channel subscription successful');
            showSuccessAlert('Real-time notifications are now active!');
        });
        
        channel.error((error) => {
            console.error('❌ Channel subscription failed:', error);
            showErrorAlert('Failed to subscribe to notifications. Status: ' + (error.status || 'Unknown'));
            
            // Try public channel as fallback for testing
            console.log('🔄 Trying public channel as fallback...');
            setupPublicChannel(userId);
        });

        // Listen for events
        channel.listen('.task.assigned', (e) => {
            console.log('🔔 Task assigned notification:', e);
            handleNotification(e);
        });

        channel.listen('.task.updated', (e) => {
            console.log('🔔 Task updated notification:', e);
            handleNotification(e);
        });
        
    } catch (error) {
        console.error('❌ Error setting up private channel:', error);
        showErrorAlert('Error setting up real-time connection: ' + error.message);
    }
}

// Fallback public channel for testing
function setupPublicChannel(userId) {
    console.log('🔧 Setting up public channel for user:', userId);
    
    try {
        const publicChannel = window.Echo.channel(`user-public.${userId}`);
        
        publicChannel.subscribed(() => {
            console.log('✅ Public channel subscribed successfully');
            showSuccessAlert('Public notifications channel active (fallback mode)');
        });
        
        publicChannel.listen('.task.assigned', (e) => {
            console.log('🔔 Public channel notification:', e);
            handleNotification(e);
        });

        publicChannel.error((error) => {
            console.error('❌ Public channel error:', error);
        });
        
    } catch (error) {
        console.error('❌ Error setting up public channel:', error);
    }
}

function handleNotification(notification) {
    console.log('📨 Processing notification', notification);
    
    // Show toast notification
    showNotificationToast(notification);
    
    // Add to notifications list
    addNotificationToList(notification);
    
    // Update badge
    updateNotificationBadge();
}

function showSuccessAlert(message) {
    const container = document.querySelector('.container');
    if (container) {
        container.insertAdjacentHTML('afterbegin', `
            <div class="alert alert-success alert-dismissible fade show">
                <h6>✅ ${message}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        setTimeout(() => {
            const alert = container.querySelector('.alert-success');
            if (alert) alert.remove();
        }, 5000);
    }
}

function showErrorAlert(message) {
    const container = document.querySelector('.container');
    if (container) {
        container.insertAdjacentHTML('afterbegin', `
            <div class="alert alert-danger alert-dismissible fade show">
                <h6>❌ ${message}</h6>
                <p class="mb-1">Check the browser console for details.</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
    }
}

function showNotificationToast(notification) {
    const toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        console.error('Toast container not found');
        return;
    }
    
    const toastId = 'toast-' + Date.now();
    
    const toastHTML = `
        <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-primary text-white">
                <i class="fas fa-tasks me-2"></i>
                <strong class="me-auto">New Task Assigned</strong>
                <small class="text-white">just now</small>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <strong>${notification.task?.title || 'New Task'}</strong>
                <br>
                <small class="text-muted">Priority: ${notification.task?.priority || 'Normal'}</small>
                ${notification.task?.due_date ? `<br><small class="text-muted">Due: ${notification.task.due_date}</small>` : ''}
                <div class="mt-2 pt-2 border-top">
                    <small class="text-muted">${notification.timestamp || new Date().toLocaleString()}</small>
                </div>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    const toastElement = document.getElementById(toastId);
    
    // Check if Bootstrap is available, if not use fallback
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 5000
        });
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    } else {
        console.warn('Bootstrap not available, using fallback toast display');
        // Fallback: just show the toast and auto-remove after 5 seconds
        toastElement.style.display = 'block';
        toastElement.classList.add('show');
        
        // Add click handler for close button
        const closeBtn = toastElement.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                toastElement.remove();
            });
        }
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (toastElement && toastElement.parentNode) {
                toastElement.remove();
            }
        }, 5000);
    }
}

function addNotificationToList(notification) {
    const noNotifications = document.getElementById('noNotifications');
    if (noNotifications) {
        noNotifications.style.display = 'none';
    }
    
    const notificationsContainer = document.getElementById('realtimeNotifications');
    const notificationId = 'notification-' + Date.now();
    
    const notificationHTML = `
        <div id="${notificationId}" class="alert alert-info alert-dismissible fade show mb-2">
            <div class="d-flex">
                <i class="fas fa-tasks me-3 mt-1"></i>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">New Task: ${notification.task?.title || 'New Task'}</h6>
                    <p class="mb-1">${notification.message || 'A new task has been assigned to you.'}</p>
                    <small class="text-muted">
                        Priority: <span class="badge bg-${getPriorityBadgeColor(notification.task?.priority)}">${notification.task?.priority || 'normal'}</span>
                        ${notification.task?.due_date ? ` • Due: ${notification.task.due_date}` : ''}
                    </small>
                    <br>
                    <small class="text-muted">Received: ${new Date(notification.timestamp || Date.now()).toLocaleString()}</small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    if (notificationsContainer.firstChild && notificationsContainer.firstChild.id !== 'noNotifications') {
        notificationsContainer.insertAdjacentHTML('afterbegin', notificationHTML);
    } else {
        notificationsContainer.innerHTML = notificationHTML;
    }
    
    // Auto-remove after 30 seconds
    setTimeout(() => {
        const notificationElement = document.getElementById(notificationId);
        if (notificationElement) {
            notificationElement.remove();
            if (document.querySelectorAll('#realtimeNotifications .alert').length === 0) {
                showNoNotifications();
            }
        }
    }, 30000);
}

function getPriorityBadgeColor(priority) {
    switch (priority?.toLowerCase()) {
        case 'high': return 'danger';
        case 'medium': return 'warning';
        case 'low': return 'info';
        default: return 'secondary';
    }
}

function updateNotificationBadge() {
    const badge = document.getElementById('notificationBadge');
    if (badge) {
        const currentCount = parseInt(badge.textContent) || 0;
        badge.textContent = currentCount + 1;
        badge.style.display = 'inline-block';
        badge.style.transform = 'scale(1.2)';
        setTimeout(() => {
            badge.style.transform = 'scale(1)';
        }, 300);
    }
}

function showNoNotifications() {
    const notificationsContainer = document.getElementById('realtimeNotifications');
    if (notificationsContainer) {
        notificationsContainer.innerHTML = `
            <div class="text-center text-muted py-3" id="noNotifications">
                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                <p>No new notifications</p>
            </div>
        `;
    }
}
</script>
@endpush

@push('styles')
<style>
.toast {
    min-width: 300px;
    opacity: 0;
    transition: opacity 0.15s ease-in-out;
}

.toast.show {
    opacity: 1;
}

.toast:not(.show) {
    display: none;
}

.notification-badge {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.alert {
    border-left: 4px solid;
}

.alert-info {
    border-left-color: #0dcaf0;
}

/* Toast positioning and styling */
.toast-container {
    z-index: 1055;
}

.toast-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.toast-body {
    padding: 0.75rem;
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}
</style>
@endpush