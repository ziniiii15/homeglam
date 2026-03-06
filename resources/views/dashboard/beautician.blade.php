<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Beautician Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Leaflet & Routing Machine -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Confirm Delete Slot
    function confirmDeleteSlot(url) {
        const form = document.getElementById('deleteSlotForm');
        form.action = url;
        const modal = new bootstrap.Modal(document.getElementById('deleteSlotModal'));
        modal.show();
    }
</script>
<style>
:root {
    --primary: #D63384; /* Deep Pink */
    --primary-light: #F8D7DA;
    --secondary: #20c997; /* Teal */
    --secondary-light: #d2f4ea;
    --accent: #fd7e14; /* Orange */
    --accent-light: #ffe5d0;
    --bg: #FFF5F7; /* Very Light Pinkish Background */
    --card-bg: #ffffff;
    --text-dark: #212529;
    --text-muted: #6c757d;
    --card-radius: 16px;
    --shadow-sm: 0 2px 8px rgba(214, 51, 132, 0.1);
    --shadow-md: 0 6px 16px rgba(214, 51, 132, 0.15);
    --shadow-hover: 0 10px 24px rgba(214, 51, 132, 0.25);
    --border-color: rgba(214, 51, 132, 0.2);
}

/* Global Pink Balance */
h1, h2, h3, h4, h5, h6 { color: var(--primary); }
.appointment-item strong { color: var(--primary); }
.sidebar h6 { color: var(--primary); }
.mobile-header h4 { color: var(--primary) !important; }
.mobile-bottom-bar { border-top: 1px solid var(--border-color); }

:root.dark {
    --bg: #050505;
    --card-bg: #141414;
    --text-dark: #fff0f5;
    --text-muted: #d8b4c0;
    --primary: #FFA6C9; /* Carnation Pink */
    --primary-light: rgba(255, 166, 201, 0.2);
    --secondary-light: #0f4d3a;
    --accent-light: #522905;
    --border-color: rgba(255, 166, 201, 0.3);
    --shadow-sm: 0 2px 8px rgba(255, 166, 201, 0.1);
    --shadow-md: 0 6px 16px rgba(255, 166, 201, 0.15);
    --shadow-hover: 0 10px 24px rgba(255, 166, 201, 0.25);
}

