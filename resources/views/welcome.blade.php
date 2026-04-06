@extends('layouts.app')

@section('content')
<!-- Fonts & Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ====== System Theme Overrides ====== */
    :root {
        --primary: #D63384;      /* Deep Pink */
        --primary-hover: #b02a6c;
        --primary-light: #ffe5f0;
        --secondary: #20c997;    /* Teal */
        --accent: #fd7e14;       /* Orange */
        --bg-pink: #FFF5F7;      /* System Background */
        --card-bg: #ffffff;
        --text-dark: #212529;
        --text-muted: #6c757d;
        --radius: 16px;
    }

    /* Override layouts.app body background */
    body {
        background: var(--bg-pink) !important;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-dark);
        overflow-x: hidden;
    }

    /* Navbar */
    .navbar {
        background: transparent !important;
        backdrop-filter: none !important;
        padding: 1rem 0;
        transition: all 0.3s;
    }
    .navbar-brand {
        font-weight: 800;
        color: var(--text-dark) !important;
        font-size: 1.6rem;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .nav-link {
        font-weight: 600;
        color: var(--text-muted) !important;
        transition: color 0.3s;
    }
    .nav-link:hover {
        color: var(--primary) !important;
    }

    /* Buttons */
    .btn-glam {
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 28px;
        border-radius: 50px;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(214, 51, 132, 0.3);
        transition: all 0.3s ease;
    }
    .btn-glam:hover {
        background: var(--primary-hover);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(214, 51, 132, 0.4);
    }
    .btn-outline-glam {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        padding: 10px 28px;
        border-radius: 50px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-outline-glam:hover {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 15px rgba(214, 51, 132, 0.2);
    }

    /* Hero Section */
    .hero-section {
        padding: 140px 0 100px;
        position: relative;
        overflow: hidden;
    }
    .hero-title {
        font-weight: 800;
        color: var(--primary);
        font-size: 4rem;
        line-height: 1.1;
        margin-bottom: 1.5rem;
    }
    .hero-title span {
        color: var(--text-dark);
    }
    .hero-image-container {
        position: relative;
        z-index: 1;
    }
    .hero-img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 30px;
        box-shadow: 0 20px 40px rgba(214, 51, 132, 0.15);
        /* Use the existing bg1.png if suitable, or a placeholder */
        background: url('{{ asset("view-asset/bg1.png") }}') center center/cover no-repeat;
    }
    /* Floating Elements */
    .floating-card {
        position: absolute;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 15px;
        animation: float 6s ease-in-out infinite;
        z-index: 2;
    }
    .fc-1 { bottom: 40px; left: -30px; }
    .fc-2 { top: 40px; right: -30px; animation-delay: 2s; }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }

    /* Services Section */
    .service-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(214, 51, 132, 0.1);
        height: 100%;
    }
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(214, 51, 132, 0.1);
        border-color: rgba(214, 51, 132, 0.3);
    }
    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }

    /* Role Cards */
    .role-card {
        padding: 3rem;
        border-radius: 30px;
        height: 100%;
        transition: transform 0.3s;
    }
    .role-card:hover {
        transform: scale(1.02);
    }
    .bg-client {
        background: white;
        border: 1px solid rgba(214, 51, 132, 0.1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .bg-pro {
        background: linear-gradient(135deg, #212529 0%, #343a40 100%);
        color: white;
    }

    @media (max-width: 991px) {
        .hero-title { font-size: 3rem; }
        .hero-section { padding-top: 100px; text-align: center; }
        .hero-image-container { margin-top: 3rem; }
        .floating-card { display: none; }
        .fc-1 { bottom: -20px; left: 0; right: 0; margin: auto; width: fit-content; }
    }
    @media (max-width: 576px) {
        .hero-title { font-size: 2.4rem; }
        .hero-section { padding: 100px 0 60px; }
        .service-card { padding: 1.5rem; }
        .role-card { padding: 2rem; }
    }
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <img src="{{ asset('view-asset/photo_2026-02-04_22-17-58.png') }}" alt="HomeGlam Logo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"> HomeGlam
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-lg-4">
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#roles">Join Us</a></li>
                <li class="nav-item d-flex gap-2 ms-lg-2">
                    <a href="{{ route('client.login') }}" class="btn btn-outline-glam px-4 py-2" style="font-size: 0.9rem;">Log In</a>
                    <a href="{{ route('client.register') }}" class="btn btn-glam px-4 py-2" style="font-size: 0.9rem;">Sign Up</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="badge rounded-pill bg-white text-primary border border-primary px-3 py-2 mb-3 shadow-sm">
                    ✨ Beauty at your doorstep
                </span>
                <h1 class="hero-title">
                    Your Beauty, <br>
                    <span>Anytime, Anywhere.</span>
                </h1>
                <p class="lead text-muted mb-5 pe-lg-5">
                    Experience premium beauty treatments in the comfort of your home. 
                    From hair styling to skincare, book trusted professionals in just a few clicks.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('client.login') }}" class="btn btn-glam btn-lg px-5">Book Appointment</a>
                    <a href="{{ route('beautician.login') }}" class="btn btn-outline-glam btn-lg px-5">Serve now!</a>
                </div>
                
                <div class="mt-5 d-flex align-items-center gap-4 justify-content-center justify-content-lg-start">
                    <div class="d-flex">
                        <div class="bg-light rounded-circle border border-white" style="width:40px; height:40px; margin-left: -10px;"></div>
                        <div class="bg-light rounded-circle border border-white" style="width:40px; height:40px; margin-left: -10px;"></div>
                        <div class="bg-light rounded-circle border border-white" style="width:40px; height:40px; margin-left: -10px;"></div>
                    </div>
                    <div>
                        <div class="d-flex text-warning small">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <small class="text-muted fw-bold">Loved by 500+ Clients</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="hero-image-container">
                    <div class="hero-img"></div>
                    
                    <!-- Floating Card 1 -->
                    <div class="floating-card fc-1">
                        <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Verified Pros</h6>
                            <small class="text-muted">Safety First</small>
                        </div>
                    </div>
                    
                    <!-- Floating Card 2 -->
                    <div class="floating-card fc-2">
                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Save Time</h6>
                            <small class="text-muted">No Travel Needed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase letter-spacing-2">Our Services</h6>
            <h2 class="fw-bold display-6">Everything You Need to Glow</h2>
        </div>
        
        <div class="row g-4">
            <!-- Hair -->
            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-circle" style="background: #e0cffc; color: #6610f2;">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Hair</h4>
                    <p class="text-muted small mb-0">Styling, coloring, and treatments by expert hairdressers.</p>
                </div>
            </div>
            
            <!-- Makeup -->
            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-circle" style="background: #ffe5d0; color: #fd7e14;">
                        <i class="bi bi-palette"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Makeup</h4>
                    <p class="text-muted small mb-0">Professional makeup for weddings, events, and parties.</p>
                </div>
            </div>
            
            <!-- Nails -->
            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-circle" style="background: #d2f4ea; color: #20c997;">
                        <i class="bi bi-magic"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Nails</h4>
                    <p class="text-muted small mb-0">Manicures, pedicures, and nail art at your convenience.</p>
                </div>
            </div>
            
            <!-- Massage -->
            <div class="col-lg-3 col-md-6">
                <div class="service-card">
                    <div class="icon-circle" style="background: #f8d7da; color: #dc3545;">
                        <i class="bi bi-flower1"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Massage</h4>
                    <p class="text-muted small mb-0">Relaxing therapeutic massages to relieve stress.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Roles Section -->
<section id="roles" class="py-5">
    <div class="container py-5">
        <div class="row g-4">
            <!-- For Clients -->
            <div class="col-lg-6">
                <div class="role-card bg-client">
                    <span class="badge bg-primary-light text-primary mb-3 px-3 py-2 rounded-pill">For Clients</span>
                    <h2 class="fw-bold mb-3">Book Your Glow Up</h2>
                    <p class="text-muted mb-4 lead">Get matched with top-rated beauty professionals in your area. View portfolios, read reviews, and book instantly.</p>
                    
                    <ul class="list-unstyled mb-5 d-flex flex-column gap-3">
                        <li class="d-flex align-items-center gap-3">
                            <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                            <span class="fw-medium">Verified Professionals</span>
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                            <span class="fw-medium">Transparent Pricing</span>
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                            <span class="fw-medium">Secure Online Payments</span>
                        </li>
                    </ul>
                    
                    <a href="{{ route('client.register') }}" class="btn btn-glam btn-lg w-100">Sign Up as Client</a>
                </div>
            </div>
            
            <!-- For Beauticians -->
            <div class="col-lg-6">
                <div class="role-card bg-pro">
                    <span class="badge bg-light text-dark mb-3 px-3 py-2 rounded-pill">For Professionals</span>
                    <h2 class="fw-bold mb-3 text-white">Grow Your Business</h2>
                    <p class="text-white-50 mb-4 lead">Join our network of elite beauty professionals. We handle the bookings and marketing so you can focus on your craft.</p>
                    
                    <ul class="list-unstyled mb-5 d-flex flex-column gap-3">
                        <li class="d-flex align-items-center gap-3 text-white">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <span class="fw-medium">Be Your Own Boss</span>
                        </li>
                        <li class="d-flex align-items-center gap-3 text-white">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <span class="fw-medium">Set Your Own Schedule</span>
                        </li>
                        <li class="d-flex align-items-center gap-3 text-white">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <span class="fw-medium">Guaranteed Payments</span>
                        </li>
                    </ul>
                    
                    <a href="{{ route('beautician.register') }}" class="btn btn-light btn-lg w-100 text-dark fw-bold">Join as Beautician</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-white py-5 border-top">
    <div class="container text-center">
        <div class="mb-4">
            <a href="#" class="d-inline-flex align-items-center gap-2 text-decoration-none">
                <i class="bi bi-gem fs-2 text-primary"></i>
                <span class="fw-bold fs-3 text-dark">HomeGlam</span>
            </a>
        </div>
        <div class="d-flex justify-content-center gap-4 mb-4">
            <a href="#" class="text-muted text-decoration-none">About Us</a>
            <a href="#" class="text-muted text-decoration-none">Services</a>
            <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
            <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
        </div>
        <div class="d-flex justify-content-center gap-3 mb-4">
            <a href="#" class="btn btn-light rounded-circle p-2"><i class="bi bi-facebook text-primary"></i></a>
            <a href="#" class="btn btn-light rounded-circle p-2"><i class="bi bi-instagram text-primary"></i></a>
            <a href="#" class="btn btn-light rounded-circle p-2"><i class="bi bi-twitter-x text-primary"></i></a>
        </div>
        <p class="text-muted small mb-0">&copy; {{ date('Y') }} HomeGlam. All rights reserved.</p>
    </div>
</footer>
@endsection

