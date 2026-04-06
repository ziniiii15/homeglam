@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-pink: #ff8fb3;
        --neon-pink: #ff5c9a;
        --dark-bg: #fff7fb;
        --card-bg: #ffffff;
        --glass-bg: rgba(255, 255, 255, 0.96);
        --text-main: #212529;
        --text-muted: #6c757d;
        --border-color: rgba(255, 143, 179, 0.25);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--dark-bg);
        color: var(--text-main);
    }

    .admin-wrapper {
        min-height: 100vh;
        padding-bottom: 2.5rem;
        background: radial-gradient(circle at top right, #ffe5f0, #ffffff 60%);
    }
    
    .glass-card {
        background: var(--glass-bg);
        border: 1px solid rgba(255, 143, 179, 0.2);
        border-radius: 20px;
        box-shadow: 0 4px 18px 0 rgba(0, 0, 0, 0.04);
        backdrop-filter: blur(8px);
    }

    .section-title {
        color: var(--text-main);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    /* Table Styles */
    .table-custom {
        margin-bottom: 0;
    }
    .table-custom thead th {
        background-color: #fff7fb;
        border-bottom: 1px solid var(--border-color);
        color: var(--primary-pink);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .table-custom tbody td {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        color: var(--text-main);
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .table-custom tr:last-child td {
        border-bottom: none;
    }
    .table-hover tbody tr:hover td {
        background-color: rgba(255, 143, 179, 0.08);
        color: var(--text-main);
    }

    .text-primary-pink {
        color: var(--primary-pink) !important;
    }

    .btn-pink {
        background-color: var(--primary-pink);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-pink:hover {
        background-color: var(--neon-pink);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 92, 154, 0.3);
    }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin: 0 2px;
        transition: all 0.2s ease;
        position: relative;
        z-index: 1;
    }

    .bg-info-soft { background-color: rgba(13, 202, 240, 0.1) !important; color: #0dcaf0 !important; }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.1) !important; color: #ffc107 !important; }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1) !important; color: #dc3545 !important; }

    .btn-action:hover {
        transform: translateY(-2px);
        opacity: 1 !important;
    }
</style>

<div class="admin-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-3 mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold fs-5 d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-chevron-left me-2 text-primary-pink"></i>
                <span class="text-dark">Back to Dashboard</span>
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Admins Management</h2>
            <a href="{{ route('admins.create') }}" class="btn btn-pink">
                <i class="bi bi-person-plus-fill me-2"></i>Add Admin
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="card glass-card">
            <div class="card-header bg-transparent border-bottom border-light pt-3 px-3 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill me-2 text-primary-pink"></i>All System Admins</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $a)
                            <tr>
                                <td class="ps-3 fw-bold">#{{ $a->id }}</td>
                                <td>{{ $a->name }}</td>
                                <td>{{ $a->email }}</td>
                                <td>
                                    <span class="badge bg-light text-primary-pink border border-pink px-3 py-2 rounded-pill" style="border-color: var(--primary-pink) !important;">
                                        {{ $a->role }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admins.show', $a->id) }}" class="btn btn-action bg-info-soft border-0" title="View">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('admins.edit', $a->id) }}" class="btn btn-action bg-warning-soft border-0" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admins.destroy', $a->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-action bg-danger-soft border-0" onclick="return confirm('Are you sure?')" title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