/* Dark Mode Overrides */
.dark .bg-light { background-color: #1f1f1f !important; color: #fff0f5; }
.dark .form-control { background-color: #1f1f1f; border-color: var(--border-color); color: #fff0f5; }
.dark .form-control::placeholder { color: var(--text-muted); }
.dark .modal-content { background-color: var(--card-bg); border: 1px solid var(--border-color); }
.dark .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
.dark .btn-light { background-color: #2a2a2a; color: var(--primary); border-color: var(--border-color); }
.dark .btn-light:hover { background-color: #333; border-color: var(--primary); }
.dark .btn-primary { background-color: var(--primary); border-color: var(--primary); color: #000; font-weight: 700; box-shadow: 0 0 10px rgba(255, 166, 201, 0.4); }
.dark .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
.dark .btn-outline-primary:hover { background-color: var(--primary); color: #000; box-shadow: 0 0 10px rgba(255, 166, 201, 0.4); }
.dark .text-primary { color: var(--primary) !important; }
.dark a { color: var(--primary); }
.dark .nav-link { color: var(--text-muted); }
.dark .nav-link.active { background: var(--primary-light); color: var(--primary); border: 1px solid var(--border-color); }
.dark .card { background-color: var(--card-bg); border-color: var(--border-color); }
.dark .table { --bs-table-bg: transparent; --bs-table-hover-bg: rgba(255, 166, 201, 0.05); color: var(--text-dark); border-color: var(--border-color); }
.dark .table th { background-color: var(--card-bg); color: var(--primary); border-color: var(--border-color); }
.dark .table td { background-color: transparent; border-color: var(--border-color); }
.dark .badge-pending { background: #522905; color: #ffecb5; } /* Darker yellow for dark mode */
.dark .badge-accepted { background: #055160; color: #cff4fc; }
.dark .badge-completed { background: #0f5132; color: #d1e7dd; }
.dark .badge-canceled { background: #842029; color: #f8d7da; }

/* Mobile Bottom Bar Icons Dark Mode */
.dark .mobile-bottom-bar img, .dark .mobile-bottom-bar i {
    filter: invert(1) sepia(1) saturate(500%) hue-rotate(290deg) brightness(100%);
    color: var(--primary) !important;
}
.dark .mobile-bottom-bar button.active i {
    filter: drop-shadow(0 0 2px var(--primary));
    color: var(--primary);
}

/* Additional Dark Mode Overrides for Containers */
.dark .bg-white { background-color: var(--card-bg) !important; color: var(--text-dark); border-color: var(--border-color) !important; }
.dark .text-dark { color: var(--text-dark) !important; }
.dark .text-muted { color: var(--text-muted) !important; }
.dark .text-secondary { color: var(--text-muted) !important; }
.dark .border, .dark .border-top, .dark .border-bottom, .dark .border-end, .dark .border-start { border-color: var(--border-color) !important; }

/* Text Color Overrides for Dark Mode */
.dark .text-success { color: #75b798 !important; }
.dark .text-danger { color: #ea868f !important; }
.dark .text-warning { color: #ffda6a !important; }
.dark .text-info { color: #6edff6 !important; }

/* Subtle Backgrounds for Dark Mode */
.dark .bg-primary-subtle { background-color: rgba(214, 51, 132, 0.2) !important; color: var(--primary) !important; }
.dark .bg-success-subtle { background-color: rgba(25, 135, 84, 0.2) !important; color: #75b798 !important; }
.dark .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.2) !important; color: #ffda6a !important; }
.dark .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.2) !important; color: #ea868f !important; }
.dark .bg-info-subtle { background-color: rgba(13, 202, 240, 0.2) !important; color: #6edff6 !important; }

/* Mobile Carousel for Services */
@media (max-width: 991px) {
    .mobile-service-carousel {
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        padding-bottom: 0.5rem;
        margin-right: -1rem; /* Adjust for container padding */
        padding-right: 1rem;
    }
    .mobile-service-carousel > div {
        flex: 0 0 100%;
        width: 100%;
        max-width: 100%;
        scroll-snap-align: start;
        padding-right: 0.75rem; /* Spacing between cards */
    }
    .mobile-service-carousel::-webkit-scrollbar {
        display: none;
    }
    .mobile-service-carousel {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
}

body {
    background: var(--bg);
    font-family: 'Plus Jakarta Sans', sans-serif;
    min-height: 100vh;
    color: var(--text-dark);
    font-size: 0.9rem;
    padding-bottom: 80px; /* Ensure space for bottom nav */
}
/* Hide scrollbar for Chrome, Safari and Opera */
/* body::-webkit-scrollbar {
    display: none;
} */
/* Hide scrollbar for IE, Edge and Firefox */
/* body {
    -ms-overflow-style: none;
    scrollbar-width: none;
} */

/* ====== SIDEBAR LEFT ====== */
.sidebar {
    width: 240px;
    background: var(--card-bg);
    border-right: 1px solid var(--border-color);
    padding: 1.5rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: sticky;
    top: 0;
    height: 100vh;
    z-index: 100;
}
.sidebar img { 
    width: 80px; 
    height: 80px; 
    border-radius: 50%; 
    object-fit: cover; 
    border: 3px solid var(--bg); 
    margin-bottom: 0.8rem;
    box-shadow: var(--shadow-sm);
}
.sidebar h6 {
    font-size: 1.1rem; 
    font-weight: 700;
    margin-bottom: 0.2rem;
    color: var(--text-dark);
}
.sidebar p { font-size: 0.85rem; color: var(--text-muted); }

.sidebar nav { margin-top: 2rem; width: 100%; }
.sidebar .nav-link { 
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-muted); 
    padding: 0.75rem 1rem; 
    border-radius: 12px; 
    font-weight: 600; 
    margin-bottom: 0.4rem; 
    transition: all 0.2s ease; 
    font-size: 0.9rem;
}
.sidebar .nav-link:hover, .sidebar .nav-link.active { 
    background: var(--primary-light); 
    color: var(--primary);
}
.sidebar-bottom { margin-top: auto; width: 100%; }
.sidebar-bottom .btn { 
    width: 100%; 
    font-size: 0.85rem; 
    border-radius: 12px; 
    padding: 0.6rem; 
    margin-bottom: 0.6rem; 
    font-weight: 600; 
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

/* ====== MAIN CONTENT ====== */
.main-content { flex-grow: 1; padding: 1.5rem 2rem; max-width: 1200px; margin: 0 auto; }

.welcome-card { 
    background: var(--card-bg);
    padding: 1.5rem 2rem; 
    border-radius: var(--card-radius); 
    box-shadow: var(--shadow-md); 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    border: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    height: 120px;
}
.welcome-card::after {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    width: 120px;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(111, 66, 193, 0.05));
}
.welcome-card h5 { font-weight: 800; font-size: 1.6rem; color: var(--text-dark); margin-bottom: 0.3rem; letter-spacing: -0.5px; }
.welcome-card p { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; }

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.stat-card {
    background: var(--card-bg);
    padding: 1.25rem;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 1rem;
}
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.stat-info h3 { font-weight: 700; margin: 0; font-size: 1.5rem; }
.stat-info span { color: var(--text-muted); font-size: 0.85rem; font-weight: 500; }

/* Tables */
.custom-table-card {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.custom-table-header {
    padding: 1.25rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.table-responsive {
    height: 350px;
    overflow-y: auto;
}
.table th {
    position: sticky;
    top: 0;
    z-index: 5;
    background: var(--bg);
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    padding: 1rem;
    color: var(--text-muted);
    box-shadow: 0 1px 0 var(--border-color); /* Separator for sticky header */
}
.table td {
    padding: 1rem;
    vertical-align: middle;
    font-size: 0.9rem;
}

/* Badges */
.badge-custom {
    padding: 0.5em 0.85em;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.3px;
}
.badge-pending { background: #fff3cd; color: #856404; }
.badge-accepted { background: #cff4fc; color: #055160; }
.badge-completed { background: #d1e7dd; color: #0f5132; }
.badge-canceled { background: #f8d7da; color: #842029; }

/* ====== RIGHT SIDEBAR ====== */
.right-sidebar { 
    width: 280px; 
    background: var(--card-bg); 
    border-left: 1px solid var(--border-color);
    padding: 1.5rem 1.2rem; 
    height: 100vh; 
    position: sticky; 
    top: 0; 
    overflow-y: auto; 
    box-shadow: none;
}
.right-sidebar h6 { font-weight: 700; margin-bottom: 1.2rem; font-size: 1rem; }

.schedule-card {
    background: var(--bg);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid var(--border-color);
}
.schedule-slot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    background: var(--card-bg);
    border-radius: 8px;
    margin-bottom: 0.5rem;
    border: 1px solid rgba(0,0,0,0.05);
    font-size: 0.85rem;
}
.schedule-slot.booked { border-left: 3px solid var(--danger-color, #dc3545); }
.schedule-slot.available { border-left: 3px solid var(--secondary-color, #20c997); }

/* Mobile Sidebar & Bottom Bar */
@media (max-width: 991px) {
    /* Background Image Layer */
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('{{ asset("bg1.png") }}') no-repeat center center fixed;
        background-size: cover;
        z-index: -2;
        transition: filter 0.3s ease;
    }
    .dark body::before { filter: brightness(0.2) grayscale(0.4); }

    .sidebar {
        position: fixed;
        bottom: 0;
        top: auto;
        width: 100%;
        height: auto;
        max-height: 85vh;
        overflow-y: auto;
        border-radius: 25px 25px 0 0;
        box-shadow: 0 -10px 40px rgba(0,0,0,0.1);
        transform: translateY(100%);
        opacity: 0;
        transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.3s ease;
        padding: 1.5rem;
        z-index: 3100;
        padding-bottom: 2rem;
    }
    .sidebar.show { transform: translateY(0); opacity: 1; }

    .right-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        width: 85%;
        max-width: 300px;
        height: 100%;
        border-radius: 0;
        border-left: none;
        box-shadow: -10px 0 40px rgba(0,0,0,0.1);
        transform: translateX(100%);
        opacity: 0;
        z-index: 3000;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
    .right-sidebar.show { transform: translateX(0); opacity: 1; }

    .main-content { padding: 1rem; padding-bottom: 80px; }
    
    .welcome-card { padding: 1.2rem; border-radius: 16px; text-align: left; align-items: flex-start; height: auto; }
    .welcome-card h5 { font-size: 1.4rem; }
    
    .mobile-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 65px;
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
        display: flex;
        justify-content: space-around;
        align-items: center;
        z-index: 2500;
        border-top: 1px solid rgba(0,0,0,0.05);
    }
    .mobile-bottom-bar button { background: none; border: none; padding: 10px; border-radius: 50%; transition: 0.2s; }
    .mobile-bottom-bar button:active { background: var(--bg); transform: scale(0.95); }
    .mobile-bottom-bar i { font-size: 1.5rem; color: var(--text-muted); }
    .services-list { max-height: 55vh; overflow-y: auto; -webkit-overflow-scrolling: touch; padding-right: 4px; margin-right: -4px; }
}

/* Modals */
.time-slot-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 10px;
    max-height: 250px;
    overflow-y: auto;
    padding: 10px;
}
.action-btn {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    border: none;
    font-size: 1.2rem;
}
.action-btn:hover { transform: scale(1.1); }

/* Horizontal Scroll for Slots */
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* Dark Mode Fix for Table Rows */
.dark .table-hover tbody tr td,
.dark .table-hover tbody tr td span.fw-semibold,
.dark .table-hover tbody tr td span.fw-medium,
.dark .table-hover tbody tr td small.text-muted {
    color: #ffffff !important;
}
.dark .table-hover tbody tr td small.text-muted {
    color: #e0e0e0 !important;
}

/* Map Text Overrides */
#map, .leaflet-routing-container {
    color: #000000 !important;
}
#map h1, #map h2, #map h3, #map h4, #map h5, #map h6,
.leaflet-routing-container h1, .leaflet-routing-container h2, .leaflet-routing-container h3,
.leaflet-popup-content {
    color: #000000 !important;
}
</style>
</head>
<body>

    <!-- Notification Container -->
    <div id="notification-container" class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999; width: 90%; max-width: 400px;"></div>

<div class="d-lg-flex">
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="d-flex align-items-center mb-3">
            <img src="{{ asset('images/logo.png') }}" alt="HomeGlam Logo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 0.75rem;">
            <span class="fw-bold" style="letter-spacing: -0.3px; font-size: 1.1rem;">HomeGlam</span>
        </div>
        <img src="{{ auth()->guard('beautician')->user()->photo_url ?? 'https://via.placeholder.com/150' }}" alt="Profile">
        <h6 class="text-truncate" style="max-width: 100%;">{{ auth()->guard('beautician')->user()->name }}</h6>
        <p>Beautician</p>
        
        <nav class="nav flex-column">
            <a class="nav-link active" href="#"><i class="bi bi-grid-fill"></i> Dashboard</a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#availabilityModal"><i class="bi bi-clock"></i> Availability</a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#scheduleModal"><i class="bi bi-calendar-check"></i> Schedules</a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#completedAppointmentsModal"><i class="bi bi-journal-check"></i> Completed</a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#transactionsModal"><i class="bi bi-receipt-cutoff"></i> Transactions</a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#cancelledAppointmentsModal"><i class="bi bi-x-circle"></i> Cancelled</a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal"><i class="bi bi-person-gear"></i> Edit Profile</a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#portfolioModal"><i class="bi bi-images"></i> Portfolio</a>
            <a class="nav-link text-primary fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#setLocationModal"><i class="bi bi-geo-alt-fill"></i> Set Current Location</a>
        </nav>

        <div class="sidebar-bottom">
            <button class="btn btn-outline-secondary mb-2" id="themeToggleBtn">
                <i class="bi bi-moon-stars"></i> Dark Mode
            </button>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Mobile Header -->
        <div class="d-flex align-items-center d-lg-none mb-3 mobile-header">
            <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle me-2" style="width: 35px; height: 35px;">
                 <i class="bi bi-stars"></i>
            </div>
            <h4 class="fw-bold mb-0" style="letter-spacing: -0.5px;">HomeGlam</h4>
        </div>

        <div class="welcome-card">
            <div>
                <h5>Welcome back, {{ explode(' ', auth()->guard('beautician')->user()->name)[0] }}! <span style="font-size:1.5rem;">✨</span></h5>
                <p class="rotating-text mb-0">Here's what's happening with your appointments.</p>
            </div>
            <div class="d-none d-md-block">
                <div class="bg-white text-dark px-4 py-2 rounded-4 shadow-sm border">
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Date</small>
                    <span class="fw-bold">{{ now()->format('F d, Y') }}</span>
                </div>
            </div>
        </div>

        @php 
            $upcomingBookings = $upcomingBookings ?? []; 
            $pendingCount = $upcomingBookings->where('status', 'pending')->count();
            $acceptedCount = $upcomingBookings->where('status', 'accepted')->count();
            $totalCount = $upcomingBookings->count();
            $maxBookings = auth()->guard('beautician')->user()->max_bookings;
        @endphp

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $pendingCount }}</h3>
                    <span>Pending Requests</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $acceptedCount }}</h3>
                    <span>Confirmed</span>
                </div>
            </div>
            <div class="stat-card d-block p-0 overflow-hidden position-relative">
                <div class="d-flex overflow-auto no-scrollbar w-100" style="scroll-snap-type: x mandatory; scroll-behavior: smooth;">
                    
                    <!-- Slide 1: Daily Capacity Summary -->
                    <div class="w-100 flex-shrink-0 d-flex align-items-center p-3" style="scroll-snap-align: start; min-width: 100%;">
                        <div class="stat-icon bg-primary-subtle text-primary me-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-info flex-grow-1">
                            <h3 class="mb-0">{{ $maxBookings }}</h3>
                            <span class="text-muted small">Daily Capacity</span>
                        </div>
                        <div class="text-muted opacity-25 small animate-pulse">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </div>

                    <!-- Slides: Today's Slots -->
                    @foreach($todaySchedules ?? [] as $slot)
                    <div class="w-100 flex-shrink-0 d-flex align-items-center p-3 position-relative" style="scroll-snap-align: start; min-width: 100%;">
                        <div class="stat-icon {{ $slot->status === 'booked' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} me-3">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="stat-info flex-grow-1">
                            <h5 class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($slot->booking_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</h5>
                            <span class="badge {{ $slot->status === 'booked' ? 'bg-danger' : 'bg-success' }}">{{ ucfirst($slot->status) }}</span>
                        </div>
                        
                        <!-- Delete Action -->
                        <button type="button" 
                                class="btn btn-sm btn-light text-danger border rounded-circle shadow-sm ms-2" 
                                style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"
                                data-delete-url="{{ route('beautician.schedules.destroy', $slot->id) }}"
                                onclick="confirmDeleteSlot(this.dataset.deleteUrl)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    @endforeach
                    
                </div>
            </div>
            
            <!-- Availability Time (Mobile) -->
            <div class="stat-card d-lg-none">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-info">
                    @if ($todayAvailability && $todayAvailability->status !== 'inactive')
                        <h5 class="mb-0 fw-bold" style="font-size: 0.95rem;">
                            {{ \Carbon\Carbon::parse($todayAvailability->start_time)->format('h:i A') }} - 
                            {{ \Carbon\Carbon::parse($todayAvailability->end_time)->format('h:i A') }}
                        </h5>
                        <span class="text-muted small">Today's Availability</span>
                    @else
                        <h5 class="mb-0 fw-bold text-muted" style="font-size: 0.9rem;">Unavailable</h5>
                        <span class="text-muted small">No hours set</span>
                    @endif
                </div>
            </div>
        </div>



        <!-- Portfolio Modal -->
        <div class="modal fade" id="portfolioModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0">
                        <div>
                            <h5 class="modal-title fw-bold">My Portfolio</h5>
                            <small class="text-muted">Showcase your best work to clients</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addGalleryModal">
                                <i class="bi bi-plus-lg me-1"></i>Add Photo
                            </button>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="modal-body p-4">
                        @if($beautician->galleries->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3 text-muted opacity-25">
                                    <i class="bi bi-images" style="font-size: 3rem;"></i>
                                </div>
                                <h6 class="text-muted">No photos yet</h6>
                                <p class="small text-muted">Add photos to attract more clients!</p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($beautician->galleries as $gallery)
                                <div class="col-6 col-md-4">
                                    <div class="position-relative rounded-3 overflow-hidden group-hover-action" style="aspect-ratio: 1;">
                                        <img src="{{ asset($gallery->image_url) }}" alt="Gallery Image" class="w-100 h-100 object-fit-cover">
                                        <div class="position-absolute top-0 end-0 p-2">
                                            <form action="{{ route('beautician.gallery.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('Delete this photo?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @if($gallery->description)
                                        <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 p-2 text-white small text-truncate">
                                            {{ $gallery->description }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Gallery Modal -->
        <div class="modal fade" id="addGalleryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title fw-bold">Add to Portfolio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('beautician.gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Photo</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description (Optional)</label>
                                <input type="text" name="description" class="form-control" placeholder="e.g. Bridal Makeup">
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings Table -->
        <div class="custom-table-card">
            <div class="custom-table-header">
                <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-event me-2"></i> Upcoming Bookings</h5>
                <span class="badge bg-light text-dark border">{{ $pendingCount }} Pending</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Location</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingBookings as $booking)
                            @if($booking->status === 'accepted' || $booking->status === 'pending')
                            <tr style="cursor: pointer;" 
                                data-details="{{ json_encode([
                                    'id' => $booking->id,
                                    'clientName' => $booking->client->name,
                                    'clientPhone' => $booking->client->phone,
                                    'serviceName' => $booking->service->service_name,
                                    'date' => \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y'),
                                    'time' => \Carbon\Carbon::parse($booking->booking_time)->format('h:i A'),
                                    'location' => $booking->location,
                                    'clientLatitude' => $booking->client->latitude,
                                    'clientLongitude' => $booking->client->longitude,
                                    'totalCost' => $booking->total_cost,
                                    'discountAmount' => $booking->discount_amount ?? 0,
                                    'paymentStatus' => $booking->payment_status ?? 'pending',
                                    'downPayment' => $booking->down_payment_amount ?? 0,
                                    'notes' => $booking->client_notes ?? 'None',
                                    'allergyInfo' => $booking->allergy_info,
                                    'status' => $booking->status,
                                    'paymentReceipt' => $booking->payment_receipt_path ? asset($booking->payment_receipt_path) : null,
                                    'paymentOption' => $booking->down_payment_amount >= $booking->total_cost ? 'Full Payment' : ($booking->down_payment_amount > 0 ? '50% Downpayment' : 'Pay Later (Cash)')
                                ]) }}"
                                onclick="openBookingDetailsModal(JSON.parse(this.getAttribute('data-details')))">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 text-primary fw-bold" style="width:35px; height:35px;">
                                            {{ substr($booking->client->name, 0, 1) }}
                                        </div>
                                        <span class="fw-semibold">{{ $booking->client->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $booking->service->service_name }}</td>
                                <td>{{ $booking->location }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d') }}</span>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($booking->status === 'pending')
                                        <span class="badge-custom badge-pending">Pending</span>
                                    @elseif($booking->status === 'accepted')
                                        <span class="badge-custom badge-accepted">Accepted</span>
                                    @endif
                                </td>
                                <td class="text-center" onclick="event.stopPropagation()">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('beautician.update_booking_status', $booking->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="accepted">
                                                <button class="action-btn bg-success-subtle text-success" title="Accept">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('beautician.update_booking_status', $booking->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="canceled">
                                                <button class="action-btn bg-danger-subtle text-danger" title="Decline">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @elseif($booking->status === 'accepted')
                                            <button type="button" 
                                                class="action-btn bg-info-subtle text-info" 
                                                title="View Directions" 
                                                data-client-address="{{ $booking->location ?? $booking->client->address ?? '' }}"
                                                data-client-lat="{{ $booking->client->latitude ?? '' }}"
                                                data-client-lng="{{ $booking->client->longitude ?? '' }}"
                                                onclick="openMapModal(this.getAttribute('data-client-address'), this.getAttribute('data-client-lat'), this.getAttribute('data-client-lng'))">
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </button>
                                            <form action="{{ route('beautician.update_booking_status', $booking->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button class="action-btn bg-primary-subtle text-primary" title="Complete">
                                                    <i class="bi bi-check2-all"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('beautician.update_booking_status', $booking->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="canceled">
                                                <button class="action-btn bg-danger-subtle text-danger" title="Cancel">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="text-muted fw-medium mb-0">No upcoming bookings found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- My Services Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0">My Services</h5>
            <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                <i class="bi bi-plus-lg me-1"></i> Add Service
            </button>
        </div>
        
        <div class="row g-3 services-list mobile-service-carousel">
            @foreach($services as $service)
            <div class="col-md-6 col-xl-4">
                <div class="service-card p-3 rounded-4 border bg-white h-100 d-flex flex-column justify-content-between shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $service->service_name }}</h6>
                            <span class="badge bg-light text-secondary border">{{ $service->category }}</span>
                            @if($service->discount_percentage > 0)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1">-{{ $service->discount_percentage }}% OFF</span>
                            @endif
                        </div>
                        <div class="text-end">
                            @if($service->discount_percentage > 0)
                                <span class="fw-bold text-danger">₱{{ number_format($service->base_price * (1 - $service->discount_percentage / 100), 2) }}</span>
                                <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.8rem;">₱{{ number_format($service->base_price, 2) }}</small>
                            @else
                                <span class="fw-bold text-primary">₱{{ number_format($service->base_price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($service->description, 60) }}</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-light flex-grow-1 text-primary"
                                data-service="{{ json_encode($service) }}"
                                onclick="openEditServiceModal(JSON.parse(this.dataset.service))">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-light flex-grow-1 text-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteServiceModal" 
                                data-service-id="{{ $service->id }}" 
                                data-service-name="{{ $service->service_name }}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Bottom Spacer for Mobile Scroll -->
        <div class="d-block d-lg-none" style="height: 300px; width: 100%;"></div>

    </div>

    <!-- Hidden Geo Data for JS -->
    <div id="beautician-geo-data" 
         data-lat="{{ $beautician->latitude ?? '' }}" 
         data-lng="{{ $beautician->longitude ?? '' }}" 
         style="display:none;"></div>

    <!-- ===== RIGHT SIDEBAR ===== -->
    <div class="right-sidebar" id="rightSidebar">
        <div class="d-lg-none d-flex align-items-center mb-3">
            <button id="closeRight" class="btn btn-light btn-sm me-2"><i class="bi bi-chevron-right"></i></button>
            <strong>Schedule</strong>
        </div>

        <!-- Current Location Display -->
        <div class="mb-4">
            <h6 class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-geo-alt-fill text-primary"></i> Current Location
            </h6>
            <div class="bg-light p-3 rounded-3 border">
                <p id="sidebarLocationText" class="mb-0 small text-muted">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="visually-hidden">Loading...</span>
                </p>
                <small id="sidebarLocationCoords" class="d-block text-muted mt-1 fst-italic" style="font-size: 0.7rem;"></small>
            </div>
        </div>
        
        <h6 class="d-flex align-items-center gap-2">
            <i class="bi bi-calendar-day"></i> Today's Schedule
        </h6>
        
        <!-- General Hours -->
        <div class="schedule-card">
            <label class="small text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.7rem;">General Hours</label>
            @if ($todayAvailability && $todayAvailability->status !== 'inactive')
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        {{ \Carbon\Carbon::parse($todayAvailability->start_time)->format('h:i A') }}
                        -
                        {{ \Carbon\Carbon::parse($todayAvailability->end_time)->format('h:i A') }}
                    </span>
                </div>
            @else
                <span class="text-muted small fst-italic">No general hours set</span>
            @endif
        </div>

        <!-- Appointment Slots -->
        <div>
            <label class="small text-muted d-block mb-2 text-uppercase fw-bold" style="font-size: 0.7rem;">Specific Slots</label>
            <div id="sidebarTodaySlots">
                @if(isset($todaySchedules) && count($todaySchedules) > 0)
                    <div class="d-flex flex-column gap-2">
                        @foreach($todaySchedules as $schedule)
                            <div class="schedule-slot {{ $schedule->status === 'booked' ? 'booked' : 'available' }}">
                                <span class="fw-medium">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                </span>
                                <span class="badge {{ $schedule->status === 'booked' ? 'bg-danger' : 'bg-success' }}" style="font-size: 0.65rem;">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0 fst-italic">No specific slots added.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ===== MODALS ===== -->

@if(isset($subscriptionExpired) && $subscriptionExpired)
<div class="modal fade" id="dashboardSubscriptionExpiredModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Subscription Expired</h5>
            </div>
            <div class="modal-body">
                <p class="mb-3">
                    Your beautician subscription has expired. To continue using your dashboard and accepting bookings, you need to renew your subscription.
                </p>
                <p class="mb-0 text-muted">
                    You can renew now or logout and renew later.
                </p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('beautician.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        Logout
                    </button>
                </form>
                <a href="{{ route('beautician.subscription_renew') }}" class="btn btn-primary">
                    Renew Subscription
                </a>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('dashboardSubscriptionExpiredModal');
    if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        return;
    }
    var modal = new bootstrap.Modal(modalEl);
    modal.show();
});
</script>
@endif

<!-- Completed Appointments Modal -->
<div class="modal fade" id="completedAppointmentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Completed Appointments & Reviews</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                @php
                    $completedBookings = $pastBookings->where('status', 'completed')->sortByDesc(function($booking) {
                        return $booking->booking_date . ' ' . $booking->booking_time;
                    });
                @endphp

                @if($completedBookings->count() > 0)
                    @foreach($completedBookings as $booking)
                        <div class="card mb-3 border-0 shadow-sm" style="border-radius: 16px;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-primary fw-bold" style="width:40px; height:40px;">
                                            {{ substr($booking->client->name ?? 'C', 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $booking->client->name ?? 'Client' }}</h6>
                                            <p class="text-muted small mb-0">{{ $booking->service->service_name ?? 'Service' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</small>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</small>
                                    </div>
                                </div>
                                
                                @if($booking->review)
                                    <div class="bg-light p-3 rounded-3 mt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $booking->review->rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($booking->review->created_at)->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0 small fst-italic">"{{ $booking->review->comment }}"</p>
                                        @if($booking->review->image_url)
                                            <div class="mt-2">
                                                <img src="{{ asset($booking->review->image_url) }}" alt="Review Image" class="img-fluid rounded-3" style="max-height: 150px; object-fit: cover;">
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-light p-3 rounded-3 mt-2">
                                        <p class="mb-0 small text-muted fst-italic">No review provided yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-check text-muted display-4 mb-3"></i>
                        <p class="text-muted">No completed appointments yet.</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancelled Appointments Modal -->
<div class="modal fade" id="cancelledAppointmentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Cancelled Appointments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                @php
                    $cancelledBookings = $pastBookings->where('status', 'canceled');
                @endphp

                @forelse($cancelledBookings as $booking)
                    <div class="card mb-3 border-0 shadow-sm appointment-item-hover" 
                         style="border-radius: 16px; cursor: pointer; transition: transform 0.2s;"
                         onmouseover="this.style.transform='scale(1.01)'" 
                         onmouseout="this.style.transform='scale(1)'"
                         data-details="{{ json_encode([
                            'id' => $booking->id,
                            'clientName' => $booking->client->name,
                            'clientPhone' => $booking->client->phone,
                            'serviceName' => $booking->service->service_name,
                            'date' => \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y'),
                            'time' => \Carbon\Carbon::parse($booking->booking_time)->format('h:i A'),
                            'location' => $booking->location,
                            'clientLatitude' => $booking->client->latitude,
                            'clientLongitude' => $booking->client->longitude,
                            'totalCost' => $booking->total_cost,
                            'discountAmount' => $booking->discount_amount ?? 0,
                            'paymentStatus' => $booking->payment_status ?? 'pending',
                            'downPayment' => $booking->down_payment_amount ?? 0,
                            'notes' => $booking->client_notes ?? 'None',
                            'allergyInfo' => $booking->allergy_info,
                            'status' => $booking->status,
                            'paymentReceipt' => $booking->payment_receipt_path ? asset($booking->payment_receipt_path) : null,
                            'paymentOption' => $booking->down_payment_amount >= $booking->total_cost ? 'Full Payment' : ($booking->down_payment_amount > 0 ? '50% Downpayment' : 'Pay Later (Cash)')
                         ]) }}"
                         onclick="openBookingDetailsModal(JSON.parse(this.getAttribute('data-details')))">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-danger fw-bold" style="width:40px; height:40px;">
                                        {{ substr($booking->client->name ?? 'C', 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $booking->service->service_name }}</h6>
                                        <p class="text-muted small mb-0">
                                            with {{ $booking->client->name }} <br>
                                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <span class="badge bg-danger rounded-pill">Cancelled</span>
                                    <small class="text-primary fw-bold" style="font-size: 0.8rem;">View Details</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-x-circle text-muted display-4 mb-3"></i>
                        <p class="text-muted">No cancelled appointments.</p>
                    </div>
                @endforelse
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="transactionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Payment Transactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                @php
                    $transactionBookings = $pastBookings->filter(function($booking) {
                        return !is_null($booking->payment_status);
                    });
                @endphp

                @if($transactionBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Booking</th>
                                    <th>Client</th>
                                    <th>Service</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Proof</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactionBookings as $booking)
                                    <tr>
                                        <td>#{{ $booking->id }}</td>
                                        <td>{{ $booking->client->name ?? '-' }}</td>
                                        <td>{{ $booking->service->service_name ?? '-' }}</td>
                                        <td class="fw-semibold">
                                            ₱{{ number_format($booking->down_payment_amount > 0 ? $booking->down_payment_amount : $booking->total_cost, 2) }}
                                        </td>
                                        <td>
                                            @php
                                                $paymentStatus = $booking->payment_status ?? 'pending';
                                            @endphp
                                            <span class="badge rounded-pill
                                                @if($paymentStatus === 'paid') bg-success-subtle text-success
                                                @elseif($paymentStatus === 'pending' || $paymentStatus === 'pending_verification') bg-warning-subtle text-warning
                                                @elseif($paymentStatus === 'refunded') bg-info-subtle text-info
                                                @else bg-secondary-subtle text-secondary @endif">
                                                {{ $paymentStatus === 'paid' ? 'Completed' : ucfirst($paymentStatus) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($booking->payment_receipt_path)
                                                <a href="{{ asset($booking->payment_receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                                    View Proof
                                                </a>
                                            @else
                                                <span class="text-muted small">Online / none</span>
                                            @endif
                                        </td>
                                        <td>{{ $booking->created_at ? $booking->created_at->format('M d, Y') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-receipt-cutoff text-muted display-4 mb-3"></i>
                        <p class="text-muted">No payment transactions found yet.</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Availability Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Set Daily Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-4">Set your working hours for a specific date.</p>
                <form action="{{ route('beautician.save_availability') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">DATE</label>
                        <input type="date" name="selected_date" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">START TIME</label>
                            <input type="time" name="start_time" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">END TIME</label>
                            <input type="time" name="end_time" class="form-control">
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_unavailable" id="isUnavailable">
                        <label class="form-check-label text-muted" for="isUnavailable">
                            Mark as Unavailable / Day Off
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill">Save Availability</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Slots Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Manage Slots</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Add specific time slots for clients to book.</p>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">DATE</label>
                    <input type="date" id="slotDate" class="form-control form-control-lg" min="{{ date('Y-m-d') }}">
                </div>

                <div id="slotList" class="mb-4">
                    <p class="text-muted small text-center">Select a date to view or add slots.</p>
                </div>

                <!-- Add Schedule Form -->
                <div class="bg-light p-3 rounded-3" id="addSlotForm" style="display:none;">
                    <h6 class="fw-bold mb-3">Add New Slot</h6>
                    <div class="row g-2 align-items-end">
                        <div class="col-5">
                            <label class="small text-muted">Start</label>
                            <input type="time" id="slotStartTime" class="form-control form-control-sm">
                        </div>
                        <div class="col-5">
                            <label class="small text-muted">End</label>
                            <input type="time" id="slotEndTime" class="form-control form-control-sm">
                        </div>
                        <div class="col-2">
                            <button onclick="addSlot()" class="btn btn-sm btn-primary w-100"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('beautician.update_profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="text-center mb-4">
                        <img src="{{ auth()->guard('beautician')->user()->photo_url ?? 'https://via.placeholder.com/100' }}" 
                             class="rounded-circle mb-3 shadow-sm" width="100" height="100" style="object-fit:cover;">
                        
                        <!-- Average Rating Display -->
                        <div class="mb-3">
                            <div class="text-warning fs-5">
                                @php $rating = $averageRating ?? 0; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rating ? '-fill' : ($i - 0.5 <= $rating ? '-half' : '') }}"></i>
                                @endfor
                            </div>
                            <p class="text-muted small mb-0">{{ number_format($rating, 1) }} Average Rating</p>
                        </div>

                        <div>
                            <label for="photoUpload" class="btn btn-sm btn-outline-primary rounded-pill">Change Photo</label>
                            <input type="file" id="photoUpload" name="photo" class="d-none">
                        </div>
                        
                        <div class="mt-3">
                            <label class="form-label fw-bold small text-muted d-block">PAYMENT QR CODE</label>
                            @if(auth()->guard('beautician')->user()->qr_code_path)
                                <img src="{{ asset(auth()->guard('beautician')->user()->qr_code_path) }}" class="img-fluid rounded border mb-2" style="max-height: 150px;">
                            @endif
                            <input type="file" name="qr_code" class="form-control form-control-sm">
                            <small class="text-muted fst-italic">Upload your GCash/Bank QR for manual payments.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">FULL NAME</label>
                        <input type="text" name="name" class="form-control" value="{{ auth()->guard('beautician')->user()->name }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">EMAIL</label>
                        <input type="email" name="email" class="form-control" value="{{ auth()->guard('beautician')->user()->email }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PHONE</label>
                        <input type="text" name="phone" class="form-control" value="{{ auth()->guard('beautician')->user()->phone }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NEW PASSWORD (Optional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">CONFIRM PASSWORD</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('beautician.services.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">SERVICE NAME</label>
                        <input type="text" name="service_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">CATEGORY</label>
                        <select name="category" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PRICE (₱)</label>
                        <input type="number" name="base_price" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">DISCOUNT (%)</label>
                        <input type="number" name="discount_percentage" class="form-control" min="0" max="100" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">DESCRIPTION</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill">Add Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Edit Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editServiceForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="service_id" id="editServiceId">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">SERVICE NAME</label>
                        <input type="text" name="service_name" id="editServiceName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">CATEGORY</label>
                        <select name="category" id="editServiceCategory" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PRICE (₱)</label>
                        <input type="number" name="base_price" id="editServicePrice" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">DISCOUNT (%)</label>
                        <input type="number" name="discount_percentage" id="editServiceDiscount" class="form-control" min="0" max="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">DESCRIPTION</label>
                        <textarea name="description" id="editServiceDescription" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill">Update Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Service Modal -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="bg-danger-subtle text-danger rounded-circle d-inline-flex p-3 mb-3">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                </div>
                <h5 class="fw-bold mb-2">Delete Service?</h5>
                <p class="text-muted small mb-4">Are you sure you want to delete <strong id="deleteServiceName"></strong>?</p>
                
                <form id="deleteServiceForm" action="{{ route('beautician.delete_service', 0) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="service_id" id="deleteServiceId">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger rounded-pill">Yes, Delete it</button>
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Slot Modal -->
<div class="modal fade" id="deleteSlotModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="bg-danger-subtle text-danger rounded-circle d-inline-flex p-3 mb-3">
                    <i class="bi bi-trash fs-3"></i>
                </div>
                <h5 class="fw-bold mb-2">Delete Slot?</h5>
                <p class="text-muted small mb-4">Are you sure you want to remove this time slot?</p>
                
                <form id="deleteSlotForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger rounded-pill">Yes, Remove it</button>
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to log out?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('beautician.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Set Location Modal -->
<div class="modal fade" id="setLocationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header bg-primary text-white border-bottom-0" style="border-radius: 16px 16px 0 0;">
                <h5 class="modal-title fw-bold"><i class="bi bi-geo-alt-fill me-2"></i>Set Current Location</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">
                    Enter your current location (e.g., "SM Mall of Asia") to update your status. 
                    This will be visible to clients when they book.
                </p>
                
                <div class="position-relative mb-4">
                    <label class="form-label fw-bold small text-uppercase text-muted">Search Location</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="manualLocationInput" class="form-control border-start-0" placeholder="Type an address or landmark..." autocomplete="off">
                    </div>
                    <!-- Suggestions Dropdown -->
                    <div id="manualLocationSuggestions" class="list-group position-absolute w-100 shadow-lg mt-1" 
                         style="z-index: 1050; max-height: 200px; overflow-y: auto; display: none; border-radius: 8px;">
                    </div>
                </div>

                <div class="d-grid">
                    <button type="button" id="saveManualLocationBtn" class="btn btn-primary rounded-pill fw-bold py-2" disabled>
                        Update Location
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-map-fill text-primary me-2"></i>Client Location</h5>
                <div class="d-flex align-items-center gap-2">
                    <a id="btnStreetView" href="#" target="_blank" class="btn btn-sm btn-warning text-white rounded-pill d-none">
                        <i class="bi bi-person-walking me-1"></i> 360° View
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="p-2 bg-light border-bottom">
                    <div class="input-group">
                        <input type="text" id="mapSearchInput" class="form-control" placeholder="Search specific location (e.g. Street, Landmark)...">
                        <button class="btn btn-primary" type="button" onclick="manualSearch()">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal with Manual Discount -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Booking Info -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" id="modalClientName">Client Name</h5>
                            <small class="text-muted" id="modalService">Service Name</small>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block mb-1">Date</small>
                                <span class="fw-bold" id="modalDate">Jan 01, 2026</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block mb-1">Time</small>
                                <span class="fw-bold" id="modalTime">10:00 AM</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block mb-1">Location</small>
                                <span class="fw-bold" id="modalLocation">123 Street</span>
                            </div>
                        </div>
                        <div class="col-12 d-none" id="modalAllergyContainer">
                            <div class="p-3 bg-danger-subtle text-danger rounded-3 text-center border border-danger">
                                <i class="bi bi-exclamation-triangle-fill mb-2 fs-4"></i>
                                <small class="d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Allergy Alert</small>
                                <span class="fw-bold" id="modalAllergyInfo"></span>
                            </div>
                        </div>
                         <div class="col-12">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block mb-1">Client Notes</small>
                                <span class="fst-italic text-dark" id="modalNotes">None</span>
                            </div>
                        </div>

                        <!-- Communication Actions -->
                        <div class="col-12">
                             <label class="small text-muted fw-bold mb-2">ACTIONS</label>
                             <div class="d-flex gap-2">
                                <button type="button" id="btnNotifyClient" class="btn btn-warning flex-grow-1 text-dark fw-bold" onclick="notifyClientOnTheWay()">
                                    <i class="bi bi-cursor-fill me-1"></i> On The Way
                                </button>
                                <a id="btnCallClient" href="#" class="btn btn-outline-success flex-grow-1">
                                    <i class="bi bi-telephone-fill me-1"></i> Call
                                </a>
                                <a id="btnMessageClient" href="#" class="btn btn-outline-primary flex-grow-1">
                                    <i class="bi bi-chat-dots-fill me-1"></i> Message
                                </a>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Financials & Discount -->
                <div class="border-top pt-4">
                    <h6 class="fw-bold mb-3">Payment & Discount</h6>
                    
                    <!-- Payment Receipt Display -->
                    <div id="modalReceiptContainer" class="mb-4 text-center d-none">
                        <label class="small text-muted fw-bold d-block mb-2">PAYMENT RECEIPT</label>
                        <a href="#" target="_blank" id="modalReceiptLink">
                            <img id="modalReceiptImage" src="" class="img-fluid rounded border shadow-sm" style="max-height: 300px;">
                        </a>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Payment Option</span>
                        <span class="fw-bold text-dark" id="modalPaymentOption">Full Payment</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Payment Status</span>
                        <span class="badge bg-secondary" id="modalPaymentStatus">Pending</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Down Payment (Paid)</span>
                        <span class="fw-bold text-success" id="modalDownPayment">₱0.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal (Original Price)</span>
                        <span class="fw-bold" id="modalOriginalPrice">₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount Applied</span>
                        <span class="fw-bold" id="modalDiscountAmount">-₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 pt-2 border-top">
                        <span class="fw-bold fs-5">Total To Pay</span>
                        <span class="fw-bold fs-5 text-primary" id="modalTotalCost">₱0.00</span>
                    </div>

                    <!-- Discount Form -->
                    <form id="discountForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <label class="form-label fw-bold small text-muted">ADD MANUAL DISCOUNT (₱)</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="discount_amount" class="form-control" placeholder="0.00" min="0" step="0.01" required>
                            <button class="btn btn-outline-primary" type="submit">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Location Not Found Modal -->
<div class="modal fade" id="locationNotFoundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-body text-center p-4">
                <div class="mb-3 text-warning">
                    <i class="bi bi-geo-alt-fill" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Location Not Found</h5>
                <p class="text-muted mb-4">We couldn't pinpoint the exact address on our map. You can view the approximate area or open Google Maps for better navigation.</p>
                <div class="d-grid gap-2">
                    <a id="btnGoogleMapsFallback" href="#" target="_blank" class="btn btn-primary rounded-pill fw-bold">
                        <i class="bi bi-google me-2"></i> Open in Google Maps
                    </a>
                    <button type="button" class="btn btn-light rounded-pill text-muted" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentBookingId = null;

    function openBookingDetailsModal(data) {
        currentBookingId = data.id;
        document.getElementById('modalClientName').textContent = data.clientName;
        document.getElementById('modalService').textContent = data.serviceName;
        document.getElementById('modalDate').textContent = data.date;
        document.getElementById('modalTime').textContent = data.time;
        document.getElementById('modalLocation').textContent = data.location;
        document.getElementById('modalNotes').textContent = data.notes;

        // Allergy Info
        const allergyContainer = document.getElementById('modalAllergyContainer');
        const allergyInfo = document.getElementById('modalAllergyInfo');
        
        if (data.allergyInfo) {
            allergyContainer.classList.remove('d-none');
            allergyInfo.textContent = data.allergyInfo;
        } else {
            allergyContainer.classList.add('d-none');
            allergyInfo.textContent = '';
        }

        // Payment Receipt
        const receiptContainer = document.getElementById('modalReceiptContainer');
        const receiptImage = document.getElementById('modalReceiptImage');
        const receiptLink = document.getElementById('modalReceiptLink');

        if (data.paymentReceipt) {
            receiptContainer.classList.remove('d-none');
            receiptImage.src = data.paymentReceipt;
            if(receiptLink) receiptLink.href = data.paymentReceipt;
        } else {
            receiptContainer.classList.add('d-none');
            receiptImage.src = '';
            if(receiptLink) receiptLink.href = '#';
        }

        // Payment Option
        const paymentOptionEl = document.getElementById('modalPaymentOption');
        if(paymentOptionEl) paymentOptionEl.textContent = data.paymentOption || 'N/A';

        // Financials
        const total = parseFloat(data.totalCost);
        const discount = parseFloat(data.discountAmount);
        const original = total + discount;

        // Payment Info
        const paymentStatusEl = document.getElementById('modalPaymentStatus');
        const downPaymentEl = document.getElementById('modalDownPayment');
        
        if (data.paymentStatus) {
            paymentStatusEl.textContent = data.paymentStatus.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            const pStatus = data.paymentStatus.toLowerCase();
            if (pStatus === 'paid') {
                paymentStatusEl.className = 'badge bg-success';
            } else if (pStatus === 'pending_verification') {
                paymentStatusEl.className = 'badge bg-info text-dark';
            } else {
                paymentStatusEl.className = 'badge bg-warning text-dark';
            }
        } else {
            paymentStatusEl.textContent = 'Pending';
            paymentStatusEl.className = 'badge bg-secondary';
        }
        
        const downPaymentVal = parseFloat(data.downPayment || 0);
        downPaymentEl.textContent = '₱' + downPaymentVal.toFixed(2);

        document.getElementById('modalOriginalPrice').textContent = '₱' + original.toFixed(2);
        document.getElementById('modalDiscountAmount').textContent = '-₱' + discount.toFixed(2);
        document.getElementById('modalTotalCost').textContent = '₱' + total.toFixed(2);

        // Update Communication Buttons
        const phone = data.clientPhone;
        document.getElementById('btnCallClient').href = `tel:${phone}`;
        document.getElementById('btnMessageClient').href = `sms:${phone}`;

        // Disable "On The Way" if status is not accepted
        const btnNotify = document.getElementById('btnNotifyClient');
        if(data.status !== 'accepted') {
            btnNotify.disabled = true;
            btnNotify.classList.add('opacity-50');
        } else {
            btnNotify.disabled = false;
            btnNotify.classList.remove('opacity-50');
        }

        // Update Form Action
        const form = document.getElementById('discountForm');
        form.action = `/beautician/bookings/${data.id}/discount`;

        new bootstrap.Modal(document.getElementById('bookingDetailsModal')).show();
    }

    function notifyClientOnTheWay() {
        if(!currentBookingId) return;
        
        const btn = document.getElementById('btnNotifyClient');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';

        fetch(`/beautician/bookings/${currentBookingId}/notify-client`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Client notified successfully!');
            } else {
                alert('Failed to notify client: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while sending notification.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
    }
</script>

<!-- ===== MOBILE BOTTOM BAR ===== -->
<div class="mobile-bottom-bar d-lg-none">
    <button id="openLeft" title="Menu">
        <i class="bi bi-list"></i>
    </button>
    <button id="mobileThemeToggle" title="Dark Mode">
        <i class="bi bi-moon-fill"></i>
    </button>
    <button onclick="new bootstrap.Modal(document.getElementById('editProfileModal')).show()" title="Profile">
        <i class="bi bi-person-circle"></i>
    </button>
    <button id="openRight" title="Schedule">
        <i class="bi bi-calendar-event"></i>
    </button>
</div>

<script>
    // Theme Toggle Logic
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    const mobileThemeToggle = document.getElementById('mobileThemeToggle');
    const html = document.documentElement;

    function setTheme(theme) {
        if (theme === 'dark') {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            if(themeToggleBtn) themeToggleBtn.innerHTML = '<i class="bi bi-sun"></i> Light Mode';
            if(mobileThemeToggle) mobileThemeToggle.innerHTML = '<i class="bi bi-sun-fill" style="font-size: 24px; color: var(--text-dark);"></i>';
        } else {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            if(themeToggleBtn) themeToggleBtn.innerHTML = '<i class="bi bi-moon-stars"></i> Dark Mode';
            if(mobileThemeToggle) mobileThemeToggle.innerHTML = '<i class="bi bi-moon-fill" style="font-size: 24px; color: var(--text-dark);"></i>';
        }
    }

    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);

    if(themeToggleBtn) themeToggleBtn.addEventListener('click', () => setTheme(html.classList.contains('dark') ? 'light' : 'dark'));
    if(mobileThemeToggle) mobileThemeToggle.addEventListener('click', () => setTheme(html.classList.contains('dark') ? 'light' : 'dark'));

    // Mobile Sidebar Toggles
    const sidebar = document.getElementById('sidebar');
    const rightSidebar = document.getElementById('rightSidebar');
    const openLeft = document.getElementById('openLeft');
    const openRight = document.getElementById('openRight');
    const closeRight = document.getElementById('closeRight');

    // Toggle Left
    if(openLeft) {
        openLeft.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            rightSidebar.classList.remove('show');
        });
    }

    // Toggle Right
    if(openRight) {
        openRight.addEventListener('click', (e) => {
            e.stopPropagation();
            rightSidebar.classList.toggle('show');
            sidebar.classList.remove('show');
        });
    }

    if(closeRight) {
        closeRight.addEventListener('click', () => rightSidebar.classList.remove('show'));
    }

    // Close sidebars when clicking outside
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !openLeft.contains(e.target)) {
            sidebar.classList.remove('show');
        }
        if (!rightSidebar.contains(e.target) && !openRight.contains(e.target)) {
            rightSidebar.classList.remove('show');
        }
    });

    if (sidebar) {
        sidebar.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-bs-toggle="modal"]');
            if (trigger && window.matchMedia('(max-width: 991px)').matches) {
                sidebar.classList.remove('show');
            }
        });
    }

    // Appointment Slots Logic
    const slotDate = document.getElementById('slotDate');
    const slotList = document.getElementById('slotList');
    const addSlotForm = document.getElementById('addSlotForm');
    const serverToday = "{{ \Carbon\Carbon::today()->toDateString() }}";

    if (slotDate) slotDate.addEventListener('change', fetchSlots);

    const formatTime12Hour = (timeStr) => {
        if (!timeStr) return '';
        const [h, m] = timeStr.split(':');
        const date = new Date();
        date.setHours(h);
        date.setMinutes(m);
        return date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true });
    };

    function fetchSlots() {
        const date = slotDate.value;
        if (!date) return;
        addSlotForm.style.display = 'block';
        slotList.innerHTML = '<p class="text-center text-muted">Loading...</p>';

        fetch(`{{ route('beautician.schedules.index') }}?date=${date}`)
            .then(res => res.json())
            .then(data => {
                slotList.innerHTML = '';
                if (data.length === 0) {
                    slotList.innerHTML = '<p class="text-center text-muted small">No slots added for this date.</p>';
                } else {
                    data.forEach(slot => {
                        const div = document.createElement('div');
                        div.className = 'd-flex justify-content-between align-items-center bg-white p-2 mb-2 rounded border';
                        div.innerHTML = `
                            <span class="small fw-bold">${formatTime12Hour(slot.start_time)} - ${formatTime12Hour(slot.end_time)}</span>
                            <div>
                                <span class="badge ${slot.status === 'booked' ? 'bg-danger' : 'bg-success'} me-2">${slot.status}</span>
                                <button onclick="deleteSlot(${slot.id})" class="btn btn-sm text-danger p-0 border-0"><i class="bi bi-trash"></i></button>
                            </div>
                        `;
                        slotList.appendChild(div);
                    });
                }
            })
            .catch(console.error);
    }

    function addSlot() {
        const date = slotDate.value;
        const start = document.getElementById('slotStartTime').value;
        const end = document.getElementById('slotEndTime').value;
        if (!date || !start || !end) return alert('Please fill all fields');

        fetch(`{{ route('beautician.schedules.store') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ date, start_time: start, end_time: end })
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) alert(data.error);
            else {
                fetchSlots();
                if (date === serverToday) location.reload(); // Refresh to update right sidebar
                document.getElementById('slotStartTime').value = '';
                document.getElementById('slotEndTime').value = '';
            }
        });
    }

    function deleteSlot(id) {
        if (!confirm('Delete this slot?')) return;
        const url = "{{ route('beautician.schedules.destroy', 'SLOT_ID') }}".replace('SLOT_ID', id);
        fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(res => {
            if (res.ok) {
                fetchSlots();
                if (slotDate.value === serverToday) location.reload();
            } else {
                alert('Failed to delete');
            }
        });
    }

    // Delete Service Modal Logic
    const deleteServiceModal = document.getElementById('deleteServiceModal');
    const deleteServiceForm = document.getElementById('deleteServiceForm');
    const baseDeleteAction = "{{ route('beautician.delete_service', 0) }}";

    if(deleteServiceModal) {
        deleteServiceModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-service-id');
            const name = button.getAttribute('data-service-name');
            document.getElementById('deleteServiceId').value = id;
            document.getElementById('deleteServiceName').textContent = name;
            deleteServiceForm.action = baseDeleteAction.replace('/0', '/' + id);
        });
    }

    // Rotating Text
    const rotatingText = document.querySelector('.rotating-text');
    if (rotatingText) {
        const messages = [
            "Here's what's happening with your appointments.",
            "Check your schedule for today.",
            "Don't forget to update your availability!",
            "Great job managing your bookings!"
        ];
        let index = 0;
        setInterval(() => {
            rotatingText.style.opacity = '0';
            setTimeout(() => {
                index = (index + 1) % messages.length;
                rotatingText.textContent = messages[index];
                rotatingText.style.opacity = '1';
            }, 500);
        }, 4000);
    }

    // Map & GPS Logic
    let map;
    let routeControl;
    let beauticianLocation = null;

    function openMapModal(clientAddress, lat = null, lng = null) {
        const myModal = new bootstrap.Modal(document.getElementById('mapModal'));
        myModal.show();
        
        // Store address to use after map is ready
        const modalEl = document.getElementById('mapModal');
        modalEl.setAttribute('data-address', clientAddress);
        if (lat && lng) {
            modalEl.setAttribute('data-lat', lat);
            modalEl.setAttribute('data-lng', lng);
        } else {
            modalEl.removeAttribute('data-lat');
            modalEl.removeAttribute('data-lng');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const mapModal = document.getElementById('mapModal');
        
        // Add Enter key listener for search
        const searchInput = document.getElementById('mapSearchInput');
        if(searchInput) {
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    manualSearch();
                }
            });
        }
        
        mapModal.addEventListener('shown.bs.modal', () => {
            // Focus on search input for convenience
            // document.getElementById('mapSearchInput').focus(); 
            
            if (!map) {
                // Base Layers
                const googleStreets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                    attribution: '&copy; Google Maps'
                });

                const googleHybrid = L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                    attribution: '&copy; Google Maps'
                });

                map = L.map('map', {
                    center: [14.5995, 120.9842],
                    zoom: 13,
                    layers: [googleStreets] // Default layer
                });

                // Layer Control
                const baseMaps = {
                    "Google Streets": googleStreets,
                    "Google Hybrid": googleHybrid
                };
                L.control.layers(baseMaps).addTo(map);

                // Add "Use My Location" Trigger Button
                const locateControl = L.Control.extend({
                    options: { position: 'topright' },
                    onAdd: function(map) {
                        const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                        const btn = L.DomUtil.create('a', 'leaflet-control-custom-location', container);
                        btn.href = "#";
                        btn.title = "Use My Location";
                        btn.innerHTML = '<i class="bi bi-crosshair" style="font-size: 1.2rem; line-height: 30px; display: block; text-align: center;"></i>';
                        btn.style.width = '30px';
                        btn.style.height = '30px';
                        btn.style.backgroundColor = 'white';
                        btn.style.cursor = 'pointer';
                        
                        btn.onclick = function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            startGeolocation(); 
                        };
                        return container;
                    }
                });
                map.addControl(new locateControl());
            }
            
            // Critical for Modal Rendering
            setTimeout(() => {
                map.invalidateSize();
            }, 100);

            // Robust Geolocation Function (Global to this scope)
            const startGeolocation = () => {
                if (!navigator.geolocation) {
                    console.error("Geolocation not supported by this browser.");
                    fallbackToManila();
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        beauticianLocation = L.latLng(lat, lng);

                        // 1. Center Map
                        map.setView(beauticianLocation, 16);

                        // 2. Add/Update Marker
                        map.eachLayer((layer) => {
                            if (layer instanceof L.Marker && layer.getPopup() && layer.getPopup().getContent().includes("You are here")) {
                                map.removeLayer(layer);
                            }
                        });
                        L.marker(beauticianLocation).addTo(map).bindPopup("You are here").openPopup();

                        // Update server with real-time location
                        fetch('{{ route("beautician.location.update") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                latitude: lat,
                                longitude: lng
                            })
                        }).then(() => {
                             // Update Sidebar Display (Reverse Geocode)
                            const sidebarLocText = document.getElementById('sidebarLocationText');
                            const sidebarLocCoords = document.getElementById('sidebarLocationCoords');
                            if (sidebarLocText) {
                                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        if(data && data.display_name) {
                                            sidebarLocText.innerText = data.display_name;
                                        } else {
                                            sidebarLocText.innerText = "Location set (Address not found)";
                                        }
                                        if(sidebarLocCoords) sidebarLocCoords.innerText = `(${lat}, ${lng})`;
                                    })
                                    .catch(() => {
                                        sidebarLocText.innerText = "Location set";
                                        if(sidebarLocCoords) sidebarLocCoords.innerText = `(${lat}, ${lng})`;
                                    });
                            }
                        }).catch(err => console.error('Location update failed:', err));

                        // 3. Trigger Route
                        processRoute();
                    },
                    (err) => {
                        console.warn("Geolocation Error:", err.code, err.message);
                        fallbackToManila();
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            };

            const fallbackToManila = () => {
                if (!beauticianLocation) {
                    beauticianLocation = L.latLng(14.5995, 120.9842);
                    map.setView(beauticianLocation, 13);
                }
            };

            const processRoute = () => {
                const clientLat = mapModal.getAttribute('data-lat');
                const clientLng = mapModal.getAttribute('data-lng');
                const clientAddress = mapModal.getAttribute('data-address');
                
                if (clientLat && clientLng) {
                    calculateRoute(clientAddress || "Client Location", parseFloat(clientLat), parseFloat(clientLng));
                } else if (clientAddress) {
                    calculateRoute(clientAddress);
                }
            };

            // Main Logic Flow
            const geoData = document.getElementById('beautician-geo-data').dataset;
            const storedLatModal = parseFloat(geoData.lat) || null;
            const storedLngModal = parseFloat(geoData.lng) || null;

            if (!beauticianLocation && storedLatModal && storedLngModal) {
                beauticianLocation = L.latLng(storedLatModal, storedLngModal);
                
                map.setView(beauticianLocation, 16);
                
                // Remove existing "You are here" markers
                map.eachLayer((layer) => {
                    if (layer instanceof L.Marker && layer.getPopup() && layer.getPopup().getContent().includes("You are here")) {
                        map.removeLayer(layer);
                    }
                });
                L.marker(beauticianLocation).addTo(map).bindPopup("You are here").openPopup();
            }

            if (beauticianLocation) {
                // If we already have a location, just use it and re-route
                processRoute();
            } else {
                // Otherwise, start fresh search
                startGeolocation();
            }
        });
    });

    function manualSearch() {
        const query = document.getElementById('mapSearchInput').value;
        if(query) {
             // Clear existing route control if any
             if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
            }
            calculateRoute(query);
        }
    }

    let clientMarker = null;

    function calculateRoute(address, directLat = null, directLng = null) {
        // Update search input so user sees what is being searched
        document.getElementById('mapSearchInput').value = address;

        // Reset Street View button to be a generic Google Maps search initially
        const btnStreetView = document.getElementById('btnStreetView');
        btnStreetView.classList.remove('d-none');
        btnStreetView.href = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
        btnStreetView.innerHTML = '<i class="bi bi-google"></i> Open in Google Maps';

        if (!beauticianLocation) return;
        
        // Remove previous client marker
        if (clientMarker) {
            map.removeLayer(clientMarker);
            clientMarker = null;
        }

        // Helper: Handle Successful Location Found
        const onLocationFound = (lat, lon, isApprox) => {
            const clientLocation = L.latLng(lat, lon);
            
            // 1. Add Client Marker
            clientMarker = L.marker(clientLocation).addTo(map)
                .bindPopup(isApprox ? "<b>Approximate Client Location</b><br>Exact address not found." : "<b>Client Location</b>")
                .openPopup();

            // 2. Force Fit Bounds
            const bounds = L.latLngBounds([beauticianLocation, clientLocation]);
            map.fitBounds(bounds, { padding: [100, 100] });

            // 3. Update Google Maps Link
            btnStreetView.href = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lon}`;
            btnStreetView.innerHTML = '<i class="bi bi-google"></i> Open in Google Maps';

            // 4. Add Route
            if (routeControl) map.removeControl(routeControl);
            try {
                routeControl = L.Routing.control({
                    waypoints: [beauticianLocation, clientLocation],
                    router: L.Routing.osrmv1({
                        serviceUrl: 'https://router.project-osrm.org/route/v1',
                        profile: 'driving'
                    }),
                    routeWhileDragging: false,
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: false, 
                    showAlternatives: false,
                    lineOptions: { styles: [{color: '#0d6efd', opacity: 0.8, weight: 6}] },
                    createMarker: function() { return null; }
                }).addTo(map);
            } catch (e) { console.error('Routing control error:', e); }
        };

        if (directLat && directLng) {
            onLocationFound(directLat, directLng, false);
            return;
        }

        // Check for Coordinates (Lat, Lon)
        const coordRegex = /^(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)$/;
        const match = address.match(coordRegex);
        if (match) {
            onLocationFound(parseFloat(match[1]), parseFloat(match[2]), false);
            return;
        }

        // Recursive search function
        const performSearch = (query, attempt = 1) => {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        onLocationFound(data[0].lat, data[0].lon, attempt > 1);
                    } else {
                        // Fallback Strategies
                        let nextQuery = null;
                        
                        if (attempt === 1) {
                            // Attempt 2: Remove detailed location info (Block/Lot/Phase) but keep structure
                            let clean = query.replace(/(?:Block|Blk|Lot|Unit|Bldg|Phase|Ph)\.?\s*[\w-]+\s*,?/gi, '')
                                             .replace(/#\d+\s*,?/g, '')
                                             .replace(/,+,/g, ',') 
                                             .trim();
                            // If cleanup resulted in a different string, try it
                            if (clean !== query && clean.length > 10) nextQuery = clean;
                            else attempt++; // Skip to next strategy immediately
                        }
                        
                        if (attempt === 2 || (!nextQuery && attempt === 2)) {
                            // Attempt 3: Search using only the last 2 parts (City, Province)
                            const parts = address.split(',');
                            if (parts.length >= 2) {
                                const lastTwo = parts.slice(-2).join(',').trim();
                                if (lastTwo !== query && lastTwo.length > 3) {
                                    nextQuery = lastTwo;
                                    if (!nextQuery.toLowerCase().includes('philippines')) nextQuery += ', Philippines';
                                }
                            }
                        }

                        if (nextQuery) {
                            console.log(`Fallback Search (${attempt + 1}):`, nextQuery);
                            performSearch(nextQuery, attempt + 1);
                        } else {
                            // Show Location Not Found Modal
                            const notFoundModal = new bootstrap.Modal(document.getElementById('locationNotFoundModal'));
                            const btnGoogleMaps = document.getElementById('btnGoogleMapsFallback');
                            if(btnGoogleMaps) {
                                btnGoogleMaps.href = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
                            }
                            notFoundModal.show();
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Error searching for location.");
                });
        };

        // Initial Search
        let initialQuery = address;
        if (!initialQuery.toLowerCase().includes('philippines')) {
            initialQuery += ', Philippines';
        }
        performSearch(initialQuery, 1);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const manualLocInput = document.getElementById('manualLocationInput');
        const suggestionsBox = document.getElementById('manualLocationSuggestions');
        const saveBtn = document.getElementById('saveManualLocationBtn');
        let manualLat = null;
        let manualLng = null;
        let debounceTimer;

        // Helper: Debounce
        const debounce = (func, delay) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(func, delay);
        };

        // 3. Initialize Sidebar Location
        const geoDataSidebar = document.getElementById('beautician-geo-data').dataset;
        const storedLatSidebar = parseFloat(geoDataSidebar.lat) || null;
        const storedLngSidebar = parseFloat(geoDataSidebar.lng) || null;
        const sidebarLocText = document.getElementById('sidebarLocationText');
        const sidebarLocCoords = document.getElementById('sidebarLocationCoords');

        if (storedLatSidebar && storedLngSidebar && sidebarLocText) {
             fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${storedLatSidebar}&lon=${storedLngSidebar}`)
                .then(res => res.json())
                .then(data => {
                    if(data && data.display_name) {
                         sidebarLocText.innerText = data.display_name;
                    } else {
                         sidebarLocText.innerText = "Address not found";
                    }
                    if(sidebarLocCoords) sidebarLocCoords.innerText = `(${storedLatSidebar}, ${storedLngSidebar})`;
                })
                .catch(err => {
                    console.error('Reverse geocoding error:', err);
                    sidebarLocText.innerText = "Location set";
                     if(sidebarLocCoords) sidebarLocCoords.innerText = `(${storedLatSidebar}, ${storedLngSidebar})`;
                });
        } else if (sidebarLocText) {
             sidebarLocText.innerText = "Location not set";
             if(sidebarLocCoords) sidebarLocCoords.innerText = "";
        }

        // 1. Input Listener
        if (manualLocInput) {
            manualLocInput.addEventListener('input', function() {
                const query = this.value.trim();
                saveBtn.disabled = true; // Disable until valid selection
                manualLat = null;
                manualLng = null;

                if (query.length < 3) {
                    suggestionsBox.style.display = 'none';
                    return;
                }

                debounce(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ph&limit=5`)
                        .then(res => res.json())
                        .then(data => {
                            suggestionsBox.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(place => {
                                    const item = document.createElement('a');
                                    item.className = 'list-group-item list-group-item-action border-0 border-bottom';
                                    item.style.cursor = 'pointer';
                                    item.innerHTML = `<i class="bi bi-geo-alt text-muted me-2"></i><small>${place.display_name}</small>`;
                                    item.onclick = () => {
                                        manualLocInput.value = place.display_name;
                                        manualLat = place.lat;
                                        manualLng = place.lon;
                                        saveBtn.disabled = false;
                                        suggestionsBox.style.display = 'none';
                                    };
                                    suggestionsBox.appendChild(item);
                                });
                                suggestionsBox.style.display = 'block';
                            } else {
                                suggestionsBox.style.display = 'none';
                            }
                        })
                        .catch(err => console.error('Nominatim error:', err));
                }, 300);
            });

            // Hide suggestions on click outside
            document.addEventListener('click', function(e) {
                if (!manualLocInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.style.display = 'none';
                }
            });
        }

        // 2. Save Button Listener
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                if (!manualLat || !manualLng) return;

                // Visual Feedback
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
                this.disabled = true;

                fetch('{{ route("beautician.location.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        latitude: manualLat,
                        longitude: manualLng
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Update Global Variable for Map if needed
                        if (typeof beauticianLocation !== 'undefined') {
                            beauticianLocation = L.latLng(manualLat, manualLng);
                            if (typeof map !== 'undefined' && map) {
                                map.setView(beauticianLocation, 16);
                                // Update marker
                                map.eachLayer((layer) => {
                                    if (layer instanceof L.Marker && layer.getPopup() && layer.getPopup().getContent().includes("You are here")) {
                                        map.removeLayer(layer);
                                    }
                                });
                                L.marker(beauticianLocation).addTo(map).bindPopup("You are here").openPopup();
                            }
                        }
                        
                        // Update Sidebar Display
                        const sidebarLocText = document.getElementById('sidebarLocationText');
                        const sidebarLocCoords = document.getElementById('sidebarLocationCoords');
                        if (sidebarLocText) sidebarLocText.innerText = manualLocInput.value;
                        if (sidebarLocCoords) sidebarLocCoords.innerText = `(${manualLat}, ${manualLng})`;

                        alert('Location updated successfully! Clients will now see this as your current location.');
                        const modal = bootstrap.Modal.getInstance(document.getElementById('setLocationModal'));
                        modal.hide();
                        manualLocInput.value = ''; // Clear input
                    } else {
                        alert('Failed to update location.');
                    }
                })
                .catch(err => {
                    console.error('Update error:', err);
                    alert('An error occurred while updating location.');
                })
                .finally(() => {
                    this.innerHTML = originalText;
                    this.disabled = false; 
                });
            });
        }
    });

    function openEditServiceModal(service) {
        document.getElementById('editServiceForm').action = `/beautician/services/${service.id}`;
        document.getElementById('editServiceId').value = service.id;
        document.getElementById('editServiceName').value = service.service_name;
        document.getElementById('editServiceDescription').value = service.description;
        document.getElementById('editServicePrice').value = service.base_price;
        document.getElementById('editServiceCategory').value = service.category;
        document.getElementById('editServiceDiscount').value = service.discount_percentage || 0;
        
        var editModal = new bootstrap.Modal(document.getElementById('editServiceModal'));
        editModal.show();
    }
