@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>

    <p>Welcome to the admin dashboard, {{ Auth::user()->name ?? 'Admin' }}!</p>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <p class="card-text">{{ \$totalStudents ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Staff</h5>
                    <p class="card-text">{{ \$totalStaff ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Unread Notifications</h5>
                    <p class="card-text">{{ \$notifications->count() ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- More dashboard widgets and content will go here --}}
    {{-- This is a very basic placeholder based on AdminDashboardController stub --}}

@endsection

@push('page_scripts')
    {{-- <script src="{{ asset('js/admin-dashboard.js') }}"></script> --}}
@endpush
