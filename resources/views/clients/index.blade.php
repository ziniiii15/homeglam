@extends('layouts.app')

@section('content')
<!-- Add Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .admin-wrapper {
        min-height: 100vh;
        padding-bottom: 3rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        backdrop-filter: blur(4px);
    }

    .table-custom thead th {
        background-color: transparent;
        border-bottom: 2px solid #eee;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #555;
    }
</style>

<div class="admin-wrapper">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted small fw-bold mb-2 d-inline-block">
                    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                </a>
                <h2 class="fw-bold text-dark mb-0">Manage Clients</h2>
            </div>
            <a href="{{ route('clients.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Add New Client
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="card glass-card">
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Client Name</th>
                                <th>Contact Info</th>
                                <th>Address</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('clients.show', $client->id) }}" class="text-decoration-none">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3 bg-info-subtle text-info fw-bold">
                                                {{ substr($client->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark hover-primary">{{ $client->name }}</div>
                                                <small class="text-muted">ID: #{{ $client->id }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark mb-1"><i class="bi bi-envelope me-2 text-muted"></i>{{ $client->email }}</span>
                                        <span class="text-muted small"><i class="bi bi-telephone me-2"></i>{{ $client->phone }}</span>
                                    </div>
                                </td>
                                <td class="text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($client->address, 30) }}
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-light text-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-light text-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger" onclick="return confirm('Are you sure you want to delete this client?')" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">No clients found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
