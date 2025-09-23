@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Admin Dashboard</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        Welcome, Admin! You have administrative privileges.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">User Management</h5>
                                    <p class="card-text">Manage regular users and their permissions.</p>
                                    <a href="#" class="btn btn-primary">Manage Users</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Content Management</h5>
                                    <p class="card-text">Manage website content and settings.</p>
                                    <a href="#" class="btn btn-success">Manage Content</a>
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