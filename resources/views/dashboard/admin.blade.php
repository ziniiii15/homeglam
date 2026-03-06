@extends('layouts.app')

@section('content')
<!-- Add Fonts & Icons -->
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
        padding-bottom: 1.5rem;
        background: radial-gradient(circle at top right, #ffe5f0, #ffffff 60%);
    }
    
    .glass-card {
        background: var(--glass-bg);
        border: 1px solid rgba(255, 143, 179, 0.2);
        border-radius: 20px;
        box-shadow: 0 4px 18px 0 rgba(0, 0, 0, 0.04);
        backdrop-filter: blur(8px);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .glass-card:hover {
        box-shadow: 0 10px 30px 0 rgba(255, 143, 179, 0.25);
    }

    .stat-card {
        border-radius: 18px;
        color: var(--text-main);
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
        z-index: 1;
        box-shadow: 0 4px 18px rgba(0,0,0,0.04);
        border: 1px solid rgba(255, 143, 179, 0.28);
        background-color: #ffffff;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #fffafd 0%, #ffe5f0 90%);
        z-index: -1;
    }
    .stat-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 30px rgba(214, 51, 132, 0.3);
    }
    
    .bg-gradient-pink,
    .bg-gradient-dark-pink,
    .bg-gradient-purple-pink {
        background: linear-gradient(135deg, #fffafd 0%, #ffe5f0 90%);
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
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    .table-custom tbody td {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        color: var(--text-main);
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    .table-custom tr:last-child td {
        border-bottom: none;
    }
    .table-hover tbody tr:hover td {
        background-color: rgba(255, 143, 179, 0.08);
        color: var(--text-main);
    }

    /* Text Gradient Logo */
    .text-gradient-logo {
        background: linear-gradient(45deg, #ffffff, #ff8fb3);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Pending Reports Card */
    .card-pending-reports {
        cursor: pointer;
        background: linear-gradient(135deg, #fffafd 0%, #ffe5f0 90%);
        display: block;
        text-decoration: none;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-pending-reports:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 30px rgba(255, 143, 179, 0.28);
    }

    .avatar-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.9rem;
        box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        border: 2px solid rgba(255,255,255,0.1);
    }

    /* Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: scale(1.1);
    }

    .hover-neon:hover {
        color: var(--neon-pink) !important;
        text-shadow: 0 0 10px rgba(255, 92, 154, 0.5);
    }
    
    .badge-pink-subtle {
        background-color: rgba(255, 143, 179, 0.08);
        color: #d63384;
        border: 1px solid rgba(255, 143, 179, 0.35);
    }

    .text-primary-pink {
        color: var(--primary-pink) !important;
    }

    .admin-sidebar {
        position: sticky;
        top: 0;
        max-height: 100vh;
    }

    .admin-sidebar .nav-link {
        border-radius: 999px;
        padding: 0.75rem 1.1rem;
        color: var(--text-muted);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        position: relative;
        transition: background-color 0.18s ease, color 0.18s ease, transform 0.08s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        background-color: rgba(255, 143, 179, 0.04);
        border: 1px solid rgba(255, 143, 179, 0.22);
    }
    .admin-sidebar .nav-link .sidebar-indicator {
        width: 5px;
        height: 24px;
        border-radius: 999px;
        background: rgba(255, 143, 179, 0.6);
        margin-right: 0.4rem;
    }
    .admin-sidebar .nav-link i {
        font-size: 1.1rem;
    }
    .admin-sidebar .nav-link:hover {
        background-color: rgba(255, 143, 179, 0.16);
        color: var(--primary-pink);
        transform: translateX(2px);
        box-shadow: 0 4px 12px rgba(255, 143, 179, 0.3);
        border-color: rgba(255, 143, 179, 0.45);
    }
    .admin-sidebar .nav-link:hover .sidebar-indicator {
        background: linear-gradient(180deg, #ff8fb3, #ff5c9a);
    }

    .admin-sidebar .sidebar-count-badge {
        position: absolute;
        top: 6px;
        right: 12px;
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
        font-size: 0.7rem;
        background-color: #ffffff;
        color: var(--primary-pink);
        border: 1px solid rgba(255, 143, 179, 0.6);
        min-width: 26px;
        text-align: center;
    }

    .admin-sidebar-inner {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f3f5; 
    }
    ::-webkit-scrollbar-thumb {
        background: #ced4da; 
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: var(--primary-pink); 
    }
</style>

<div class="admin-wrapper">
    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-3 col-lg-2 mb-4">
                <div class="card h-100 border-0 shadow-sm admin-sidebar">
                    <div class="card-body admin-sidebar-inner">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('images/logo.png') }}" alt="HomeGlam Logo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 0.75rem;">
                            <span class="fw-bold text-dark" style="letter-spacing: -0.3px; font-size: 1.1rem;">HomeGlam | Admin</span>
                        </div>
                        <h6 class="text-uppercase text-muted small mb-3">Navigation</h6>
                        <ul class="nav flex-column gap-2 mb-3 flex-grow-1">
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center w-100">
                                    <span class="sidebar-indicator"></span>
                                    <i class="bi bi-speedometer2 me-2"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.reports.index') }}" class="nav-link d-flex align-items-center w-100">
                                    <span class="sidebar-indicator"></span>
                                    <i class="bi bi-flag me-2"></i>
                                    <span>Reports</span>
                                    <span class="sidebar-count-badge">
                                        {{ \App\Models\Report::where('status', 'pending')->count() }}
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.appeals.index') }}" class="nav-link d-flex align-items-center w-100">
                                    <span class="sidebar-indicator"></span>
                                    <i class="bi bi-envelope-exclamation me-2"></i>
                                    <span>Appeals</span>
                                    <span class="sidebar-count-badge">
                                        {{ \App\Models\BanAppeal::where('status', 'pending')->count() }}
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('clients.index') }}" class="nav-link d-flex align-items-center w-100">
                                    <span class="sidebar-indicator"></span>
                                    <i class="bi bi-people me-2"></i>
                                    <span>Clients</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('beauticians.index') }}" class="nav-link d-flex align-items-center w-100">
                                    <span class="sidebar-indicator"></span>
                                    <i class="bi bi-magic me-2"></i>
                                    <span>Beauticians</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admins.index') }}" class="nav-link d-flex align-items-center w-100">
                                    <span class="sidebar-indicator"></span>
                                    <i class="bi bi-shield-lock me-2"></i>
                                    <span>Admins</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#transactions-section" class="nav-link d-flex align-items-center w-100">
                                    <span class="sidebar-indicator"></span>
                                    <i class="bi bi-receipt-cutoff me-2"></i>
                                    <span>Transactions</span>
                                </a>
                            </li>
                        </ul>
                        <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill w-100 fw-bold" style="border-width: 2px;">Logout</button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="col-md-9 col-lg-10">
                <div class="container mt-2">
        <!-- Welcome Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card glass-card p-5">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <h2 class="fw-bold mb-2">
                                Welcome back, {{ auth()->guard('admin')->user()->name }}!
                            </h2>
                            <p class="text-muted mb-0">
                                Use the tools on this dashboard to manage clients, beauticians,
                                reports, and appeals across HomeGlam.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <div class="d-inline-flex align-items-center px-4 py-3 rounded-4"
                                 style="background: linear-gradient(135deg, #fffafd 0%, #ffe5f0 90%);">
                                <i class="bi bi-speedometer2 text-primary-pink fs-3 me-3"></i>
                                <div>
                                    <div class="fw-bold text-primary-pink">Admin Dashboard</div>
                                    <small class="text-muted">Everything you need in one place</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Verifications Section -->
        @if(isset($pendingVerifications) && $pendingVerifications->count() > 0)
        <div class="row mb-3">
             <div class="col-12">
                <h4 class="section-title mb-2">Pending Verifications</h4>
             </div>
        </div>
        <div class="card glass-card mb-4">
             <div class="card-header bg-transparent border-bottom border-secondary pt-3 px-3 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-check me-2 text-warning"></i>Beauticians Awaiting Verification</h5>
             </div>
             <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Beautician</th>
                                <th>Email</th>
                                <th>Document</th>
                                <th>Subscription Proof</th>
                                <th>Date Registered</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingVerifications as $b)
                            @php
                                $subscriptionProofPattern = public_path('uploads/subscription_proofs/beautician_' . $b->id . '.*');
                                $subscriptionProofFiles = glob($subscriptionProofPattern);
                                $hasSubscriptionProof = $subscriptionProofFiles && count($subscriptionProofFiles) > 0;
                                $subscriptionProofUrl = null;
                                if ($hasSubscriptionProof) {
                                    $first = $subscriptionProofFiles[0];
                                    $relative = str_replace(public_path(), '', $first);
                                    $relative = ltrim($relative, '\\/');
                                    $subscriptionProofUrl = asset($relative);
                                }
                                $verificationDocumentUrl = $b->verification_document ? asset($b->verification_document) : null;
                            @endphp
                            <tr class="pending-verification-row"
                                style="cursor: pointer;"
                                data-name="{{ $b->name }}"
                                data-email="{{ $b->email }}"
                                data-doc-url="{{ $verificationDocumentUrl ?? '' }}"
                                data-proof-url="{{ $subscriptionProofUrl ?? '' }}">
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                         <div class="fw-bold text-dark">{{ $b->name }}</div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $b->email }}</td>
                                <td>
                                    @if($b->verification_document)
                                        <a href="{{ asset($b->verification_document) }}" target="_blank" class="btn btn-sm btn-outline-info">View Document</a>
                                    @else
                                        <span class="text-muted">No document</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hasSubscriptionProof)
                                        <span class="badge badge-pink-subtle rounded-pill px-3">Proof Submitted</span>
                                    @else
                                        <span class="text-muted">No proof</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $b->created_at->format('M d, Y') }}</td>
                                <td class="text-end pe-3">
                                    <form action="{{ route('admin.beauticians.verify', $b->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">Verify</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
             </div>
        </div>
        @endif

        <!-- Category Management Section -->
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                <h4 class="section-title mb-0">Service Categories</h4>
                <button type="button" class="btn btn-light rounded-pill shadow fw-bold px-4 text-dark" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-lg me-2 text-primary-pink"></i>Add Category
                </button>
            </div>
        </div>

        <!-- Categories List -->
        <div class="card glass-card mb-5">
            <div class="card-header bg-transparent border-bottom border-secondary pt-3 px-3 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-tags me-2 text-primary-pink"></i>Manage Categories</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Icon</th>
                                <th>Category Name</th>
                                <th>Slug</th>
                                <th>Services Count</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td class="ps-3">
                                    <div class="avatar-circle bg-dark text-white border-pink shadow-sm" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                        <i class="bi bi-{{ $category->icon }}"></i>
                                    </div>
                                </td>
                                <td><span class="fw-bold text-dark">{{ $category->name }}</span></td>
                                <td class="text-muted">{{ $category->slug }}</td>
                                <td>
                                    <span class="badge badge-pink-subtle rounded-pill px-3">
                                        {{ \App\Models\Service::where('category', $category->slug)->count() }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure? This might affect existing services.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No categories found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Admin Management Section -->
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                <h4 class="section-title mb-0">Platform Management</h4>
                <button type="button" class="btn btn-light rounded-pill shadow fw-bold px-4 text-dark" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                    <i class="bi bi-plus-lg me-2 text-primary-pink"></i>New Admin
                </button>
            </div>
        </div>

        <!-- Admins Table -->
        <div class="card glass-card mb-4">
            <div class="card-header bg-transparent border-bottom border-secondary pt-3 px-3 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock me-2 text-primary-pink"></i>Admin Users</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">User</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $admin)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3 text-white bg-gradient-pink shadow-sm">
                                            {{ substr($admin->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $admin->name }}</div>
                                            <small class="text-muted">ID: #{{ $admin->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-pink-subtle rounded-pill px-3 py-2">
                                        {{ ucfirst($admin->role) }}
                                    </span>
                                </td>
                                <td class="text-muted fw-medium">{{ $admin->email }}</td>
                                <td class="text-muted">{{ $admin->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">No admins found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card glass-card mb-4" id="transactions-section">
            <div class="card-header bg-transparent border-bottom border-secondary pt-3 px-3 pb-2">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-receipt-cutoff me-2 text-primary-pink"></i>
                    Beautician Subscription Transactions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6 class="fw-bold text-dark mb-3">Subscription QR</h6>
                        @php
                            $subscriptionQrPath = null;
                            if (file_exists(public_path('uploads/admin/subscription_qr.png'))) {
                                $subscriptionQrPath = asset('uploads/admin/subscription_qr.png');
                            }
                        @endphp

                        @if($subscriptionQrPath)
                            <div class="mb-3">
                                <img src="{{ $subscriptionQrPath }}" alt="Subscription QR" class="img-fluid rounded border" style="max-height: 240px; object-fit: contain;">
                            </div>
                        @else
                            <div class="mb-3 text-center text-muted" style="border: 1px dashed rgba(0,0,0,0.15); border-radius: 16px; padding: 1.75rem 1rem;">
                                <i class="bi bi-qr-code fs-1 mb-2 d-block text-primary-pink"></i>
                                <span>No subscription QR uploaded yet.</span>
                            </div>
                        @endif

                        <form action="{{ route('admin.subscription_qr.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <input type="file" name="subscription_qr" class="form-control form-control-sm" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                                Save QR
                            </button>
                        </form>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-custom align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Beautician</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $transactions = isset($subscriptionTransactions) ? $subscriptionTransactions : collect();
                                    @endphp
                                    @forelse($transactions as $tx)
                                        <tr>
                                            <td class="ps-3">{{ $tx->beautician_name ?? 'N/A' }}</td>
                                            <td class="text-muted">{{ $tx->type ?? 'Subscription' }}</td>
                                            <td class="fw-bold text-dark">₱{{ number_format($tx->amount ?? 0, 2) }}</td>
                                            <td class="text-muted">{{ $tx->created_at ?? '-' }}</td>
                                            <td>
                                                <span class="badge badge-pink-subtle rounded-pill px-3">
                                                    {{ ucfirst($tx->status ?? 'paid') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                No subscription transactions recorded yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout for Clients & Beauticians -->
        <div class="row g-3">
            <!-- Clients List -->
            <div class="col-lg-6">
                <div class="card glass-card h-100">
            <div class="card-header bg-transparent border-bottom border-secondary pt-3 px-3 pb-2 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-people me-2 text-primary-pink"></i>Recent Clients</h5>
                        <span class="badge bg-light text-muted border border-secondary">{{ $clients->count() }} Total</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Name</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($clients->take(5) as $client)
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('clients.show', $client->id) }}" class="text-decoration-none group-hover">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-3 bg-dark text-primary-pink border border-light border-opacity-25">
                                                        {{ substr($client->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark hover-neon transition-all">{{ $client->name }}</div>
                                                        <small class="text-muted" style="font-size: 0.7rem;">Joined {{ $client->created_at->format('M d') }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="small text-muted fw-medium">{{ $client->email }}</div>
                                            <div class="small text-muted">{{ $client->phone ?? '-' }}</div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2" class="text-center py-4 text-muted">No clients yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($clients->count() > 5)
                        <div class="card-footer bg-transparent border-0 text-center pb-3 pt-2">
                            <a href="{{ route('clients.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 text-decoration-none fw-bold hover-neon">View All Clients</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Beauticians List -->
            <div class="col-lg-6">
                <div class="card glass-card h-100">
                    <div class="card-header bg-transparent border-bottom border-secondary pt-3 px-3 pb-2 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-magic me-2 text-primary-pink"></i>Beauticians</h5>
                         <span class="badge bg-light text-muted border border-secondary">{{ $beauticians->count() }} Total</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Professional</th>
                                        <th>Status</th>
                                        <th>Services</th>
                                        <th>Subscription</th>
                                        <th class="text-end pe-3">Test</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($beauticians->take(5) as $beautician)
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('beauticians.show', $beautician->id) }}" class="text-decoration-none">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-3 bg-dark text-primary-pink border border-light border-opacity-25">
                                                        {{ substr($beautician->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark hover-neon transition-all">{{ $beautician->name }}</div>
                                                        <small class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-geo-alt-fill me-1"></i>{{ $beautician->base_location }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            @if($beautician->banned_until && $beautician->banned_until->isFuture())
                                                <span class="badge bg-danger">Banned</span>
                                            @else
                                                <span class="badge bg-success">Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($beautician->serviceList->count() > 0)
                                                <span class="badge badge-pink-subtle px-2 py-1">
                                                    {{ $beautician->serviceList->count() }} Services
                                                </span>
                                            @else
                                                <span class="badge bg-secondary text-white-50">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($beautician->subscription_expires_at)
                                                @if($beautician->subscription_expires_at->isPast())
                                                    <span class="badge bg-danger">Expired</span>
                                                @else
                                                    <span class="badge bg-success">Active</span>
                                                    <div class="small text-muted">
                                                        {{ $beautician->subscription_expires_at->diffForHumans(null, true, true) }} left
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">No subscription</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <form action="{{ route('admin.beauticians.test_subscription', $beautician->id) }}" method="POST" onsubmit="return confirm('Set this beautician subscription as expired for testing?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-3">Test Expiry</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2" class="text-center py-4 text-muted">No beauticians yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                         @if($beauticians->count() > 5)
                        <div class="card-footer bg-transparent border-0 text-center pb-3 pt-2">
                            <a href="{{ route('beauticians.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 text-decoration-none fw-bold hover-neon">View All Beauticians</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg bg-white text-dark" style="border-radius: 20px; border: 1px solid rgba(0,0,0,0.05);">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Add New Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admins.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="admin">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Create Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg bg-white text-dark" style="border-radius: 20px; border: 1px solid rgba(0,0,0,0.05);">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Category Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Hair, Makeup" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Icon (Bootstrap Icon Name)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary text-muted">bi-</span>
                            <input type="text" name="icon" class="form-control" placeholder="scissors">
                        </div>
                        <div class="form-text text-muted">Use Bootstrap Icon names (e.g. scissors, palette, stars).</div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="beauticianVerifyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg bg-white text-dark" style="border-radius: 20px; border: 1px solid rgba(0,0,0,0.05);">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="verifyModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="small text-muted">Email</div>
                    <div id="verifyModalEmail" class="fw-semibold"></div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-2">Verification Document</h6>
                        <div id="verifyModalDoc"></div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-2">Subscription Proof</h6>
                        <div id="verifyModalProof"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var rows = document.querySelectorAll('.pending-verification-row');
    var modalEl = document.getElementById('beauticianVerifyModal');
    if (!modalEl || !rows.length) {
        return;
    }
    var modal = bootstrap.Modal ? new bootstrap.Modal(modalEl) : null;
    if (!modal) {
        return;
    }

    rows.forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('form')) {
                return;
            }

            var name = row.getAttribute('data-name') || '';
            var email = row.getAttribute('data-email') || '';
            var docUrl = row.getAttribute('data-doc-url') || '';
            var proofUrl = row.getAttribute('data-proof-url') || '';

            var titleEl = modalEl.querySelector('#verifyModalTitle');
            var emailEl = modalEl.querySelector('#verifyModalEmail');
            var docContainer = modalEl.querySelector('#verifyModalDoc');
            var proofContainer = modalEl.querySelector('#verifyModalProof');

            if (!titleEl || !emailEl || !docContainer || !proofContainer) {
                return;
            }

            titleEl.textContent = name;
            emailEl.textContent = email;

            docContainer.innerHTML = '';
            proofContainer.innerHTML = '';

            if (docUrl) {
                var lowerDocUrl = docUrl.toLowerCase();
                if (lowerDocUrl.indexOf('.pdf') !== -1) {
                    var iframe = document.createElement('iframe');
                    iframe.src = docUrl;
                    iframe.style.width = '100%';
                    iframe.style.height = '260px';
                    iframe.className = 'border rounded';
                    iframe.setAttribute('loading', 'lazy');
                    iframe.style.cursor = 'zoom-in';
                    iframe.addEventListener('click', function () {
                        if (iframe.style.height === '260px') {
                            iframe.style.height = '80vh';
                            iframe.style.cursor = 'zoom-out';
                        } else {
                            iframe.style.height = '260px';
                            iframe.style.cursor = 'zoom-in';
                        }
                    });
                    docContainer.appendChild(iframe);
                } else {
                    var docImg = document.createElement('img');
                    docImg.src = docUrl;
                    docImg.className = 'img-fluid rounded border';
                    docImg.style.maxHeight = '260px';
                    docImg.style.objectFit = 'contain';
                    docImg.style.cursor = 'zoom-in';
                    docImg.addEventListener('click', function () {
                        if (docImg.style.maxHeight === '260px') {
                            docImg.style.maxHeight = '80vh';
                            docImg.style.cursor = 'zoom-out';
                        } else {
                            docImg.style.maxHeight = '260px';
                            docImg.style.cursor = 'zoom-in';
                        }
                    });
                    docContainer.appendChild(docImg);
                }
            } else {
                docContainer.textContent = 'No document uploaded.';
            }

            if (proofUrl) {
                var img = document.createElement('img');
                img.src = proofUrl;
                img.className = 'img-fluid rounded border';
                img.style.maxHeight = '260px';
                img.style.objectFit = 'contain';
                img.style.cursor = 'zoom-in';
                img.addEventListener('click', function () {
                    if (img.style.maxHeight === '260px') {
                        img.style.maxHeight = '80vh';
                        img.style.cursor = 'zoom-out';
                    } else {
                        img.style.maxHeight = '260px';
                        img.style.cursor = 'zoom-in';
                    }
                });
                proofContainer.appendChild(img);
            } else {
                proofContainer.textContent = 'No subscription proof uploaded.';
            }

            modal.show();
        });
    });
});
</script>
@endsection