</script>

<script>
    // Notification System
    let displayedNotificationIds = new Set();

    setInterval(function() {
        fetch('{{ route("beautician.notifications") }}')
            .then(response => response.json())
            .then(data => {
                // Data is an array of notifications
                if (Array.isArray(data) && data.length > 0) {
                    let newNotificationsIds = [];

                    data.forEach(notification => {
                        if (!displayedNotificationIds.has(notification.id)) {
                            showNotification(notification);
                            displayedNotificationIds.add(notification.id);
                            newNotificationsIds.push(notification.id);
                        }
                    });
                    
                    // Mark as read only if we processed new ones
                    if (newNotificationsIds.length > 0) {
                        fetch('{{ route("beautician.notifications.read") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ ids: newNotificationsIds })
                        });
                    }
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }, 5000);

    function showNotification(notification) {
        const container = document.getElementById('notification-container');
        if (!container) return;

        const alertDiv = document.createElement('div');
        let alertClass = 'alert-info';
        let icon = 'bi-info-circle-fill';
        
        // Check notification type from data
        const type = notification.data ? notification.data.type : 'info';
        
        if (type === 'new_booking') {
            alertClass = 'alert-success';
            icon = 'bi-calendar-check-fill';
        } else if (type === 'booking_cancelled' || type === 'booking_status_updated') {
             // Status updates can be acceptance or cancellation
             if (notification.data.status === 'canceled') {
                 alertClass = 'alert-danger';
                 icon = 'bi-x-circle-fill';
             } else if (notification.data.status === 'accepted') {
                 alertClass = 'alert-success';
                 icon = 'bi-check-circle-fill';
             }
        }

        alertDiv.className = `alert ${alertClass} alert-dismissible fade show shadow-lg border-0`;
        alertDiv.role = 'alert';
        
        const message = notification.data ? notification.data.message : 'You have a new notification';
        const title = notification.data && notification.data.title ? notification.data.title : 'Notification';

        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi ${icon} fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">${title}</h6>
                    <div class="small">${message}</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        container.appendChild(alertDiv);
        
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
            alert.close();
        }, 10000);

        if (type === 'new_booking') {
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    }
</script>
</body>
</html>
