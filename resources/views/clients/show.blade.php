@extends('layouts.app')

@section('content')
<!-- Add Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-pink: #D63384;
        --neon-pink: #ff69b4;
        --dark-bg: #f5f5f5;
        --card-bg: #ffffff;
        --glass-bg: rgba(255, 255, 255, 0.95);
        --text-main: #212529;
        --text-muted: #6c757d;
        --border-color: rgba(214, 51, 132, 0.15);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--dark-bg);
        color: var(--text-main);
    }

    .admin-wrapper {
        min-height: 100vh;
        padding-bottom: 3rem;
        background: radial-gradient(circle at top right, #ffe5f0, #ffffff 60%);
    }
    
    .glass-card {
        background: var(--glass-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 6px 24px 0 rgba(0, 0, 0, 0.08);
        backdrop-filter: blur(10px);
    }

    .profile-header {
        background: linear-gradient(135deg, #ffffff 0%, #ffe5f0 100%);
        border-radius: 20px;
        padding: 2rem;
        color: var(--text-main);
        margin-bottom: 2rem;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
        position: relative;
        overflow: hidden;
    }
    .profile-header::before {
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; width: 30%;
        background: linear-gradient(90deg, transparent, rgba(214, 51, 132, 0.1));
        z-index: 0;
    }

    .avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 3px solid var(--primary-pink);
        background: var(--card-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--neon-pink);
        object-fit: cover;
        box-shadow: 0 0 20px rgba(214, 51, 132, 0.3);
        position: relative;
        z-index: 1;
    }

    .stat-badge {
        background: rgba(214,51,132,0.04);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(214,51,132,0.15);
        color: var(--text-muted);
    }
    .stat-badge i { color: var(--neon-pink); }
    .stat-badge span { color: var(--text-main); }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .booking-item {
        background: transparent;
        transition: transform 0.2s;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        color: var(--text-muted);
    }
    .booking-item:hover {
        transform: translateX(5px);
        background-color: rgba(214, 51, 132, 0.05);
        color: var(--text-main);
    }
    .booking-item h6 { color: var(--text-main); }
    
    .review-card {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    .review-card:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .badge-status-completed { background-color: rgba(25, 135, 84, 0.2); color: #75b798; border: 1px solid rgba(25, 135, 84, 0.3); }
    .badge-status-pending { background-color: rgba(255, 193, 7, 0.2); color: #ffda6a; border: 1px solid rgba(255, 193, 7, 0.3); }
    .badge-status-other { background-color: rgba(108, 117, 125, 0.2); color: #adb5bd; border: 1px solid rgba(108, 117, 125, 0.3); }

    .btn-contact {
        background: transparent;
        border: 2px solid var(--primary-pink);
        color: var(--neon-pink);
        transition: all 0.3s;
    }
    .btn-contact:hover {
        background: var(--primary-pink);
        color: white;
        box-shadow: 0 0 15px rgba(214, 51, 132, 0.4);
    }
</style>

<div class="admin-wrapper">
    <div class="container py-5">
        <!-- Back Button -->
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted fw-bold mb-4 d-inline-block hover-lift">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center position-relative" style="z-index: 1;">
                <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                    @if($client->photo_url)
                        <img src="{{ asset($client->photo_url) }}" class="avatar-large" alt="{{ $client->name }}">
                    @else
                        <div class="avatar-large">
                            {{ substr($client->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="col-md">
                    <h2 class="fw-bold mb-1 text-dark">{{ $client->name }}</h2>
                    <div class="d-flex flex-wrap gap-3 mb-3 text-muted">
                        <span><i class="bi bi-geo-alt-fill me-1 text-danger"></i>{{ $client->address }}</span>
                        <span><i class="bi bi-envelope-fill me-1 text-primary"></i>{{ $client->email }}</span>
                        <span><i class="bi bi-telephone-fill me-1 text-success"></i>{{ $client->phone }}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="stat-badge">
                            <i class="bi bi-calendar-event me-1"></i>
                            <span class="fw-bold">Joined {{ $client->created_at->format('M Y') }}</span>
                        </div>
                        <div class="stat-badge">
                            <i class="bi bi-journal-check me-1"></i>
                            <span class="fw-bold">{{ $client->bookings->count() }}</span> Bookings
                        </div>
                    </div>
                </div>
                <div class="col-md-auto mt-3 mt-md-0">
                    <a href="mailto:{{ $client->email }}" class="btn btn-contact rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-envelope me-2"></i>Email Client
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Bookings Section -->
            <div class="col-lg-7">
                <div class="card glass-card h-100">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="bi bi-calendar-week text-danger"></i> Booking History
                            <span class="badge bg-light text-muted border border-secondary rounded-pill ms-auto">{{ $client->bookings->count() }}</span>
                        </h5>
                        
                        @if($client->bookings->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($client->bookings as $booking)
                                    <div class="list-group-item px-0 py-3 booking-item">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold">
                                                {{ $booking->service_name ?? 'Service' }} 
                                                <span class="text-muted fw-normal">with</span> <span class="text-dark">{{ $booking->beautician->name ?? 'Unknown' }}</span>
                                            </h6>
                                            @php
                                                $badgeClass = match($booking->status) {
                                                    'completed' => 'badge-status-completed',
                                                    'pending' => 'badge-status-pending',
                                                    default => 'badge-status-other',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} rounded-pill">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($booking->time)->format('h:i A') }}
                                            </small>
                                            <span class="fw-bold text-dark">₱{{ number_format($booking->price ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-50"></i>
                                No bookings found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Reviews Written Section -->
            <div class="col-lg-5">
                <div class="card glass-card h-100">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="bi bi-pencil-square text-danger"></i> Reviews Written
                            <span class="badge bg-light text-muted border border-secondary rounded-pill ms-auto">{{ $client->reviews->count() }}</span>
                        </h5>

                        <div style="max-height: 500px; overflow-y: auto; padding-right: 5px;">
                            @forelse($client->reviews as $review)
                                <div class="review-card">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-dark text-white border border-secondary me-2 d-flex align-items-center justify-content-center rounded-circle" style="width:32px; height:32px; font-size: 0.8rem;">
                                                {{ substr($review->beautician->name ?? 'B', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">To: {{ $review->beautician->name ?? 'Unknown' }}</div>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $review->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                        <div class="text-warning small">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-0 fst-italic">"{{ $review->comment }}"</p>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-chat-square-text fs-1 d-block mb-2 opacity-50"></i>
                                    No reviews written yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
