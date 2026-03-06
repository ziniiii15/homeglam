@extends('layouts.app')

@section('content')
<!-- Add Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-pink: #D63384;
        --neon-pink: #ff69b4;
        --dark-bg: #050505;
        --card-bg: #121212;
        --glass-bg: rgba(18, 18, 18, 0.85);
        --text-main: #ffffff;
        --text-muted: #b0b0b0;
        --border-color: rgba(214, 51, 132, 0.3);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--dark-bg);
        color: var(--text-main);
    }

    .admin-wrapper {
        min-height: 100vh;
        padding-bottom: 3rem;
        background: radial-gradient(circle at top right, #2a0a18, #000000 40%);
    }
    
    .glass-card {
        background: var(--glass-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(12px);
    }

    .profile-header {
        background: linear-gradient(135deg, #343a40 0%, #000000 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
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
        background: rgba(255,255,255,0.05);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.1);
        color: var(--text-muted);
    }
    .stat-badge i { color: var(--neon-pink); }
    .stat-badge span { color: white; }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .service-item {
        background: transparent;
        transition: transform 0.2s;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        color: var(--text-muted);
        border-left: 3px solid transparent;
    }
    .service-item:hover {
        transform: translateX(5px);
        background-color: rgba(214, 51, 132, 0.05);
        color: white;
        border-left-color: var(--neon-pink);
    }
    .service-item h6 { color: white; }

    .review-card {
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    .review-card:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
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
    
    .badge-pink-subtle {
        background-color: rgba(214, 51, 132, 0.15);
        color: #ff85c1;
        border: 1px solid rgba(214, 51, 132, 0.3);
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
                    @if($beautician->photo_url)
                        <img src="{{ asset($beautician->photo_url) }}" class="avatar-large" alt="{{ $beautician->name }}">
                    @else
                        <div class="avatar-large">
                            {{ substr($beautician->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="col-md">
                    <h2 class="fw-bold mb-1 text-white">{{ $beautician->name }}</h2>
                    <div class="d-flex flex-wrap gap-3 mb-3 text-white-50">
                        <span><i class="bi bi-geo-alt-fill me-1 text-danger"></i>{{ $beautician->base_location }}</span>
                        <span><i class="bi bi-envelope-fill me-1 text-primary"></i>{{ $beautician->email }}</span>
                        <span><i class="bi bi-telephone-fill me-1 text-success"></i>{{ $beautician->phone }}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="stat-badge">
                            <i class="bi bi-briefcase me-1"></i>
                            <span class="fw-bold">{{ $beautician->experience }}</span> Experience
                        </div>
                        <div class="stat-badge">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            <span class="fw-bold">{{ number_format($beautician->reviews->avg('rating'), 1) }}</span> Rating
                        </div>
                        <div class="stat-badge">
                            <i class="bi bi-calendar-check me-1"></i>
                            <span class="fw-bold">{{ $beautician->bookings->count() }}</span> Bookings
                        </div>
                    </div>
                </div>
                <div class="col-md-auto mt-3 mt-md-0">
                    <a href="mailto:{{ $beautician->email }}" class="btn btn-contact rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-envelope me-2"></i>Contact
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Services Section -->
            <div class="col-lg-7">
                <div class="card glass-card h-100">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="bi bi-magic text-white"></i> Services Offered
                            <span class="badge bg-dark text-white border border-secondary rounded-pill ms-auto">{{ $beautician->serviceList->count() }}</span>
                        </h5>
                        
                        @if($beautician->serviceList->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($beautician->serviceList as $service)
                                    <div class="list-group-item px-0 py-3 service-item">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold">{{ $service->service_name }}</h6>
                                            <span class="fs-5 fw-bold text-white">₱{{ number_format($service->base_price, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-white-50">{{ Str::limit($service->description, 60) }}</small>
                                            <span class="badge badge-pink-subtle rounded-pill">{{ $service->category }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x fs-1 d-block mb-2 opacity-50"></i>
                                No services listed yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="col-lg-5">
                <div class="card glass-card h-100">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="bi bi-chat-heart text-danger"></i> Recent Reviews
                            <span class="badge bg-danger-subtle text-danger rounded-pill ms-auto">{{ $beautician->reviews->count() }}</span>
                        </h5>

                        <div style="max-height: 500px; overflow-y: auto; padding-right: 5px;">
                            @forelse($beautician->reviews as $review)
                                <div class="review-card">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-dark text-white border border-secondary me-2 d-flex align-items-center justify-content-center rounded-circle" style="width:32px; height:32px; font-size: 0.8rem;">
                                                {{ substr($review->client->name ?? 'A', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-white" style="font-size: 0.9rem;">{{ $review->client->name ?? 'Anonymous' }}</div>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $review->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                        <div class="text-warning small">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-white-50 small mb-0 fst-italic">"{{ $review->comment }}"</p>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-star fs-1 d-block mb-2 opacity-50"></i>
                                    No reviews yet.
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
