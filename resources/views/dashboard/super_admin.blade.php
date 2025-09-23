@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Super Admin Dashboard</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Users</h5>
                                    <p class="card-text display-4">{{ \App\Models\User::count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Admins</h5>
                                    <p class="card-text display-4">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Regular Users</h5>
                                    <p class="card-text display-4">{{ \App\Models\User::where('role', 'user')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>System Management</h5>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">User Management</a>
                            <a href="#" class="list-group-item list-group-item-action">System Settings</a>
                            <a href="#" class="list-group-item list-group-item-action">Database Backup</a>
                            <a href="#" class="list-group-item list-group-item-action">Logs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection