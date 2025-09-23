@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">User Dashboard</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-success">
                        Welcome, {{ auth()->user()->name }}! This is your personal dashboard.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Profile Information</h5>
                                    <ul class="list-unstyled">
                                        <li><strong>Name:</strong> {{ auth()->user()->name }}</li>
                                        <li><strong>Email:</strong> {{ auth()->user()->email }}</li>
                                        <li><strong>Role:</strong> <span class="badge bg-primary">{{ auth()->user()->role }}</span></li>
                                        <li><strong>Member since:</strong> {{ auth()->user()->created_at->format('M d, Y') }}</li>
                                    </ul>
                                    <a href="#" class="btn btn-outline-primary">Edit Profile</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Quick Actions</h5>
                                    <div class="d-grid gap-2">
                                        <a href="#" class="btn btn-outline-success">View Profile</a>
                                        <a href="#" class="btn btn-outline-info">Settings</a>
                                        <a href="#" class="btn btn-outline-warning">Help & Support</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection