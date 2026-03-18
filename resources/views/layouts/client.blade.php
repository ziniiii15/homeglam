<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Client Dashboard') - HomeGlam</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #e0e7ff;
            --secondary: #14b8a6;
            --secondary-light: #ccfbf1;
            --accent: #f43f5e;
            --accent-light: #ffe4e6;
            --bg: #f8fafc;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --white: #ffffff;
            --sidebar-width: 260px;
            --radius-lg: 24px;
            --radius-md: 16px;
            --radius-sm: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        body {
            background: var(--bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Layout */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Left */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--white);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            border-right: 1px solid #f1f5f9;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .user-profile {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--white);
            box-shadow: var(--shadow-md);
            margin-bottom: 1rem;
        }

        .user-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-light);
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-item:hover, .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-item i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .sidebar-footer {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-right: 300px; /* Width of right sidebar */
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
        }

        /* Right Sidebar */
        .right-sidebar {
            width: 300px;
            background: var(--white);
            padding: 2rem 1.5rem;
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            border-left: 1px solid #f1f5f9;
            z-index: 900;
            overflow-y: auto;
        }

        /* Mobile Adjustments */
        @media (max-width: 1200px) {
            .main-content {
                margin-right: 0;
            }
            .right-sidebar {
                transform: translateX(100%);
                transition: transform 0.3s ease;
            }
            .right-sidebar.show {
                transform: translateX(0);
                box-shadow: -5px 0 15px rgba(0,0,0,0.1);
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
                box-shadow: 5px 0 15px rgba(0,0,0,0.1);
            }
            .main-content {
                margin-left: 0;
                padding-bottom: 80px; /* Space for bottom nav */
            }
        }

        /* Components */
        .btn-custom {
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            transition: all 0.2s;
        }

        .card-custom {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: none;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card-custom:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Mobile Bottom Nav */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--white);
            display: none;
            justify-content: space-around;
            padding: 1rem;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
            z-index: 2000;
            border-radius: 20px 20px 0 0;
        }

        @media (max-width: 991px) {
            .mobile-nav {
                display: flex;
            }
        }

        .mobile-nav-btn {
            background: none;
            border: none;
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .mobile-nav-btn.active {
            color: var(--primary);
        }

        .mobile-nav-btn i {
            font-size: 1.5rem;
        }

        /* Animations */
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }
        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid #f1f5f9;
            padding: 1.5rem;
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="dashboard-container">
        <!-- Left Sidebar -->
        <aside class="sidebar" id="leftSidebar">
            <div class="user-profile">
                @php
                    $client = auth()->guard('client')->user();
                    $photo = $client && $client->photo_url ? asset($client->photo_url) . '?v=' . time() : 'https://ui-avatars.com/api/?name=' . ($client->name ?? 'User') . '&background=6366f1&color=fff';
                @endphp
                <img src="{{ $photo }}" alt="Profile" class="user-avatar">
                <h6 class="user-name">{{ $client->name ?? 'Guest' }}</h6>
                <p class="text-muted small mb-0">{{ $client->email ?? '' }}</p>
            </div>

            <nav class="nav-menu">
                <a href="#" class="nav-item active">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
                <a href="#" class="nav-item" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-person-gear"></i> Settings
                </a>
            </nav>

            <div class="sidebar-footer">
                <button class="btn btn-light w-100 text-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Mobile Header -->
            <div class="d-lg-none d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $photo }}" alt="Profile" class="rounded-circle" width="40" height="40">
                    <div>
                        <h6 class="mb-0 fw-bold">Hi, {{ explode(' ', $client->name ?? 'Guest')[0] }}</h6>
                        <small class="text-muted">Welcome back</small>
                    </div>
                </div>
                <button class="btn btn-light rounded-circle p-2" onclick="toggleRightSidebar()">
                    <i class="bi bi-bell-fill"></i>
                </button>
            </div>

            @yield('content')
        </main>

        <!-- Right Sidebar (Appointments) -->
        <aside class="right-sidebar" id="rightSidebar">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Schedule</h5>
                <button class="d-xl-none btn btn-sm btn-light rounded-circle" onclick="toggleRightSidebar()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            @yield('right-sidebar')
        </aside>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="mobile-nav">
        <button class="mobile-nav-btn active" onclick="window.scrollTo(0,0)">
            <i class="bi bi-house-door-fill"></i>
            <span>Home</span>
        </button>
        <button class="mobile-nav-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="bi bi-person-fill"></i>
            <span>Profile</span>
        </button>
        <button class="mobile-nav-btn" onclick="toggleRightSidebar()">
            <i class="bi bi-calendar-event-fill"></i>
            <span>Bookings</span>
        </button>
    </div>

    <!-- Common Modals -->
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="mb-3 text-danger">
                        <i class="bi bi-box-arrow-right display-4"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Log Out?</h5>
                    <p class="text-muted mb-4">Are you sure you want to sign out of your account?</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger flex-grow-1" onclick="document.getElementById('logout-form').submit()">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display:none;">@csrf</form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleRightSidebar() {
            document.getElementById('rightSidebar').classList.toggle('show');
        }
        
        // Close sidebars when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const rightSidebar = document.getElementById('rightSidebar');
            const toggleBtn = event.target.closest('button[onclick="toggleRightSidebar()"]');
            
            if (window.innerWidth < 1200 && 
                rightSidebar.classList.contains('show') && 
                !rightSidebar.contains(event.target) && 
                !toggleBtn) {
                rightSidebar.classList.remove('show');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
