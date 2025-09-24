@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 my-3">
            <div class="btn-group d-flex justify-content-between" role="group" aria-label="Basic example">
                <a href="{{ route('dashboard') }}" class="btn btn-info align-items-center text-danger mr-2">
                    <i class="fas fa-arrow-left me-2"></i>Return Back
                </a>
                <button type="button" class="btn btn-primary align-items-center" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                    <i class="fas fa-plus me-2"></i>Create New Task
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Task Management</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Assigned Users</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ Str::limit($task->description, 50) }}</td>
                                        <td>
                                            <span class="badge
                                                @if($task->priority == 'high') bg-danger
                                                @elseif($task->priority == 'medium') bg-warning
                                                @else bg-info @endif">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.tasks.updateStatus', $task) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            @if($task->due_date)
                                                <span class="{{ $task->isOverdue() ? 'text-danger' : '' }}">
                                                    {{ $task->due_date->format('M d, Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">No due date</span>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($task->assignedUsers as $user)
                                                <span class="badge bg-secondary mb-1">{{ $user->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $task->creator->name }}</td>
                                        <td>
                                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No tasks found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Create New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.tasks.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">Task Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Assign to Users *</label>

                            <!-- Search Input -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="userSearch" placeholder="Search users by name..." autocomplete="off">
                                <button type="button" class="btn btn-outline-secondary" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- User Selection Stats -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">
                                    <span id="selectedCount">0</span> users selected
                                </small>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" id="selectAll">
                                        <i class="fas fa-check-double"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                        <i class="fas fa-times-circle"></i> Clear All
                                    </button>
                                </div>
                            </div>

                            <!-- Users Checkbox List -->
                            <div class="users-checkbox-container border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                <div id="usersLoading" class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading users...</span>
                                    </div>
                                    <span class="ms-2">Loading users...</span>
                                </div>
                                <div id="usersList" class="row g-2"></div>
                                <div id="noUsersFound" class="text-center text-muted py-3" style="display: none;">
                                    <i class="fas fa-users-slash fa-2x mb-2"></i>
                                    <p>No users found matching your search</p>
                                </div>
                            </div>

                            <!-- Hidden input field for form submission -->
                            <input type="hidden" name="assigned_users" id="assignedUsersInput" required>

                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle"></i> Select one or more users to assign this task
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.75em;
    }
    .table td {
        vertical-align: middle;
    }
    .form-select-sm {
        width: auto;
        display: inline-block;
    }

    /* Custom checkbox styles */
    .user-checkbox-item {
        transition: all 0.2s ease;
        border-radius: 5px;
        padding: 8px;
    }
    .user-checkbox-item:hover {
        background-color: #f8f9fa;
    }
    .user-checkbox-item.selected {
        background-color: #e3f2fd;
        border-left: 3px solid #0d6efd;
    }
    .user-checkbox {
        transform: scale(1.1);
        margin-right: 10px;
    }
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #6c757d;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 10px;
    }
    .users-checkbox-container::-webkit-scrollbar {
        width: 6px;
    }
    .users-checkbox-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    .users-checkbox-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date to today for due date
        document.getElementById('due_date').min = new Date().toISOString().split('T')[0];

        // Users data and state management
        let allUsers = [];
        let filteredUsers = [];
        let selectedUsers = new Set();

        // DOM elements
        const usersList = document.getElementById('usersList');
        const usersLoading = document.getElementById('usersLoading');
        const noUsersFound = document.getElementById('noUsersFound');
        const userSearch = document.getElementById('userSearch');
        const clearSearch = document.getElementById('clearSearch');
        const selectAllBtn = document.getElementById('selectAll');
        const deselectAllBtn = document.getElementById('deselectAll');
        const selectedCount = document.getElementById('selectedCount');
        const assignedUsersInput = document.getElementById('assignedUsersInput');
        const userSearchForm = document.getElementById('userSearch');

        // Initialize users (you can replace this with AJAX call if needed)
        function initializeUsers() {
            // Simulate loading delay
            setTimeout(() => {
                allUsers = [
                    @foreach($users as $user)
                    {
                        id: {{ $user->id }},
                        name: "{{ $user->name }}",
                        email: "{{ $user->email }}",
                        initials: "{{ substr($user->name, 0, 1) }}",
                        isActive: {{ $user->last_login_at ? 'true' : 'false' }}
                    },
                    @endforeach
                ];

                // Sort users: active first, then by name
                allUsers.sort((a, b) => {
                    if (a.isActive && !b.isActive) return -1;
                    if (!a.isActive && b.isActive) return 1;
                    return a.name.localeCompare(b.name);
                });

                filteredUsers = [...allUsers];
                renderUsers();
                usersLoading.style.display = 'none';
            }, 500);
        }

        // Render users list
        function renderUsers() {
            if (filteredUsers.length === 0) {
                usersList.style.display = 'none';
                noUsersFound.style.display = 'block';
                return;
            }

            usersList.style.display = 'block';
            noUsersFound.style.display = 'none';

            usersList.innerHTML = filteredUsers.map(user => `
                <div class="col-md-6">
                    <div class="user-checkbox-item d-flex align-items-center p-2 ${selectedUsers.has(user.id) ? 'selected' : ''}">
                        <input type="checkbox"
                               class="user-checkbox"
                               value="${user.id}"
                               ${selectedUsers.has(user.id) ? 'checked' : ''}
                               onchange="toggleUser(${user.id})">
                        <div class="user-avatar">${user.initials}</div>
                        <div class="user-info flex-grow-1">
                            <div class="user-name fw-medium">${user.name}</div>
                            <div class="user-email small text-muted">${user.email}</div>
                        </div>
                        ${user.isActive ? '<span class="badge bg-success badge-sm"><i class="fas fa-circle me-1"></i>Active</span>' : ''}
                    </div>
                </div>
            `).join('');
        }

        // Toggle user selection
        window.toggleUser = function(userId) {
            if (selectedUsers.has(userId)) {
                selectedUsers.delete(userId);
            } else {
                selectedUsers.add(userId);
            }
            updateSelectionState();
            renderUsers();
        }

        // Update selection state and hidden input
        function updateSelectionState() {
            selectedCount.textContent = selectedUsers.size;
            assignedUsersInput.value = Array.from(selectedUsers).join(',');

            // Update validation
            if (selectedUsers.size > 0) {
                assignedUsersInput.setCustomValidity('');
            } else {
                assignedUsersInput.setCustomValidity('Please select at least one user');
            }
        }

        // Search users
        userSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();

            if (searchTerm === '') {
                filteredUsers = [...allUsers];
            } else {
                filteredUsers = allUsers.filter(user =>
                    user.name.toLowerCase().includes(searchTerm) ||
                    user.email.toLowerCase().includes(searchTerm)
                );
            }

            renderUsers();
        });

        // Clear search
        clearSearch.addEventListener('click', function() {
            userSearch.value = '';
            filteredUsers = [...allUsers];
            renderUsers();
            userSearch.focus();
        });

        // Select all visible users
        selectAllBtn.addEventListener('click', function() {
            filteredUsers.forEach(user => selectedUsers.add(user.id));
            updateSelectionState();
            renderUsers();
        });

        // Deselect all users
        deselectAllBtn.addEventListener('click', function() {
            selectedUsers.clear();
            updateSelectionState();
            renderUsers();
        });

        // Keyboard shortcuts
        userSearch.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                filteredUsers = [...allUsers];
                renderUsers();
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            if (selectedUsers.size === 0) {
                e.preventDefault();
                alert('Please select at least one user to assign the task to.');
                return false;
            }
            
        });

        // Modal cleanup
        $('#createTaskModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            selectedUsers.clear();
            updateSelectionState();
            filteredUsers = [...allUsers];
            renderUsers();
        });

        // Initialize the component
        initializeUsers();
    });
</script>
@endpush
