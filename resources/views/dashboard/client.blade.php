<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Client Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

/* Global Pink Balance (Headings & Accents) */
h1, h2, h3, h4, h5, h6 { color: var(--primary); }
.appointment-item strong { color: var(--primary); }
.sidebar h6 { color: var(--primary); }
.mobile-header h4 { color: var(--primary) !important; }
.mobile-bottom-bar { border-top: 1px solid var(--border-color); }

:root.dark {
    --bg: #050505;
    --card-bg: #141414;
    --text-dark: #fff0f5; /* Lavender Blush (very light pinkish white) */
    --text-muted: #d8b4c0;
    --primary: #FFA6C9; /* Carnation Pink */
    --primary-light: rgba(255, 166, 201, 0.2);
    --secondary-light: #0f4d3a;
    --accent-light: #522905;
    --border-color: rgba(255, 166, 201, 0.3); /* Pink borders */
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
.dark .service-card:hover { border-color: var(--primary); box-shadow: var(--shadow-hover); }
.dark .service-card .service-icon img { filter: invert(1) hue-rotate(180deg); transform: translateZ(0); }
.dark .service-card.purple .service-icon { background: var(--primary-light); border: 1px solid var(--border-color); }
.dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6 { color: var(--primary); }
.dark strong { color: #fff; }

/* Mobile Bottom Bar Icons Dark Mode */
.dark .mobile-bottom-bar img {
    filter: invert(1) sepia(1) saturate(500%) hue-rotate(290deg) brightness(100%);
}
.dark .mobile-bottom-bar button.active img {
    filter: invert(1) sepia(1) saturate(500%) hue-rotate(290deg) brightness(120%) drop-shadow(0 0 2px var(--primary));
}

.distance-tooltip {
    background-color: var(--card-bg) !important;
    border: 1px solid var(--primary) !important;
    color: var(--primary) !important;
    font-weight: bold;
    font-size: 0.9rem;
    padding: 4px 8px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Modal Text Visibility in Dark Mode */
.dark .modal-title { color: var(--primary); }
.dark .modal-body { color: var(--text-dark); }
.dark .modal-header { border-bottom-color: var(--border-color); }
.dark .modal-footer { border-top-color: var(--border-color); }
.dark .text-muted { color: var(--text-muted) !important; }
.dark .form-label { color: var(--text-dark); }

/* Dark Mode Pink Balance (Mobile & Cards) */
.dark .welcome-card h5 { color: var(--primary); }
.dark .service-card h6 { color: var(--primary); }
.dark .mobile-header h4 { color: var(--primary) !important; }
.dark .appointment-item strong { color: var(--primary); }
.dark .sidebar h6 { color: var(--primary); }
.dark .mobile-bottom-bar { border-top: 1px solid var(--border-color); }





/* Modal Z-Index Fix */
.modal { z-index: 4000 !important; }
.modal-backdrop { z-index: 3900 !important; }

body {
    background: var(--bg);
    font-family: 'Plus Jakarta Sans', sans-serif;
    min-height: 100vh;
    color: var(--text-dark);
    overflow-x: hidden;
    font-size: 0.9rem;
}

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

.service-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1.2rem;
}

.service-card { 
    background: var(--card-bg);
    padding: 1.5rem 1rem; 
    border-radius: var(--card-radius); 
    color: var(--text-dark); 
    display: flex; 
    flex-direction: column; 
    align-items: center;
    justify-content: center;
    text-align: center;
    box-shadow: var(--shadow-sm); 
    cursor: pointer; 
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    border: 1px solid var(--border-color);
    height: 100%;
}
.service-card:hover { 
    transform: translateY(-5px); 
    box-shadow: var(--shadow-hover); 
    border-color: rgba(111, 66, 193, 0.1);
}

.service-card .service-icon {
    width: 75px;
    height: 75px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.8rem;
    transition: transform 0.3s ease;
}
.service-card:hover .service-icon { transform: scale(1.1); }
.service-card .service-icon img { width: 50px; height: 50px; object-fit: contain; }

/* Color Themes for Services */
.service-card.purple .service-icon { background: var(--primary-light); }

.service-card.teal .service-icon { background: var(--secondary-light); }

.service-card.orange .service-icon { background: var(--accent-light); }

.service-card h6 { font-weight: 700; font-size: 1rem; margin-bottom: 0.2rem; }
.service-card small { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; }

/* ====== RIGHT SIDEBAR ====== */
.right-sidebar { 
    width: 260px; 
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

.appointment-item { 
    background: var(--card-bg); 
    border: 1px solid var(--border-color);
    border-radius: 14px; 
    padding: 0.8rem; 
    margin-bottom: 0.8rem; 
    transition: 0.2s;
    position: relative;
    padding-left: 1rem;
}
.appointment-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 15%;
    bottom: 15%;
    width: 3px;
    background: var(--primary);
    border-radius: 0 4px 4px 0;
}
.appointment-item:hover { transform: translateX(3px); border-color: var(--primary-light); }
.appointment-item strong { display: block; color: var(--text-dark); font-size: 0.85rem; margin-bottom: 0.1rem; }
.appointment-item small { color: var(--text-muted); font-size: 0.75rem; display: flex; align-items: center; gap: 4px; }

/* Beautician Cards */
.beautician-card {
    width: 100%;
    margin: 0 auto;
    background: var(--card-bg);
    border-radius: 16px;
    padding: 0; /* Removed padding to allow full-width internal sections */
    display: flex;
    flex-direction: column; /* Changed to column for expanding sections */
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    transition: 0.2s;
    overflow: hidden; /* Ensure rounded corners clip content */
}
.beautician-card:hover { box-shadow: var(--shadow-md); border-color: var(--primary-light); }

.beautician-trigger:hover { background-color: rgba(0,0,0,0.02); }
.dark .beautician-trigger:hover { background-color: rgba(255,255,255,0.02); }

.portfolio-preview-btn {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.portfolio-preview-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

/* Original styles removed/updated */
.beautician-info { flex-grow: 1; display: flex; flex-direction: column; }
.service-list { max-height: 120px; overflow-y: auto; padding-right: 4px; scrollbar-width: none; }
.service-list::-webkit-scrollbar { display: none; }
.service-item { padding: 6px 0; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between; font-size: 0.85rem; }
.service-item:last-child { border-bottom: none; }

/* Edit Profile Modal Fixes */
#editProfileModal { pointer-events: auto !important; }
#editProfileModal .modal-dialog { pointer-events: auto; }

/* Mobile Sidebar & Bottom Bar */
@media (max-width: 768px) {
    /* Background Image Layer */
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('{{ asset("view-asset/bg1.png") }}') no-repeat center center fixed;
        background-size: cover;
        z-index: -2;
        transition: filter 0.3s ease;
    }

    /* Light Mode Overlay */
    body::after {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.85);
        z-index: -1;
        transition: background-color 0.3s ease;
    }

    /* Dark Mode Adjustments */
    .dark body::before {
        filter: brightness(0.2) grayscale(0.4); /* Make image dark */
    }
    .dark body::after {
        background-color: transparent; /* Remove overlay in dark mode */
        backdrop-filter: none;
    }

    .sidebar {
        position: fixed;
        bottom: 0;
        top: auto;
        width: 100%;
        height: auto;
        max-height: 85vh; /* Increased from 60vh to 85vh */
        overflow-y: auto; /* Allow scrolling if content is still too long */
        border-radius: 25px 25px 0 0;
        box-shadow: 0 -10px 40px rgba(0,0,0,0.1);
        transform: translateY(100%);
        opacity: 0;
        transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.3s ease;
        padding: 1.5rem;
        z-index: 3100; /* Ensure it is above the bottom bar */
        padding-bottom: 2rem; /* Add padding to prevent content from being too close to edge */
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

    .service-cards {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 0;
    }

    .service-card {
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
        padding: 0.8rem 1rem;
        min-height: auto;
        text-align: left;
        gap: 0.8rem;
        border-radius: 14px;
    }
    .service-card:hover { transform: none; }
    
    .service-card .service-icon {
        width: 80px;
        height: 80px;
        margin-bottom: 0;
        flex-shrink: 0;
    }
    .service-card .service-icon img { width: 60px; height: 60px; object-fit: contain; }
    
    .service-text { text-align: left; }
    .service-card h6 { margin: 0; font-size: 0.95rem; }
    .service-card small { display: block; font-size: 0.75rem; margin-top: 2px; }
}

/* Time Slot Grid */
.time-slot-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 10px;
    max-height: 250px;
    overflow-y: auto;
    padding: 10px;
}
.time-slot-btn {
    padding: 10px 5px;
    border: 1px solid var(--border-color);
    background: var(--card-bg);
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-dark);
    transition: all 0.2s;
    width: 100%;
}
.time-slot-btn:hover:not(:disabled) {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
}
.time-slot-btn.selected {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}
.time-slot-btn:disabled {
    background: #e9ecef;
    color: #adb5bd;
    cursor: not-allowed;
    border-color: #dee2e6;
}

/* Beautician Name in Dark Mode - Fix for Modal */
.dark .beautician-card .beautician-trigger h6 {
    color: #ffffff !important;
}

/* Dark Mode fixes for Beautician Profile Modal */
.dark #beauticianProfileModal .modal-content {
    background-color: #212529;
    color: #f8f9fa;
}
.dark #beauticianProfileModal .bg-light {
    background-color: #343a40 !important;
    color: #f8f9fa !important;
}
.dark #beauticianProfileModal .text-dark {
    color: #f8f9fa !important;
}
.dark #beauticianProfileModal .text-muted {
    color: #adb5bd !important;
}

/* Autocomplete Suggestions */
#locationSuggestions {
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    border-top: none;
    border-radius: 0 0 10px 10px;
    max-height: 200px;
    overflow-y: auto;
    box-shadow: var(--shadow-md);
}
.suggestion-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: background-color 0.2s;
    color: var(--text-dark);
    border-bottom: 1px solid var(--border-color);
    font-size: 0.9rem;
}
.suggestion-item:last-child {
    border-bottom: none;
}
.suggestion-item:hover {
    background-color: var(--primary-light);
    color: var(--text-dark); /* Keep text readable on light bg */
}
.dark .suggestion-item:hover {
    background-color: rgba(255, 166, 201, 0.15);
    color: var(--primary);
}

/* Payment Option Cards Design */
.payment-option-card {
    transition: all 0.2s ease-in-out;
    background-color: var(--card-bg);
    border: 2px solid var(--border-color);
    cursor: pointer;
    position: relative;
}
.payment-option-card:hover {
    border-color: var(--primary);
    background-color: var(--bg);
}
.payment-option-card.selected {
    border-color: var(--primary);
    background-color: var(--primary-light);
    box-shadow: var(--shadow-sm);
}
.payment-option-card .form-check-input {
    cursor: pointer;
    transform: scale(1.1);
}
.dark .payment-option-card:hover {
    background-color: rgba(255, 166, 201, 0.1);
}
.dark .payment-option-card.selected {
    background-color: rgba(255, 166, 201, 0.15);
    border-color: var(--primary);
}
.dark .payment-option-card.selected strong {
    color: var(--primary);
}

.letter-spacing-1 {
    letter-spacing: 1px;
}

/* Manual QR Section Dark Mode */
.dark #manualQrSection .card {
    background-color: var(--card-bg) !important;
}
.dark #manualQrSection .bg-white {
    background-color: var(--bg) !important;
    border-color: var(--border-color) !important;
}
.dark #manualQrSection .input-group-text {
    background-color: var(--bg) !important;
    color: var(--text-muted) !important;
    border-color: var(--border-color) !important;
}
</style>
</head>
<body>
    <!-- Notification Container -->
    <div id="notification-container" class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999; width: 90%; max-width: 400px;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationUrl = "{{ route('client.notifications') }}";
            const markReadUrl = "{{ route('client.notifications.read') }}";
            
            // Load seen IDs from localStorage to persist across reloads
            let storedIds = [];
            try {
                storedIds = JSON.parse(localStorage.getItem('homeglam_seen_notifications') || '[]');
            } catch (e) {
                console.warn('LocalStorage error:', e);
                storedIds = [];
            }
            let displayedNotificationIds = new Set(storedIds);

            function fetchNotifications() {
                fetch(notificationUrl)
                    .then(response => response.json())
                    .then(data => {
                        // Data is an array of notifications
                        if (Array.isArray(data) && data.length > 0) {
                            let unreadIdsOnServer = [];
                            let hasNewDisplays = false;

                            data.forEach(notification => {
                                // Collect all unread IDs to mark them as read on server
                                // This prevents them from reappearing if local storage is cleared or desynced
                                if (!notification.read_at) {
                                    unreadIdsOnServer.push(notification.id);
                                }

                                // Only SHOW if not previously displayed
                                if (!displayedNotificationIds.has(notification.id) && !notification.read_at) {
                                    showNotification(notification);
                                    displayedNotificationIds.add(notification.id);
                                    hasNewDisplays = true;
                                }
                            });
                            
                            // Save updated set to localStorage if we showed anything new
                            if (hasNewDisplays) {
                                try {
                                    // Increased limit to 100 to reduce risk of cycling
                                    const idsToStore = [...displayedNotificationIds].slice(-100);
                                    localStorage.setItem('homeglam_seen_notifications', JSON.stringify(idsToStore));
                                } catch (e) {
                                    console.warn('LocalStorage write error:', e);
                                }
                            }

                            // Mark ALL fetched unread IDs as read on server
                            if (unreadIdsOnServer.length > 0) {
                                fetch(markReadUrl, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({ ids: unreadIdsOnServer })
                                }).catch(err => console.error('Mark read failed:', err));
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            }

            function showNotification(notification) {
                const container = document.getElementById('notification-container');
                if (!container) return;

                const alertDiv = document.createElement('div');
                let alertClass = 'alert-info';
                let icon = 'bi-info-circle-fill';
                
                // Check notification type/status
                const type = notification.data ? notification.data.type : 'info';
                const status = notification.data ? notification.data.status : '';
                
                if (type === 'status_update') {
                     if (status === 'accepted' || status === 'completed') {
                         alertClass = 'alert-success';
                         icon = 'bi-check-circle-fill';
                     } else if (status === 'canceled' || status === 'declined') {
                         alertClass = 'alert-danger';
                         icon = 'bi-x-circle-fill';
                     }
                }

                alertDiv.className = `alert ${alertClass} alert-dismissible fade show shadow-lg border-0`;
                alertDiv.role = 'alert';
                
                const message = notification.data ? notification.data.message : 'You have a new notification';
                const title = notification.data && notification.data.title ? notification.data.title : 'Booking Update';

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
                
                // Auto dismiss after 10 seconds
                setTimeout(() => {
                    const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
                    alert.close();
                }, 10000);

                if (type === 'status_update') {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            }

            // Poll every 5 seconds
            setInterval(fetchNotifications, 5000);
            // Initial fetch
            fetchNotifications();
        });
    </script>


<div class="d-flex">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="d-flex align-items-center mb-3">
            <img src="{{ asset('view-asset/photo_2026-02-04_22-17-58.png') }}" alt="HomeGlam Logo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 0.75rem;">
            <span class="fw-bold" style="letter-spacing: -0.3px; font-size: 1.1rem;">HomeGlam</span>
        </div>
        <img src="{{ $client->photo_url ? asset($client->photo_url) : 'https://via.placeholder.com/150' }}" alt="Profile">
        <h6>{{ $client->name }}</h6>
        <p>Client Account</p>
        
        <nav class="nav flex-column">
            <a class="nav-link active" href="#"><i class="bi bi-grid-fill"></i> Dashboard</a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#pendingBookingsModal">
                <i class="bi bi-hourglass-split"></i> Pending
            </a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#completedBookingsModal">
                <i class="bi bi-check-circle-fill"></i> Completed
            </a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#cancelledBookingsModal">
                <i class="bi bi-x-circle-fill"></i> Cancelled
            </a>
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#transactionsModal">
                <i class="bi bi-receipt-cutoff"></i> Transactions
            </a>
            <a class="nav-link" href="#" id="editProfileBtn"><i class="bi bi-person-circle"></i> Edit Profile</a>
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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Mobile Header -->
        <div class="d-flex align-items-center d-md-none mb-3 mobile-header">
            <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle me-2" style="width: 35px; height: 35px;">
                 <i class="bi bi-stars"></i>
            </div>
            <h4 class="fw-bold mb-0" style="letter-spacing: -0.5px;">HomeGlam</h4>
        </div>

        <div class="welcome-card">
            <div>
                <h5>Hello, {{ explode(' ', $client->name)[0] }}! <span style="font-size:1.5rem;">✨</span></h5>
                <p class="rotating-text mb-0">Ready to book your next service?</p>
            </div>
            <!-- Removed Add New Booking button -->
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-primary text-white border-0" style="border-radius: 15px 0 0 15px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="serviceSearch" class="form-control form-control-lg bg-light border-0" 
                       style="border-radius: 0 15px 15px 0; box-shadow: none;" 
                       placeholder="Search services (e.g. Hair, Nails, Makeup)...">
            </div>
        </div>

        <!-- Search Results Container -->
        <div id="searchResults" class="d-none flex-column gap-3 mb-4"></div>

        <div class="service-cards">
            @php
                $colors = ['purple', 'teal', 'orange', 'blue', 'pink'];
                // Map categories to their GIF icons
                $categoryGifs = [
                    'Hair' => 'view-asset/hair.gif',
                    'Hair Services' => 'view-asset/hair.gif',
                    'Nails' => 'view-asset/nails (2).gif',
                    'Nail Services' => 'view-asset/nails (2).gif',
                    'Makeup' => 'view-asset/makeup.gif',
                    'Makeup Services' => 'view-asset/makeup.gif',
                    'Massage' => 'view-asset/massage.gif',
                    'Skincare' => 'view-asset/skincare.gif',
                    'Skin Care' => 'view-asset/skincare.gif',
                ];
            @endphp
            @foreach($categories as $index => $category)
                @php
                    $colorClass = $colors[$index % count($colors)];
                    
                    // Determine GIF
                    $gifName = null;
                    // Direct match
                    if (isset($categoryGifs[$category->name])) {
                        $gifName = $categoryGifs[$category->name];
                    } else {
                        // Case-insensitive match
                        foreach ($categoryGifs as $key => $val) {
                            if (strcasecmp($key, $category->name) === 0) {
                                $gifName = $val;
                                break;
                            }
                        }
                    }
                @endphp
                <div class="service-card {{ $colorClass }}" data-category="{{ $category->name }}">

                    <!-- LEFT ICON -->
                    <div class="service-icon d-flex align-items-center justify-content-center">
                        @if($gifName)
                            <img src="{{ asset($gifName) }}" alt="{{ $category->name }}">
                        @else
                            <i class="bi bi-{{ $category->icon ?? 'circle' }}" style="font-size: 2rem; color: white;"></i>
                        @endif
                    </div>

                    <!-- RIGHT TEXT -->
                    <div class="service-text">
                        <h6>{{ $category->name }}</h6>
                        <small>Click to see beauticians</small>
                    </div>

                </div>

            @endforeach
        </div>

        <!-- Your Bookings section removed -->
    </div>

    <!-- ===== RIGHT SIDEBAR ===== -->
     

    <div class="right-sidebar">
<div class="d-md-none d-flex align-items-center mb-3">
    <button id="closeRight" class="btn btn-light btn-sm me-2">
    
</button>
    <strong>Appointments</strong>
</div>
        <h6>Upcoming Appointments</h6>
        @forelse($bookings->where('status','pending')->sortBy('booking_date')->take(3) as $upcoming)
            <div class="appointment-item position-relative" style="cursor: pointer; transition: transform 0.2s;" 
                 onmouseover="this.style.transform='scale(1.02)'" 
                 onmouseout="this.style.transform='scale(1)'"
                 data-details="{{ json_encode([
                    'id' => $upcoming->id,
                    'serviceName' => $upcoming->service->service_name,
                    'beauticianName' => $upcoming->beautician->name,
                    'date' => \Carbon\Carbon::parse($upcoming->booking_date)->format('M d, Y'),
                    'time' => \Carbon\Carbon::parse($upcoming->booking_time)->format('h:i A'),
                    'location' => $upcoming->location,
                    'status' => ucfirst($upcoming->status),
                    'totalCost' => $upcoming->total_cost,
                    'discountAmount' => $upcoming->discount_amount ?? 0,
                    'allergyInfo' => $upcoming->allergy_info,
                    'paymentStatus' => ucfirst($upcoming->payment_status ?? 'unpaid'),
                    'downPayment' => $upcoming->down_payment_amount ?? 0
                 ]) }}"
                 onclick="openBookingDetailsModal(JSON.parse(this.getAttribute('data-details')))">
                <strong>{{ $upcoming->service->service_name }}</strong>
                <small class="d-block">{{ \Carbon\Carbon::parse($upcoming->booking_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($upcoming->booking_time)->format('h:i A') }}</small>
                <span class="badge bg-info-subtle text-info mt-1 rounded-pill" style="font-size: 0.7rem;">View Details</span>
            </div>
        @empty
            <p class="text-muted">No upcoming appointments.</p>
        @endforelse

    </div>
</div>

<!-- ===== MODALS ===== -->
<!-- Beauticians Modal -->
<div class="modal fade" id="beauticiansModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold px-2 pt-2" id="beauticiansModalTitle">Beauticians</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-4 pt-2">
        <p class="text-muted small px-2 mb-4">Select a professional for your service</p>
        <div class="d-flex flex-wrap gap-3 justify-content-center" id="beauticiansCards"></div>
      </div>
      <div class="modal-footer border-top-0">
        <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Beautician Profile Modal -->
<div class="modal fade" id="beauticianProfileModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-header border-bottom-0 pb-0 position-relative" style="height: 100px; background: linear-gradient(135deg, var(--primary-light), #fff);">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-0 pb-4 px-4">
        <div class="d-flex flex-column flex-md-row gap-4">
            <!-- Left Column: Profile Info -->
            <div class="text-center text-md-start" style="margin-top: -50px; min-width: 240px;">
                <div class="position-relative d-inline-block mb-3">
                    <img id="profileModalImage" src="" class="rounded-circle border border-4 border-white shadow-sm" style="width: 140px; height: 140px; object-fit: cover; background: #fff;">
                    <span id="profileModalStatusBadge" class="position-absolute bottom-0 end-0 badge rounded-pill border border-2 border-white px-3 py-2 shadow-sm" style="transform: translate(10%, 10%);"></span>
                </div>
                
                <h4 id="profileModalName" class="fw-bold mb-1"></h4>
                <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-3 text-muted small">
                     <i class="bi bi-geo-alt-fill text-primary"></i> <span id="profileModalLocation"></span>
                </div>

                <div class="mb-3">
                    <div class="mb-1 text-warning" id="profileModalStars" style="font-size: 1.2rem;"></div>
                    <small class="text-muted fw-bold" id="profileModalRatingText"></small>
                </div>

                <div class="card bg-light border-0 rounded-4 p-3 mb-3 text-start shadow-sm">
                    <h6 class="fw-bold small text-muted mb-2 border-bottom pb-2">CONTACT INFO</h6>
                    <p class="mb-2 small d-flex align-items-center"><i class="bi bi-envelope-fill me-2 text-primary"></i> <span id="profileModalEmail" class="text-break"></span></p>
                    <p class="mb-0 small d-flex align-items-center"><i class="bi bi-telephone-fill me-2 text-primary"></i> <span id="profileModalPhone"></span></p>
                </div>
            </div>

            <!-- Right Column: Services & Details -->
            <div class="flex-grow-1 pt-md-3">
                 <h5 class="fw-bold mb-3 pb-2 border-bottom"><i class="bi bi-stars text-primary me-2"></i>Services Offered</h5>
                 <div id="profileModalServices" class="d-flex flex-column gap-2 pe-1" style="max-height: 400px; overflow-y: auto;">
                     <!-- Services injected here -->
                 </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Gallery Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold px-2 pt-2" id="galleryModalTitle">Portfolio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-4 pt-2">
        <div id="galleryContainer" class="row g-3"></div>
      </div>
      <div class="modal-footer border-top-0">
        <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Reviews Modal -->
<div class="modal fade" id="reviewsModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold px-2 pt-2" id="reviewsModalTitle">Reviews</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-4 pt-2">
        <div id="reviewsContainer" class="d-flex flex-column gap-3" style="max-height: 60vh; overflow-y: auto;"></div>
      </div>
      <div class="modal-footer border-top-0">
        <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
        <h5 class="modal-title" id="bookingServiceName">Book Service</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('client.bookings.store') }}" class="p-3" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="service_id" id="modalServiceId">
    <input type="hidden" name="beautician_id" id="modalBeauticianId">
    <input type="hidden" name="slot_id" id="selectedSlotIdInput">
    <div class="mb-3">
    <label for="modalBookingDate" class="form-label fw-bold small text-muted">DATE</label>
    <input type="date" name="booking_date" id="modalBookingDate" class="form-control form-control-lg bg-light border-0" required>
</div>

<div class="mb-3">
    <label class="form-label fw-bold small text-muted">TIME</label>
    <div id="timeSlotsContainer" class="time-slot-grid border rounded bg-light">
        <p class="text-muted small m-2 text-center w-100">Select a date to view slots.</p>
    </div>
    <input type="hidden" name="booking_time" id="selectedTimeInput" required>
</div>


    <input type="hidden" name="service_name" id="modalServiceName">
    
    <div class="mb-3 position-relative">
        <label class="form-label fw-bold small text-muted">LOCATION</label>
        <input type="text" name="location" id="bookingLocationInput" class="form-control form-control-lg bg-light border-0" placeholder="Enter your address" required autocomplete="off">
        <div id="locationSuggestions" class="position-absolute w-100" style="z-index: 9999; display: none;"></div>
    </div>
    
    <!-- Travel Map Visualization -->
    <div class="mb-3">
        <div class="d-flex justify-content-end mb-1">
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-0" style="font-size: 0.75rem;" onclick="toggleBeauticianLocation()">
                <i class="bi bi-geo-alt-fill me-1"></i> Show Beautician Location
            </button>
        </div>
        <div id="bookingMap" class="rounded-3 border" style="height: 200px; width: 100%;"></div>
        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">*Distance calculated via road network.</small>
    </div>

    <!-- Payment Info Section -->
    <div class="mb-3 p-3 bg-light rounded-3">
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Service Price:</span>
            <span class="fw-bold" id="modalServicePrice">₱0.00</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Travel Fee (<span id="modalTravelDistance">0</span> km):</span>
            <span class="fw-bold text-warning" id="modalTravelFee">₱0.00</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-primary fw-bold">Down Payment (50%):</span>
            <span class="fw-bold text-primary fs-5" id="modalDownPayment">₱0.00</span>
        </div>

        <!-- Payment Option Selection -->
        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">PAYMENT OPTION</label>
            <div class="d-flex flex-column gap-2">
                <!-- Pay Full -->
                <label class="payment-option-card p-3 rounded d-flex align-items-center gap-3" for="opt_pay_full" id="card_pay_full">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="radio" name="payment_option" id="opt_pay_full" value="pay_full" onchange="togglePaymentOptions()">
                    </div>
                    <div>
                        <strong class="d-block text-primary">Pay Full Amount</strong>
                        <span class="small text-muted">Pay the total amount now.</span>
                    </div>
                </label>

                <!-- Pay 50% -->
                <label class="payment-option-card p-3 rounded d-flex align-items-center gap-3 selected" for="opt_pay_now" id="card_pay_now">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="radio" name="payment_option" id="opt_pay_now" value="pay_now" checked onchange="togglePaymentOptions()">
                    </div>
                    <div>
                        <strong class="d-block text-primary">Pay 50% Downpayment</strong>
                        <span class="small text-muted">Pay half now, balance later.</span>
                    </div>
                </label>

                <!-- Book Only -->
                <label class="payment-option-card p-3 rounded d-flex align-items-center gap-3" for="opt_pay_later" id="card_pay_later">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="radio" name="payment_option" id="opt_pay_later" value="pay_later" onchange="togglePaymentOptions()">
                    </div>
                    <div>
                        <strong class="d-block text-primary">Book Only</strong>
                        <span class="small text-muted">Pay later (Cash).</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Payment Method Toggle -->
        <div id="paymentMethodContainer" class="mb-3">
            <label class="form-label fw-bold small text-muted">PAYMENT METHOD</label>
            <div class="d-flex gap-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method_type" id="pm_paymongo" value="paymongo" checked onchange="togglePaymentMethod()">
                    <label class="form-check-label" for="pm_paymongo">Online Gateway (Card/GCash)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method_type" id="pm_manual" value="manual" onchange="togglePaymentMethod()">
                    <label class="form-check-label" for="pm_manual">Manual Transfer (QR)</label>
                </div>
            </div>
        </div>

        <!-- PayMongo Info -->
        <div id="paymongoSection">
            <small class="text-muted fst-italic d-block">
                <i class="bi bi-info-circle me-1"></i> You will be redirected to PayMongo to complete the payment securely.
            </small>
        </div>

        <!-- Manual QR Section -->
        <div id="manualQrSection" class="d-none">
            <div class="card border-0 shadow-sm bg-light mb-3">
                <div class="card-body text-center p-4">
                    <h6 class="fw-bold text-primary mb-3">SCAN TO PAY</h6>
                    
                    <div class="position-relative d-inline-block bg-white p-3 rounded-3 shadow-sm mb-3 border">
                        <img id="beauticianQrImage" src="" class="img-fluid" style="max-height: 250px; display: none;">
                        <div id="noQrMessage" class="d-flex flex-column align-items-center justify-content-center py-4 px-3" style="display: none; min-width: 200px; min-height: 200px;">
                            <i class="bi bi-qr-code-scan text-muted display-1 opacity-25 mb-0"></i>
                        </div>
                    </div>

                    <p class="small fw-bold text-danger mb-1 letter-spacing-1">PLEASE PAY EXACT AMOUNT</p>
                    <small class="d-block text-muted">Use GCash or Bank App</small>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">PROOF OF PAYMENT</label>
                <div class="input-group">
                    <input type="file" name="payment_receipt" class="form-control" accept="image/*" onchange="togglePaymentOptions()">
                    <label class="input-group-text bg-white text-muted"><i class="bi bi-upload"></i></label>
                </div>
                <small class="text-muted fst-italic ms-1" style="font-size: 0.75rem;">Upload screenshot of your payment receipt.</small>
            </div>
        </div>

        <input type="hidden" name="travel_fee" id="inputTravelFee" value="0">
        <input type="hidden" name="distance" id="inputDistance" value="0">
    </div>

    <!-- Allergy Section (Hidden by default, shown for Skin Care) -->
    <div class="mb-3 d-none" id="allergyContainer">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="hasAllergyCheck">
            <label class="form-check-label fw-bold small text-muted" for="hasAllergyCheck">
                DO YOU HAVE ANY ALLERGIES?
            </label>
        </div>
        <div class="d-none" id="allergyInputContainer">
             <input type="text" name="allergy_info" id="allergyInfoInput" class="form-control bg-light border-0" placeholder="Please specify your allergies...">
        </div>
    </div>

    <div class="mb-3"><label class="form-label fw-bold small text-muted">NOTES</label><textarea name="client_notes" class="form-control bg-light border-0" rows="2" placeholder="Any special instructions..."></textarea></div>

    <div class="modal-footer border-top-0 px-0 pb-0">
        <div class="d-grid gap-2 w-100">
            <button type="submit" id="confirmBookingBtn" class="btn btn-primary rounded-pill fw-bold d-none">
                Confirm Booking
            </button>
            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
        </div>
    </div>
</form>

    </div>
  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
   <form id="editProfileForm" class="modal-content border-0 shadow-lg" style="border-radius: 20px;" method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="modal-header border-bottom-0">
        <h5 class="modal-title fw-bold">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        @if($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="text-center mb-4">
             <div class="position-relative d-inline-block">
                <img src="{{ $client->photo_url ? asset($client->photo_url) : 'https://via.placeholder.com/150' }}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 4px solid var(--bg);">
                <label for="photoInput" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow-sm" style="cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 1.2rem; line-height: 0;">+</span>
                </label>
             </div>
        </div>
        <div class="mb-3"><label class="form-label fw-bold small text-muted">NAME</label><input type="text" name="name" class="form-control bg-light border-0" value="{{ $client->name }}" required></div>
        <div class="mb-3"><label class="form-label fw-bold small text-muted">EMAIL</label><input type="email" name="email" class="form-control bg-light border-0" value="{{ $client->email }}" required></div>
        <div class="mb-3"><label class="form-label fw-bold small text-muted">PHONE</label><input type="text" name="phone" class="form-control bg-light border-0" value="{{ $client->phone }}" required></div>
        <div class="mb-3"><label class="form-label fw-bold small text-muted">ADDRESS</label><input type="text" name="address" class="form-control bg-light border-0" value="{{ $client->address }}" required></div>
        <div class="d-none"><input type="file" name="photo" id="photoInput" class="form-control"></div>
        <div class="mb-3"><label class="form-label fw-bold small text-muted">NEW PASSWORD</label><input type="password" name="password" class="form-control bg-light border-0" placeholder="Leave blank to keep current"></div>
    </div>
    <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
    </div>
</form>

  </div>
</div>

<!-- Location Modal -->
<div class="modal fade" id="locationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">My Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="map" style="height: 400px; width: 100%;"></div>
                <div class="p-3">
                    <label class="form-label small text-muted fw-bold">SELECTED ADDRESS</label>
                    <input type="text" id="locationAddress" class="form-control" placeholder="Select location on map" value="{{ $client->address }}">
                    <input type="hidden" id="locationLat" value="{{ $client->latitude }}">
                    <input type="hidden" id="locationLng" value="{{ $client->longitude }}">
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="saveLocationBtn" class="btn btn-primary rounded-pill px-4">Save Location</button>
            </div>
        </div>
    </div>
</div>

<!-- Pending Bookings Modal -->
<div class="modal fade" id="pendingBookingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Pending Appointments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                @forelse($bookings->whereIn('status', ['pending', 'accepted']) as $booking)
                    <div class="card mb-3 border-0 shadow-sm appointment-item-hover" style="border-radius: 16px; cursor: pointer; transition: transform 0.2s;"
                         onmouseover="this.style.transform='scale(1.01)'" 
                         onmouseout="this.style.transform='scale(1)'"
                         data-details="{{ json_encode([
                            'id' => $booking->id,
                            'serviceName' => $booking->service->service_name,
                            'beauticianName' => $booking->beautician->name,
                            'date' => \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y'),
                            'time' => \Carbon\Carbon::parse($booking->booking_time)->format('h:i A'),
                            'location' => $booking->location,
                            'status' => ucfirst($booking->status),
                            'totalCost' => $booking->total_cost,
                            'discountAmount' => $booking->discount_amount ?? 0,
                            'allergyInfo' => $booking->allergy_info,
                            'paymentStatus' => ucfirst($booking->payment_status ?? 'unpaid'),
                            'downPayment' => $booking->down_payment_amount ?? 0
                         ]) }}"
                         onclick="openBookingDetailsModal(JSON.parse(this.getAttribute('data-details')))">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $booking->service->service_name }}</h6>
                                    <p class="text-muted small mb-0">
                                        with {{ $booking->beautician->name }} <br>
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                    </p>
                                </div>
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <span class="badge {{ $booking->status == 'pending' ? 'bg-warning text-dark' : 'bg-info text-white' }} rounded-pill">{{ ucfirst($booking->status) }}</span>
                                    <small class="text-primary fw-bold" style="font-size: 0.8rem;">View Status</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-hourglass-split text-muted display-4 mb-3"></i>
                        <p class="text-muted">No pending or accepted appointments.</p>
                    </div>
                @endforelse
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Completed Bookings Modal -->
<div class="modal fade" id="completedBookingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Completed Appointments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                @php
                    $completedBookings = $bookings->where('status', 'completed')->sortByDesc(function($booking) {
                        return $booking->booking_date . ' ' . $booking->booking_time;
                    });
                @endphp

                @forelse($completedBookings as $booking)
                    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 16px;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $booking->service->service_name }}</h6>
                                    <p class="text-muted small mb-0">
                                        with {{ $booking->beautician->name }} <br>
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                    </p>
                                </div>
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <span class="badge bg-success rounded-pill">Completed</span>
                                    @if(!$booking->report)
                                        <button class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-bold" style="font-size: 0.8rem;" data-booking-id="{{ $booking->id }}" onclick="setReportBookingId(this.dataset.bookingId)" data-bs-toggle="modal" data-bs-target="#reportModal">
                                            <i class="bi bi-flag-fill me-1"></i>Report Issue
                                        </button>
                                    @else
                                        <span class="badge bg-warning text-dark rounded-pill border border-warning" style="font-size: 0.7rem;">Report: {{ ucfirst($booking->report->status) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="review-section" data-booking-id="{{ $booking->id }}">
                            @if($booking->review)
                                <div class="bg-light p-3 rounded-3 mt-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="text-warning me-2">
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
                                <button class="btn btn-outline-primary btn-sm rounded-pill mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#reviewForm{{ $booking->id }}">
                                    <i class="bi bi-star"></i> Leave a Review
                                </button>
                                <div class="collapse mt-3" id="reviewForm{{ $booking->id }}">
                                    <form action="{{ route('reviews.store') }}" method="POST" class="bg-light p-3 rounded-3 ajax-review-form" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                        <input type="hidden" name="client_id" value="{{ Auth::guard('client')->id() }}">
                                        <input type="hidden" name="beautician_id" value="{{ $booking->beautician_id }}">
                                        <input type="hidden" name="date" value="{{ now()->toDateString() }}">
                                        
                                        <div class="mb-2">
                                            <label class="form-label small fw-bold">Rating</label>
                                            <div class="d-flex align-items-center gap-1 star-rating-group">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star text-warning fs-4 star-item" data-value="{{ $i }}" style="cursor: pointer;"></i>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" class="rating-value" value="" required>
                                            <div class="invalid-feedback d-block rating-error" style="display:none !important;">Please select a rating</div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small fw-bold">Comment</label>
                                            <textarea name="comment" class="form-control form-control-sm" rows="2" required placeholder="Share your experience..."></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Photo (Optional)</label>
                                            <input type="file" name="review_image" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100">Submit Review</button>
                                    </form>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-check text-muted display-4 mb-3"></i>
                        <p class="text-muted">No completed appointments yet.</p>
                    </div>
                @endforelse
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancelled Bookings Modal -->
<div class="modal fade" id="cancelledBookingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Cancelled Appointments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                @forelse($bookings->where('status', 'canceled') as $booking)
                    <div class="card mb-3 border-0 shadow-sm appointment-item-hover" style="border-radius: 16px; cursor: pointer; transition: transform 0.2s;"
                         onmouseover="this.style.transform='scale(1.01)'" 
                         onmouseout="this.style.transform='scale(1)'"
                         data-details="{{ json_encode([
                            'id' => $booking->id,
                            'serviceName' => $booking->service->service_name,
                            'beauticianName' => $booking->beautician->name,
                            'date' => \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y'),
                            'time' => \Carbon\Carbon::parse($booking->booking_time)->format('h:i A'),
                            'location' => $booking->location,
                            'status' => ucfirst($booking->status),
                            'totalCost' => $booking->total_cost,
                            'discountAmount' => $booking->discount_amount ?? 0,
                            'allergyInfo' => $booking->allergy_info,
                            'paymentStatus' => ucfirst($booking->payment_status ?? 'unpaid'),
                            'downPayment' => $booking->down_payment_amount ?? 0
                         ]) }}"
                         onclick="openBookingDetailsModal(JSON.parse(this.getAttribute('data-details')))">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $booking->service->service_name }}</h6>
                                    <p class="text-muted small mb-0">
                                        with {{ $booking->beautician->name }} <br>
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                    </p>
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
                    $transactionBookings = $bookings->filter(function($booking) {
                        return !is_null($booking->payment_status);
                    });
                @endphp

                @if($transactionBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Booking</th>
                                    <th>Service</th>
                                    <th>Beautician</th>
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
                                        <td>{{ $booking->service->service_name ?? '-' }}</td>
                                        <td>{{ $booking->beautician->name ?? '-' }}</td>
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

<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('client.reports.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            @csrf
            <input type="hidden" name="booking_id" id="reportBookingId">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-danger">Report Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Please describe the issue with your appointment. You can also upload a proof image.</p>
                
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">REASON</label>
                    <textarea name="reason" class="form-control bg-light border-0" rows="4" required placeholder="Describe what happened..."></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">PROOF IMAGE (OPTIONAL)</label>
                    <input type="file" name="proof_image" class="form-control bg-light border-0" accept="image/*">
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger rounded-pill px-4">Submit Report</button>
            </div>
        </form>
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
        <button type="button" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</button>
      </div>
    </div>
  </div>
</div>



    </div>



<!-- Existing Booking Warning Modal (Red) -->
<div class="modal fade" id="existingBookingModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white"> <!-- changed here -->
        <h5 class="modal-title">Slot Unavailable</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button> <!-- white close button -->
      </div>
      <div class="modal-body">
        <p class="mb-0">
          This time slot has just been booked by another client or is no longer available.
        </p>
        <p class="text-muted mt-2">
          Please select a different time for your appointment.
        </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Okay</button>
      </div>
    </div>
  </div>
</div>

@if(session('booking_exists'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(
        document.getElementById('existingBookingModal')
    );
    modal.show();
});
</script>
@endif

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0 bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold">Appointment Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Service & Status -->
                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-1" id="bookingModalServiceName">Service Name</h4>
                    <p class="text-muted mb-2">with <span id="bookingModalBeauticianName">Beautician</span></p>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill text-uppercase" id="bookingModalStatus">PENDING</span>
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 text-center h-100">
                            <i class="bi bi-calendar-event text-primary mb-2 fs-4"></i>
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Date</small>
                            <span class="fw-bold" id="bookingModalDate">Date</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 text-center h-100">
                            <i class="bi bi-clock text-primary mb-2 fs-4"></i>
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Time</small>
                            <span class="fw-bold" id="bookingModalTime">Time</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <i class="bi bi-geo-alt text-primary mb-2 fs-4"></i>
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Location</small>
                            <span class="fw-bold" id="bookingModalLocation">Location</span>
                        </div>
                    </div>
                    <div class="col-12 d-none" id="bookingModalAllergyContainer">
                        <div class="p-3 bg-danger-subtle text-danger rounded-3 text-center border border-danger">
                            <i class="bi bi-exclamation-triangle-fill mb-2 fs-4"></i>
                            <small class="d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Allergy Alert</small>
                            <span class="fw-bold" id="bookingModalAllergyInfo"></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="border-top pt-4">
                    <h6 class="fw-bold mb-3">Payment Summary</h6>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Payment Status</span>
                        <span class="badge bg-secondary" id="bookingModalPaymentStatus">Pending</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Down Payment (Paid)</span>
                        <span class="fw-bold text-success" id="bookingModalDownPayment">₱0.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Service Price</span>
                        <span class="fw-bold" id="bookingModalOriginalPrice">₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount Applied</span>
                        <span class="fw-bold" id="bookingModalDiscount">-₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                        <span class="fw-bold fs-5">Amount Payable</span>
                        <span class="fw-bold fs-5 text-primary" id="bookingModalTotal">₱0.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 justify-content-center pb-4 flex-column">
                <form id="cancelBookingForm" method="POST" action="" class="d-none w-100 mb-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger rounded-pill w-100" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel Booking</button>
                </form>
                <button type="button" class="btn btn-outline-secondary rounded-pill px-5" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function openBookingDetailsModal(data) {
    document.getElementById('bookingModalServiceName').textContent = data.serviceName;
    document.getElementById('bookingModalBeauticianName').textContent = data.beauticianName;
    document.getElementById('bookingModalDate').textContent = data.date;
    document.getElementById('bookingModalTime').textContent = data.time;
    document.getElementById('bookingModalLocation').textContent = data.location;
    document.getElementById('bookingModalStatus').textContent = data.status;

    // Allergy Info
    const allergyContainer = document.getElementById('bookingModalAllergyContainer');
    const allergyInfo = document.getElementById('bookingModalAllergyInfo');
    
    if (data.allergyInfo) {
        allergyContainer.classList.remove('d-none');
        allergyInfo.textContent = data.allergyInfo;
    } else {
        allergyContainer.classList.add('d-none');
        allergyInfo.textContent = '';
    }

    // Financials
    const total = parseFloat(data.totalCost);
    const discount = parseFloat(data.discountAmount);
    const downPayment = parseFloat(data.downPayment || 0);
    // Note: totalCost in DB is remaining balance if we treat it that way, 
    // BUT typically total_cost is the full price. 
    // Assuming total_cost is FULL price.
    const original = total + discount;

    const paymentStatusEl = document.getElementById('bookingModalPaymentStatus');
    if (data.paymentStatus) {
        paymentStatusEl.textContent = data.paymentStatus.charAt(0).toUpperCase() + data.paymentStatus.slice(1);
        paymentStatusEl.className = 'badge ' + (data.paymentStatus === 'paid' ? 'bg-success' : 'bg-warning text-dark');
    } else {
        paymentStatusEl.textContent = 'Pending';
        paymentStatusEl.className = 'badge bg-secondary';
    }

    document.getElementById('bookingModalOriginalPrice').textContent = '₱' + original.toFixed(2);
    document.getElementById('bookingModalDownPayment').textContent = '₱' + downPayment.toFixed(2);
    document.getElementById('bookingModalDiscount').textContent = '-₱' + discount.toFixed(2);
    document.getElementById('bookingModalTotal').textContent = '₱' + total.toFixed(2);
    document.getElementById('bookingModalTotal').textContent = '₱' + total.toFixed(2);

    const modalEl = document.getElementById('bookingDetailsModal');
    // Update status badge color
    const statusBadge = document.getElementById('bookingModalStatus');
    statusBadge.className = 'badge px-3 py-2 rounded-pill text-uppercase';
    if(data.status.toLowerCase() === 'pending') {
        statusBadge.classList.add('bg-warning', 'text-dark');
    } else if(data.status.toLowerCase() === 'completed') {
        statusBadge.classList.add('bg-success', 'text-white');
    } else if(data.status.toLowerCase() === 'cancelled') {
        statusBadge.classList.add('bg-danger', 'text-white');
    } else {
        statusBadge.classList.add('bg-secondary', 'text-white');
    }

    // Payment Status Badge
    const paymentBadge = document.getElementById('bookingModalPaymentStatus');
    paymentBadge.className = 'badge px-3 py-2 rounded-pill text-uppercase';
    paymentBadge.textContent = data.paymentStatus.replace('_', ' '); // Replace underscore with space
    
    const pStatus = data.paymentStatus.toLowerCase();
    if(pStatus === 'paid') {
        paymentBadge.classList.add('bg-success', 'text-white');
    } else if(pStatus === 'pending') {
        paymentBadge.classList.add('bg-warning', 'text-dark');
    } else if(pStatus === 'pending_verification') {
        paymentBadge.classList.add('bg-info', 'text-dark');
    } else {
        paymentBadge.classList.add('bg-secondary', 'text-white');
    }

    // Cancel Button Logic
    const cancelForm = document.getElementById('cancelBookingForm');
    if (cancelForm) {
        if (data.status.toLowerCase() === 'pending') {
            cancelForm.classList.remove('d-none');
            cancelForm.action = "{{ url('bookings') }}/" + data.id + "/cancel"; 
        } else {
            cancelForm.classList.add('d-none');
        }
    }

    new bootstrap.Modal(modalEl).show();
}
</script>

<!-- Report Success Modal -->
<div class="modal fade" id="reportSuccessModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-success text-white border-bottom-0" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold">Report Submitted</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-3">Thank You!</h5>
                <p class="text-muted mb-0">
                    Your report has been submitted successfully. Our team will review it shortly and get back to you.
                </p>
            </div>
            <div class="modal-footer border-top-0 justify-content-center pb-4">
                <button type="button" class="btn btn-success rounded-pill px-5" data-bs-dismiss="modal">Okay, Got it</button>
            </div>
        </div>
    </div>
</div>

@if(session('report_submitted'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(
        document.getElementById('reportSuccessModal')
    );
    modal.show();
});
</script>
@endif

<!-- ===== MOBILE BOTTOM BAR ===== -->
<div class="mobile-bottom-bar d-md-none">
    <button id="openLeft" title="Profile">
        <img src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png" alt="Profile" width="24" height="24">
    </button>
    <button id="mobileThemeToggle" title="Dark Mode">
        <i class="bi bi-moon-fill" style="font-size: 24px; color: var(--text-dark);"></i>
    </button>
    <button id="openEdit" title="Edit Profile">
        <img src="https://cdn-icons-png.flaticon.com/512/1159/1159633.png" alt="Edit" width="24" height="24">
    </button>
    <button id="openRight" title="Appointments">
        <img src="https://cdn-icons-png.flaticon.com/512/747/747310.png" alt="Appointments" width="24" height="24">
    </button>
</div>



<script>
    function setReportBookingId(id) {
        document.getElementById('reportBookingId').value = id;
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star Rating Logic
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('star-item')) {
            const star = e.target;
            const container = star.closest('.star-rating-group');
            const wrapper = container.parentElement;
            const input = wrapper.querySelector('.rating-value');
            const errorMsg = wrapper.querySelector('.rating-error');
            const value = parseInt(star.dataset.value);
            
            // Set value
            if(input) input.value = value;
            if(errorMsg) errorMsg.style.setProperty('display', 'none', 'important');
            
            // Update UI
            updateStars(container, value);
        }
    });

    document.body.addEventListener('mouseover', function(e) {
        if (e.target.classList.contains('star-item')) {
            const star = e.target;
            const container = star.closest('.star-rating-group');
            const value = parseInt(star.dataset.value);
            updateStars(container, value);
        }
    });

    document.body.addEventListener('mouseout', function(e) {
        if (e.target.classList.contains('star-item')) {
            const container = e.target.closest('.star-rating-group');
            const wrapper = container.parentElement;
            const input = wrapper.querySelector('.rating-value');
            const value = (input && input.value) ? parseInt(input.value) : 0;
            updateStars(container, value);
        }
    });

    function updateStars(container, value) {
        const stars = container.querySelectorAll('.star-item');
        stars.forEach(s => {
            const sVal = parseInt(s.dataset.value);
            if (sVal <= value) {
                s.classList.remove('bi-star');
                s.classList.add('bi-star-fill');
            } else {
                s.classList.remove('bi-star-fill');
                s.classList.add('bi-star');
            }
        });
    }

    // Form validation for rating
    document.body.addEventListener('submit', function(e) {
        if (e.target.tagName === 'FORM' && e.target.querySelector('.rating-value')) {
            const input = e.target.querySelector('.rating-value');
            if (!input.value) {
                e.preventDefault();
                const errorMsg = e.target.querySelector('.rating-error');
                if(errorMsg) errorMsg.style.setProperty('display', 'block', 'important');
                // Shake animation or scroll to view could be added here
            }
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('submit', function(e) {
        if (e.target.classList.contains('ajax-review-form')) {
            e.preventDefault();
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Client-side validation check (double check rating)
            const ratingInput = form.querySelector('.rating-value');
            if (ratingInput && !ratingInput.value) {
                alert('Please select a star rating before submitting.');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';

            const formData = new FormData(form);
            const url = form.action;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async response => {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const data = await response.json();
                    if (!response.ok) {
                        // Handle Validation Errors (422)
                        if (response.status === 422 && data.errors) {
                             const messages = Object.values(data.errors).flat().join('\n');
                             throw new Error(messages || 'Validation failed.');
                        }
                        throw new Error(data.message || 'Server error occurred.');
                    }
                    return data;
                } else {
                    // Non-JSON response (e.g., HTML error page)
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    throw new Error('Server returned an unexpected response. Please try again.');
                }
            })
            .then(data => {
                if (data.message) {
                    // Success!
                    const bookingId = form.querySelector('input[name="booking_id"]').value;
                    const reviewSection = document.querySelector(`.review-section[data-booking-id="${bookingId}"]`);
                    
                    if (reviewSection && data.review) {
                        // Generate stars HTML
                        let starsHtml = '';
                        for(let i=1; i<=5; i++) {
                            starsHtml += `<i class="bi bi-star${i <= data.review.rating ? '-fill' : ''}"></i>`;
                        }

                        // Replace content
                        const imageHtml = data.review.image_url 
                            ? `<div class="mt-2"><img src="${data.review.image_url.startsWith('http') ? data.review.image_url : window.location.origin + '/' + data.review.image_url}" alt="Review Image" class="img-fluid rounded-3" style="max-height: 150px; object-fit: cover;"></div>` 
                            : '';

                        reviewSection.innerHTML = `
                            <div class="bg-light p-3 rounded-3 mt-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-warning me-2">
                                        ${starsHtml}
                                    </div>
                                    <small class="text-muted">Just now</small>
                                </div>
                                <p class="mb-0 small fst-italic">"${data.review.comment}"</p>
                                ${imageHtml}
                            </div>
                        `;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                alert(error.message || 'Something went wrong. Please try again.');
            });
        }
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    
    const categoryCards = document.querySelectorAll('.service-card');
    const beauticiansCards = document.getElementById('beauticiansCards');

    // Initialize modals
    const beauticiansModal = new bootstrap.Modal(document.getElementById('beauticiansModal'), { backdrop: false });
    const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal')); // Default backdrop for booking
    const galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'), { backdrop: false });
    const reviewsModal = new bootstrap.Modal(document.getElementById('reviewsModal'), { backdrop: false });
    
    // Check if editProfileModal is already initialized
    let editProfileModal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
    if (!editProfileModal) {
        editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'), { backdrop: false });
    }

    // Modal controls
    const modalServiceId = document.getElementById('modalServiceId');
    const modalBeauticianId = document.getElementById('modalBeauticianId');
    const bookingServiceTitle = document.getElementById('bookingServiceName');
    const beauticiansModalTitle = document.getElementById('beauticiansModalTitle');
    const galleryModalTitle = document.getElementById('galleryModalTitle');
    const galleryContainer = document.getElementById('galleryContainer');
    const reviewsModalTitle = document.getElementById('reviewsModalTitle');
    const reviewsContainer = document.getElementById('reviewsContainer');
    const modalBookingDate = document.getElementById('modalBookingDate');
    const timeSlotsContainer = document.getElementById('timeSlotsContainer');
    const selectedTimeInput = document.getElementById('selectedTimeInput');
    const selectedSlotIdInput = document.getElementById('selectedSlotIdInput');

    // Store beautician data to access gallery later
    let currentBeauticiansData = [];
    let currentCategory = '';

    // Helper to calculate availability
    const getBeauticianAvailability = (b) => {
        const now = new Date();
        const todayDay = now.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
        const availabilities = Array.isArray(b.availabilities) ? b.availabilities : [];
        const todayAvailability = availabilities.find(a => a.day_of_week.toLowerCase() === todayDay);

        let available = false;
        let statusLabel = 'Unavailable Today';
        let statusClass = 'bg-danger'; // default red

        if (todayAvailability && todayAvailability.status === 'active') {
            const [startH, startM, startS] = todayAvailability.start_time.split(':').map(Number);
            const [endH, endM, endS] = todayAvailability.end_time.split(':').map(Number);

            const startTime = new Date();
            startTime.setHours(startH, startM, startS, 0);
            const endTime = new Date();
            endTime.setHours(endH, endM, endS, 0);

            if (endTime <= startTime) endTime.setDate(endTime.getDate() + 1); // overnight handling

            const format12Hour = (date) => {
                let hours = date.getHours();
                const minutes = date.getMinutes().toString().padStart(2,'0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                if (hours === 0) hours = 12;
                return `${hours}:${minutes} ${ampm}`;
            }

            if (now >= startTime && now <= endTime) {
                available = true;
                statusLabel = `Available Today ${format12Hour(startTime)} - ${format12Hour(endTime)}`;
                statusClass = 'bg-success';
            } else if (now < startTime) {
                available = true; // Still allow booking for later today
                statusLabel = `Opens at ${format12Hour(startTime)}`;
                statusClass = 'bg-warning text-dark';
            } else {
                statusLabel = `Closed (Ended ${format12Hour(endTime)})`;
                statusClass = 'bg-secondary';
            }
        }
        return { available, statusLabel, statusClass };
    };

    // Helper to fetch slots
    const fetchAvailableSlots = (beauticianId, date) => {
        timeSlotsContainer.innerHTML = '<p class="text-muted small m-2 text-center w-100">Loading slots...</p>';
        selectedTimeInput.value = ''; // Reset selection
        selectedSlotIdInput.value = '';
        
        fetch(`/available-slots?beautician_id=${beauticianId}&date=${date}`)
            .then(res => res.json())
            .then(slots => {
                timeSlotsContainer.innerHTML = '';
                if (slots.length === 0) {
                    timeSlotsContainer.innerHTML = '<p class="text-muted small m-2 text-center w-100">No slots available for this date.</p>';
                } else {
                    slots.forEach(slot => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'time-slot-btn';
                        
                        // New format: slot.time is already formatted as "10:30 AM - 12:30 PM"
                        btn.textContent = slot.time;
                        
                        if (slot.status === 'booked') {
                            btn.disabled = true;
                            btn.title = 'Already Booked';
                        } else {
                            btn.onclick = () => {
                                document.querySelectorAll('.time-slot-btn').forEach(b => b.classList.remove('selected'));
                                btn.classList.add('selected');
                                // Use start_time for the backend value
                                selectedTimeInput.value = slot.start_time;
                                selectedSlotIdInput.value = slot.id;
                            };
                        }
                        
                        timeSlotsContainer.appendChild(btn);
                    });
                }
            })
            .catch(err => {
                console.error(err);
                timeSlotsContainer.innerHTML = '<p class="text-danger small m-2 text-center w-100">Error loading slots</p>';
            });
    };

    // Date change listener
    modalBookingDate.addEventListener('change', () => {
        const date = modalBookingDate.value;
        const bId = modalBeauticianId.value;
        if (date && bId) {
            fetchAvailableSlots(bId, date);
        }
    });

    // --- RENDER HELPER ---
    const renderBeauticianCardHtml = (b, options = {}) => {
        const { available, statusLabel, statusClass } = getBeauticianAvailability(b);
        const services = Array.isArray(b.services) ? b.services : [];
        if (!services.length) return '';

        const servicesHtml = services.map(s => {
            const basePrice = parseFloat(s.base_price);
            const discount = parseFloat(s.discount_percentage || 0);
            let finalPrice = basePrice;
            let priceDisplay = `<em>₱${basePrice.toFixed(2)}</em>`;
            
            if (discount > 0) {
                finalPrice = basePrice * (1 - discount / 100);
                priceDisplay = `
                    <small class="text-decoration-line-through text-muted" style="font-size: 0.8em;">₱${basePrice.toFixed(2)}</small><br>
                    <em class="text-danger fw-bold">₱${finalPrice.toFixed(2)}</em>
                    <span class="text-danger fw-bold ms-1" style="font-size: 0.8em;">-${discount}%</span>
                `;
            }

            return `
        <div class="service-item">
            <strong>${s.service_name}</strong><br>
            ${priceDisplay}<br>
            <button type="button" class="btn btn-sm mt-1 ${available ? 'btn-success' : 'btn-secondary'}"
                ${available ? '' : 'disabled'}
                data-id="${b.id}"
                data-service-id="${s.id}"
                data-service-name="${s.service_name}"
                data-service-price="${finalPrice}"
                data-beautician-name="${b.name}"
                data-qr-code-path="${b.qr_code_path || ''}"
                data-beautician-location="${(b.latitude && b.longitude) ? (b.latitude + ',' + b.longitude) : (b.base_location || '')}">
                ${available ? 'Book' : 'Unavailable'}
            </button>
             <small class="text-muted d-block mt-1"><i class="bi bi-clock me-1"></i>${s.duration_minutes || 60} mins</small>
        </div>
    `}).join('');

        const gallery = Array.isArray(b.gallery) ? b.gallery : [];
        const firstImage = gallery.length > 0 
            ? (gallery[0].image_url.startsWith('http') ? gallery[0].image_url : window.location.origin + '/' + gallery[0].image_url) 
            : null;

        return `
            <div class="beautician-card">
                <div class="d-flex align-items-center justify-content-between w-100 p-3">
                    <!-- Left: Beautician Info (Clickable) -->
                    <div class="d-flex align-items-center gap-3 flex-grow-1 beautician-trigger" style="cursor: pointer;" data-id="${b.id}">
                        <div class="position-relative beautician-profile-trigger" data-id="${b.id}" style="transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            <img src="${b.photo_url ? (b.photo_url.startsWith('http') ? b.photo_url : window.location.origin + '/' + b.photo_url) : 'https://via.placeholder.com/80'}" 
                                 alt="${b.name}" 
                                 class="rounded-circle border shadow-sm"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                            <span class="position-absolute bottom-0 end-0 p-1 border border-white rounded-circle ${statusClass.replace('bg-', 'text-bg-')}" style="width: 12px; height: 12px;"></span>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">${b.name}</h6>
                            <div class="d-flex align-items-center gap-1 mb-1">
                                ${b.rating_avg ? `
                                    <span class="text-warning small"><i class="bi bi-star-fill"></i> ${parseFloat(b.rating_avg).toFixed(1)}</span>
                                ` : '<span class="text-muted small">New</span>'}
                                <span class="text-muted small mx-1">•</span>
                                <span class="${statusClass.replace('bg-', 'text-').replace('text-dark', '')} small fw-semibold">${statusLabel}</span>
                            </div>
                            <small class="text-primary fw-semibold" style="font-size: 0.75rem;">View Services <i class="bi bi-chevron-down ms-1"></i></small>
                        </div>
                    </div>

                    <!-- Right: Reviews & Portfolio -->
                    <div class="d-flex align-items-center gap-2 ms-2">
                         <!-- Reviews Button -->
                         <button class="btn btn-light border shadow-sm reviews-preview-btn p-0" 
                                style="width: 70px; height: 70px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: transform 0.2s;"
                                onmouseover="this.style.transform='scale(1.05)'" 
                                onmouseout="this.style.transform='scale(1)'"
                                data-id="${b.id}"
                                title="View Reviews">
                            <i class="bi bi-chat-quote-fill text-warning mb-1" style="font-size: 1.5rem;"></i>
                            <small class="fw-bold text-muted" style="font-size: 0.65rem;">REVIEWS</small>
                        </button>

                        <div class="portfolio-preview-btn position-relative shadow-sm" 
                             style="width: 70px; height: 70px; border-radius: 12px; overflow: hidden; cursor: pointer; border: 1px solid var(--border-color); background: #f8f9fa;"
                             data-id="${b.id}"
                             title="View Portfolio">
                            ${firstImage ? `
                                <img src="${firstImage}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9;">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                                     style="background: rgba(0,0,0,0.3); transition: 0.2s;">
                                    <i class="bi bi-images text-white"></i>
                                </div>
                            ` : `
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="bi bi-images mb-1" style="font-size: 1.2rem;"></i>
                                    <small style="font-size: 0.6rem; font-weight: bold; text-transform: uppercase;">PORTFOLIO</small>
                                </div>
                            `}
                        </div>
                    </div>
                </div>

                <!-- Hidden Services Section -->
                <div class="service-list-container w-100 bg-light border-top ${options.expanded ? '' : 'd-none'}" id="services-${b.id}">
                    <div class="p-3">
                        <h6 class="text-muted small fw-bold mb-3 text-uppercase">Services Offered</h6>
                        <div class="d-flex flex-column gap-2">
                            ${servicesHtml}
                        </div>
                    </div>
                </div>
            </div>
        `;
    };

    // Service Search Logic (AJAX)
    const serviceSearch = document.getElementById('serviceSearch');
    const searchResults = document.getElementById('searchResults');
    const serviceCardsContainer = document.querySelector('.service-cards');
    let searchTimeout;
    let currentSearchData = [];
    
    if (serviceSearch && searchResults) {
        serviceSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            
            clearTimeout(searchTimeout);

            if (searchTerm === '') {
                serviceCardsContainer.classList.remove('d-none');
                searchResults.classList.add('d-none');
                searchResults.innerHTML = '';
                return;
            }

            serviceCardsContainer.classList.add('d-none');
            searchResults.classList.remove('d-none');
            searchResults.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted">Searching services...</p></div>';

            searchTimeout = setTimeout(() => {
                const url = "{{ route('client.services.filter', ['service' => ':service']) }}".replace(':service', encodeURIComponent(searchTerm));
                
                fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    currentSearchData = data; // Store for event delegation
                    if (!Array.isArray(data) || !data.length) {
                        searchResults.innerHTML = `<div class="text-center p-4 text-muted">No services found matching "${e.target.value}"</div>`;
                        return;
                    }
                    searchResults.innerHTML = data.map(b => renderBeauticianCardHtml(b, { expanded: true })).join('');
                })
                .catch(err => {
                    console.error(err);
                    searchResults.innerHTML = '<div class="text-center p-4 text-danger">Error searching services. Please try again.</div>';
                });
            }, 500); // 500ms debounce
        });
    }

    categoryCards.forEach(card => {
        card.addEventListener('click', () => {
            const category = card.dataset.category;
            currentCategory = category;
            beauticiansModal.show();
            beauticiansCards.innerHTML = "<p class='text-center'>Loading...</p>";

            const url = "{{ route('client.services.beauticians', ['category' => ':category']) }}".replace(':category', encodeURIComponent(category));
            fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(async res => {
                if (!res.ok) {
                    const text = await res.text();
                    console.error('Fetch error:', res.status, text);
                    throw new Error(`Server error: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                beauticiansCards.innerHTML = '';
                beauticiansModalTitle.textContent = `Beauticians offering ${category} services`;
                currentBeauticiansData = data; // Store data

                if (!Array.isArray(data) || !data.length) {
                    beauticiansCards.innerHTML = '<p class="text-center w-100">No beauticians found.</p>';
                    return;
                }

                beauticiansCards.innerHTML = data.map(b => renderBeauticianCardHtml(b)).join('');
            })
            .catch(err => {
                beauticiansCards.innerHTML = `<p class='text-danger'>Error loading beauticians: ${err.message}</p>`;
                console.error('Beautician load error:', err);
            });
        });
    });

    // Unified Event Handler for Beautician Cards (Search & Category)
    const handleBeauticianCardAction = (e, dataSource) => {
        // 0. Handle Profile Click (Image) -> Priority over Toggle
        const profileTrigger = e.target.closest('.beautician-profile-trigger');
        if (profileTrigger) {
            e.stopPropagation(); // Stop bubbling to beautician-trigger
            const bId = profileTrigger.dataset.id;
            const beautician = dataSource.find(b => b.id == bId);
            
            if (beautician) {
                // Populate Modal
                document.getElementById('profileModalName').textContent = beautician.name;
                document.getElementById('profileModalLocation').innerHTML = `<i class="bi bi-geo-alt-fill me-1"></i> ${beautician.base_location || 'No location set'}`;
                
                // Rating
                const rating = beautician.rating_avg ? parseFloat(beautician.rating_avg) : 0;
                document.getElementById('profileModalRatingText').textContent = rating > 0 ? `${rating.toFixed(1)} / 5.0` : 'No ratings yet';
                let starsHtml = '';
                for(let i=1; i<=5; i++) {
                    if(i <= Math.floor(rating)) {
                        starsHtml += '<i class="bi bi-star-fill"></i>';
                    } else if(i === Math.ceil(rating) && !Number.isInteger(rating)) {
                        starsHtml += '<i class="bi bi-star-half"></i>';
                    } else {
                        starsHtml += '<i class="bi bi-star"></i>';
                    }
                }
                document.getElementById('profileModalStars').innerHTML = starsHtml;

                // Contact
                document.getElementById('profileModalEmail').textContent = beautician.email;
                document.getElementById('profileModalPhone').textContent = beautician.phone;
                
                // Image
                const imgUrl = beautician.photo_url ? (beautician.photo_url.startsWith('http') ? beautician.photo_url : window.location.origin + '/' + beautician.photo_url) : 'https://via.placeholder.com/150';
                document.getElementById('profileModalImage').src = imgUrl;

                // Status Badge
                const { available, statusLabel, statusClass } = getBeauticianAvailability(beautician);
                const badgeEl = document.getElementById('profileModalStatusBadge');
                badgeEl.className = `position-absolute bottom-0 end-0 badge rounded-pill border border-2 border-white px-3 py-2 shadow-sm ${statusClass}`;
                
                let badgeText = 'Unavailable';
                if (statusLabel.startsWith('Available')) badgeText = 'Available';
                else if (statusLabel.startsWith('Opens')) badgeText = 'Opens Soon';
                else if (statusLabel.startsWith('Closed')) badgeText = 'Closed';
                badgeEl.innerHTML = `<i class="bi bi-circle-fill me-1 small" style="font-size: 0.5rem;"></i>${badgeText}`;

                // Services List
                const servicesContainer = document.getElementById('profileModalServices');
                const services = Array.isArray(beautician.services) ? beautician.services : [];
                
                if (services.length > 0) {
                    servicesContainer.innerHTML = services.map(s => {
                        const basePrice = parseFloat(s.base_price);
                        const discount = parseFloat(s.discount_percentage || 0);
                        let priceDisplay = `<h6 class="fw-bold text-primary mb-0">₱${basePrice.toFixed(2)}</h6>`;
                        let discountBadge = '';

                        if (discount > 0) {
                            const discountedPrice = basePrice * (1 - discount / 100);
                            priceDisplay = `
                                <div class="text-end">
                                    <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.8rem;">₱${basePrice.toFixed(2)}</small>
                                    <h6 class="fw-bold text-danger mb-0">₱${discountedPrice.toFixed(2)}</h6>
                                </div>
                            `;
                            discountBadge = `<span class="text-danger fw-bold ms-2" style="font-size: 1.1rem;">-${discount}% OFF</span>`;
                        }
                        
                        // Calculate final price for data attribute
                        const finalPrice = discount > 0 ? (basePrice * (1 - discount / 100)) : basePrice;

                        return `
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border-start border-4 border-primary shadow-sm mb-2">
                            <div>
                                <div class="d-flex align-items-center">
                                    <h6 class="fw-bold mb-1">${s.service_name}</h6>
                                    ${discountBadge}
                                </div>
                                <small class="text-muted d-block"><i class="bi bi-clock me-1"></i>${s.duration_minutes || 60} mins</small>
                                <button type="button" class="btn btn-sm mt-2 ${available ? 'btn-success' : 'btn-secondary'}"
                                    ${available ? '' : 'disabled'}
                                    data-id="${beautician.id}"
                                    data-service-id="${s.id}"
                                    data-service-name="${s.service_name}"
                                    data-service-price="${finalPrice.toFixed(2)}"
                                    data-beautician-name="${beautician.name}"
                                    data-qr-code-path="${beautician.qr_code_path || ''}"
                                    data-beautician-location="${(beautician.latitude && beautician.longitude) ? (beautician.latitude + ',' + beautician.longitude) : (beautician.base_location || '')}">
                                    ${available ? 'Book Now' : 'Unavailable'}
                                </button>
                            </div>
                            ${priceDisplay}
                        </div>
                    `}).join('');
                } else {
                    servicesContainer.innerHTML = '<p class="text-muted text-center py-3">No services listed.</p>';
                }
                
                new bootstrap.Modal(document.getElementById('beauticianProfileModal')).show();
            }
            return;
        }

        // 0.5 Handle Reviews Click
        const reviewsBtn = e.target.closest('.reviews-preview-btn');
        if (reviewsBtn) {
            e.stopPropagation();
            const bId = reviewsBtn.dataset.id;
            const beautician = dataSource.find(b => b.id == bId);

            if (beautician) {
                 reviewsModalTitle.textContent = `${beautician.name}'s Reviews`;
                 reviewsContainer.innerHTML = '';
                 
                 const reviews = beautician.reviews || [];
                 
                 if (reviews.length === 0) {
                     reviewsContainer.innerHTML = `
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-quote text-muted display-4 mb-3"></i>
                            <p class="text-muted">No reviews yet.</p>
                        </div>
                     `;
                 } else {
                     reviews.forEach(r => {
                         let starsHtml = '';
                         for(let i=1; i<=5; i++) {
                            starsHtml += `<i class="bi bi-star${i <= r.rating ? '-fill' : ''}"></i>`;
                         }
                         
                         reviewsContainer.innerHTML += `
                            <div class="card border-0 bg-light rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="text-warning small">${starsHtml}</div>
                                        <span class="fw-bold small">${r.client_name}</span>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">${r.created_at}</small>
                                </div>
                                <p class="mb-0 small fst-italic text-muted">"${r.comment}"</p>
                                ${r.image_url ? `
                                <div class="mt-2">
                                    <img src="${r.image_url.startsWith('http') ? r.image_url : window.location.origin + '/' + r.image_url}" class="img-fluid rounded-3 border" style="max-height: 150px; object-fit: cover; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                                </div>
                                ` : ''}
                            </div>
                         `;
                     });
                 }
                 reviewsModal.show();
            }
            return;
        }

        // 1. Handle Portfolio Click (New Square Preview)
        const portfolioBtn = e.target.closest('.portfolio-preview-btn');
        if (portfolioBtn) {
            e.stopPropagation();
            const bId = portfolioBtn.dataset.id;
            const beautician = dataSource.find(b => b.id == bId);
            
            if (beautician) {
                galleryModalTitle.textContent = `${beautician.name}'s Portfolio`;
                galleryContainer.innerHTML = '';
                
                const gallery = beautician.gallery || [];
                
                if (gallery.length === 0) {
                    galleryContainer.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-images text-muted display-4 mb-3"></i>
                            <p class="text-muted">No photos in portfolio yet.</p>
                        </div>
                    `;
                } else {
                    gallery.forEach(img => {
                        galleryContainer.innerHTML += `
                            <div class="col-md-4 col-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div style="height: 200px; overflow: hidden; border-radius: 12px 12px 0 0;">
                                        <img src="${img.image_url.startsWith('http') ? img.image_url : window.location.origin + '/' + img.image_url}" class="w-100 h-100" style="object-fit: cover; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                                    </div>
                                    ${img.description ? `<div class="card-body p-2 bg-light rounded-bottom"><small class="text-muted">${img.description}</small></div>` : ''}
                                </div>
                            </div>
                        `;
                    });
                }
                galleryModal.show();
            }
            return;
        }

        // 2. Handle Service Toggle (Clicking Beautician Info)
        const trigger = e.target.closest('.beautician-trigger');
        if (trigger) {
            const bId = trigger.dataset.id;
            const serviceContainer = document.getElementById(`services-${bId}`);
            const icon = trigger.querySelector('.bi-chevron-down, .bi-chevron-up');
            
            if (serviceContainer) {
                const isHidden = serviceContainer.classList.contains('d-none');
                if (isHidden) {
                    serviceContainer.classList.remove('d-none');
                    if(icon) { icon.classList.remove('bi-chevron-down'); icon.classList.add('bi-chevron-up'); }
                } else {
                    serviceContainer.classList.add('d-none');
                    if(icon) { icon.classList.remove('bi-chevron-up'); icon.classList.add('bi-chevron-down'); }
                }
            }
            return;
        }

        // 3. Handle Booking Button (Inside Services)
        const btn = e.target.closest('.btn-success');
        if (!btn) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        // Manually hide beauticians modal and show booking modal
        const beauticiansModalEl = document.getElementById('beauticiansModal');
        const beauticiansModalInstance = bootstrap.Modal.getInstance(beauticiansModalEl);
        if (beauticiansModalInstance) {
            beauticiansModalInstance.hide();
        }

        // Handle Allergy Section Visibility
        const allergyContainer = document.getElementById('allergyContainer');
        const hasAllergyCheck = document.getElementById('hasAllergyCheck');
        const allergyInputContainer = document.getElementById('allergyInputContainer');
        const allergyInput = document.getElementById('allergyInfoInput');

        if (currentCategory && (currentCategory.toLowerCase().includes('skin care') || currentCategory.toLowerCase().includes('makeup'))) {
            allergyContainer.classList.remove('d-none');
        } else {
            allergyContainer.classList.add('d-none');
        }
        
        // Reset allergy form state
        hasAllergyCheck.checked = false;
        allergyInputContainer.classList.add('d-none');
        allergyInput.value = '';
        allergyInput.required = false;

        modalBeauticianId.value = btn.dataset.id;
        modalServiceId.value = btn.dataset.serviceId;
        document.getElementById('modalServiceName').value = btn.dataset.serviceName;
        bookingServiceTitle.textContent = `Book ${btn.dataset.serviceName} with ${btn.dataset.beauticianName} today!`;

        // Reset Travel Fee Display
        document.getElementById('modalTravelDistance').textContent = '0';
        document.getElementById('modalTravelFee').textContent = '₱0.00';
        document.getElementById('inputTravelFee').value = 0;
        document.getElementById('inputDistance').value = 0;

        // Clear input to ensure fresh start
        const locInput = document.getElementById('bookingLocationInput');
        locInput.value = '';
        window.currentClientLocation = { lat: null, lng: null }; // Reset client location

        // Store Beautician Location
        window.currentBeauticianLocation = { lat: null, lng: null }; // Reset first
        window.forceShowBeautician = true; // Show by default

        // Handle QR Code Display
        const qrPath = btn.dataset.qrCodePath;
        const qrImage = document.getElementById('beauticianQrImage');
        const noQrMsg = document.getElementById('noQrMessage');
        
        if (qrPath && qrPath !== 'null' && qrPath !== '') {
            qrImage.src = qrPath.startsWith('http') ? qrPath : window.location.origin + '/' + qrPath;
            qrImage.style.display = 'block'; // Will be hidden by parent d-none until Manual is selected
            noQrMsg.style.display = 'none';
        } else {
            qrImage.style.display = 'none';
            noQrMsg.style.display = 'block';
        }

        // Reset Payment Option to Pay 50% (Default) and enforce UI
        document.getElementById('opt_pay_now').checked = true;
        togglePaymentOptions();
        
        // Reset toggle button UI
        const toggleBtn = document.querySelector('button[onclick="toggleBeauticianLocation()"]');
        if (toggleBtn) {
            toggleBtn.innerHTML = '<i class="bi bi-eye-slash-fill me-1"></i> Hide Beautician Location';
            toggleBtn.classList.remove('btn-outline-primary');
            toggleBtn.classList.add('btn-primary');
        }

        const locData = btn.dataset.beauticianLocation;
        if (locData) {
            const parts = locData.split(',');
            // Check if it looks like coordinates (two numbers)
            const isCoords = parts.length === 2 && !isNaN(parseFloat(parts[0])) && !isNaN(parseFloat(parts[1])) && !/[a-zA-Z]/.test(locData);
            
            if (isCoords) {
                window.currentBeauticianLocation = {
                    lat: parseFloat(parts[0].trim()),
                    lng: parseFloat(parts[1].trim())
                };
                // Calculate immediately if client location is known
                if (window.currentClientLocation.lat && window.currentClientLocation.lng) {
                     calculateTravelFee(window.currentClientLocation.lat, window.currentClientLocation.lng);
                } else {
                     updateBookingMap();
                }
            } else {
                // It's an address string, use robust geocoding
                let initialQuery = locData;
                if (!initialQuery.toLowerCase().includes('philippines')) {
                    initialQuery += ', Philippines';
                }
                resolveBeauticianLocation(initialQuery);
            }
        }

        // Financials
        const price = parseFloat(btn.dataset.servicePrice || 0);
        const downPayment = price * 0.50;
        document.getElementById('modalServicePrice').textContent = '₱' + price.toFixed(2);
        document.getElementById('modalDownPayment').textContent = '₱' + downPayment.toFixed(2);

        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const todayStr = `${yyyy}-${mm}-${dd}`;
        
        document.getElementById('modalBookingDate').value = todayStr;

        // Trigger slot fetch for today
        fetchAvailableSlots(btn.dataset.id, todayStr);

        // Force show booking modal after delay
        setTimeout(() => {
            const bookingModalEl = document.getElementById('bookingModal');
            let bookingModalInstance = bootstrap.Modal.getInstance(bookingModalEl);
            if (!bookingModalInstance) {
                bookingModalInstance = new bootstrap.Modal(bookingModalEl);
            }
            bookingModalInstance.show();

            // Refresh map size after modal is shown
            setTimeout(() => {
                if(window.bookingMap) window.bookingMap.invalidateSize();
                updateBookingMap();
            }, 300);
        }, 500);
    };

    beauticiansCards.addEventListener('click', e => handleBeauticianCardAction(e, currentBeauticiansData));
    if (searchResults) {
        searchResults.addEventListener('click', e => handleBeauticianCardAction(e, currentSearchData));
    }


    // Allergy Checkbox Logic
    const hasAllergyCheck = document.getElementById('hasAllergyCheck');
    const allergyInputContainer = document.getElementById('allergyInputContainer');
    const allergyInput = document.getElementById('allergyInfoInput');

    if(hasAllergyCheck) {
        hasAllergyCheck.addEventListener('change', function() {
            if(this.checked) {
                allergyInputContainer.classList.remove('d-none');
                allergyInput.required = true;
            } else {
                allergyInputContainer.classList.add('d-none');
                allergyInput.required = false;
                allergyInput.value = '';
            }
        });
    }

    // Open Edit Profile modal without overlay
    const editProfileBtn = document.getElementById('editProfileBtn');
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', () => {
            // Check if instance exists, if not create one
            let editProfileModal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
            if (!editProfileModal) {
                editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'), { backdrop: false });
            }
            editProfileModal.show();
        });
    }

    // Location Map Logic
    let map = null;
    let marker = null;
    const locationModalEl = document.getElementById('locationModal');
    const locationLatInput = document.getElementById('locationLat');
    const locationLngInput = document.getElementById('locationLng');
    const locationAddressInput = document.getElementById('locationAddress');
    const saveLocationBtn = document.getElementById('saveLocationBtn');

    if (locationModalEl) {
        locationModalEl.addEventListener('shown.bs.modal', function () {
            // Default to Manila or saved location
            let lat = parseFloat(locationLatInput.value) || 14.5995;
            let lng = parseFloat(locationLngInput.value) || 120.9842;

            if (!map) {
                map = L.map('map').setView([lat, lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                marker = L.marker([lat, lng], {draggable: true}).addTo(map);

                marker.on('dragend', function(e) {
                    const position = marker.getLatLng();
                    updateLocationInputs(position.lat, position.lng);
                });

                map.on('click', function(e) {
                    marker.setLatLng(e.latlng);
                    updateLocationInputs(e.latlng.lat, e.latlng.lng);
                });
            } else {
                map.invalidateSize();
                map.setView([lat, lng], 13);
                marker.setLatLng([lat, lng]);
            }
        });
    }

    function updateLocationInputs(lat, lng) {
        locationLatInput.value = lat;
        locationLngInput.value = lng;
        
        // Optional: Reverse geocoding here to update address input
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if(data.display_name) {
                    locationAddressInput.value = data.display_name;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    if (saveLocationBtn) {
        saveLocationBtn.addEventListener('click', function() {
            const lat = locationLatInput.value;
            const lng = locationLngInput.value;
            const address = locationAddressInput.value;

            fetch('{{ route("client.location.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng,
                    address: address
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Location updated successfully!');
                    bootstrap.Modal.getInstance(locationModalEl).hide();
                    location.reload(); // Reload to reflect changes
                } else {
                    alert('Failed to update location.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred.');
            });
        });
    }

    // Rotating welcome message
    const rotatingText = document.querySelector('.rotating-text');
    if (rotatingText) {
        const messages = [
            "Ready to book your next service?",
            "All our service providers are verified for your safety.",
            "Your comfort and satisfaction are always our top priority.",
            "Book with confidence — you're in trusted, professional hands.",
            "Every beautician is trained, skilled, and quality-checked.",
            "Experience self-care made easy, safe, and convenient."
        ];

        let index = 0;
        setInterval(() => {
            rotatingText.classList.add('fade-out');

            setTimeout(() => {
                index = (index + 1) % messages.length;
                rotatingText.textContent = messages[index];
                rotatingText.classList.remove('fade-out');
                rotatingText.classList.add('fade-in');

                setTimeout(() => {
                    rotatingText.classList.remove('fade-in');
                }, 600);

            }, 600);
        }, 3500);
    }

    // Theme Toggle Logic
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    const mobileThemeToggle = document.getElementById('mobileThemeToggle');
    const html = document.documentElement;

    function setTheme(theme) {
        if (theme === 'dark') {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            if(themeToggleBtn) themeToggleBtn.innerHTML = '<i class="bi bi-sun-fill"></i> Light Mode';
            if(mobileThemeToggle) mobileThemeToggle.innerHTML = '<i class="bi bi-sun-fill" style="font-size: 24px; color: var(--text-dark);"></i>';
        } else {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            if(themeToggleBtn) themeToggleBtn.innerHTML = '<i class="bi bi-moon-stars"></i> Dark Mode';
            if(mobileThemeToggle) mobileThemeToggle.innerHTML = '<i class="bi bi-moon-fill" style="font-size: 24px; color: var(--text-dark);"></i>';
        }
    }

    // Init
    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);

    // Events
    if(themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const isDark = html.classList.contains('dark');
            setTheme(isDark ? 'light' : 'dark');
        });
    }
    if(mobileThemeToggle) {
        mobileThemeToggle.addEventListener('click', () => {
            const isDark = html.classList.contains('dark');
            setTheme(isDark ? 'light' : 'dark');
        });
    }
});

function updateServiceAvailability() {
    // ... existing function ...
}

// Sidebars & Modals
const leftSidebar = document.querySelector('.sidebar');
const rightSidebar = document.querySelector('.right-sidebar');

// Get or init modal safely
const getEditProfileModal = () => {
    const el = document.getElementById('editProfileModal');
    let modal = bootstrap.Modal.getInstance(el);
    if (!modal) {
        modal = new bootstrap.Modal(el, { backdrop: false });
    }
    return modal;
};

// PROFILE BUTTON → left sidebar
document.getElementById('openLeft')?.addEventListener('click', () => {
    leftSidebar.classList.toggle('show');
    rightSidebar.classList.remove('show'); 
    getEditProfileModal().hide();
});

// EDIT PROFILE BUTTON → edit modal
document.getElementById('openEdit')?.addEventListener('click', () => {
    getEditProfileModal().show();
    leftSidebar.classList.remove('show');
    rightSidebar.classList.remove('show');
});

// APPOINTMENTS BUTTON → right sidebar
document.getElementById('openRight')?.addEventListener('click', () => {
    rightSidebar.classList.toggle('show');
    leftSidebar.classList.remove('show');
    getEditProfileModal().hide();
});

// OPTIONAL: click outside to close sidebars
document.addEventListener('click', (e) => {
    if (!leftSidebar.contains(e.target) && !rightSidebar.contains(e.target) &&
        !e.target.closest('.mobile-bottom-bar')) {
        leftSidebar.classList.remove('show');
        rightSidebar.classList.remove('show');
    }
});

// Close Edit Profile modal when touching anywhere outside on mobile
document.addEventListener('click', (e) => {
    const isMobile = window.innerWidth <= 768;
    if (!isMobile) return;

    const editModalEl = document.getElementById('editProfileModal');
    const editModal = bootstrap.Modal.getInstance(editModalEl);
    if (!editModalEl || !editModal) return;

    // If modal is open AND click is outside modal content AND not on open buttons
    if (editModalEl.classList.contains('show') && 
        !e.target.closest('.modal-dialog') && 
        !e.target.closest('#openEdit') && 
        !e.target.closest('#editProfileBtn')) {
        editModal.hide();
    }
});

// Re-open modal if there are validation errors
const hasValidationErrors = "{{ $errors->any() ? 'true' : 'false' }}" === "true";

if (hasValidationErrors) {
    document.addEventListener('DOMContentLoaded', function() {
        const editProfileModalEl = document.getElementById('editProfileModal');
        if(editProfileModalEl) {
            const modal = new bootstrap.Modal(editProfileModalEl);
            modal.show();
        }
    });
}

function togglePaymentMethod() {
    const isManual = document.getElementById('pm_manual').checked;
    const paymongoSection = document.getElementById('paymongoSection');
    const manualQrSection = document.getElementById('manualQrSection');
    const receiptInput = document.querySelector('input[name="payment_receipt"]');
    
    if (isManual) {
        paymongoSection.classList.add('d-none');
        manualQrSection.classList.remove('d-none');
        if(receiptInput) receiptInput.required = true;
    } else {
        paymongoSection.classList.remove('d-none');
        manualQrSection.classList.add('d-none');
        if(receiptInput) receiptInput.required = false;
    }
}

function togglePaymentOptions() {
    const isBookOnly = document.getElementById('opt_pay_later').checked;
    const isPayFull = document.getElementById('opt_pay_full').checked;
    const isPayNow = document.getElementById('opt_pay_now').checked;

    // Update Card UI
    document.getElementById('card_pay_full').classList.toggle('selected', isPayFull);
    document.getElementById('card_pay_now').classList.toggle('selected', isPayNow);
    document.getElementById('card_pay_later').classList.toggle('selected', isBookOnly);

    const methodContainer = document.getElementById('paymentMethodContainer');
    const paymongoSection = document.getElementById('paymongoSection');
    const manualQrSection = document.getElementById('manualQrSection');
    const receiptInput = document.querySelector('input[name="payment_receipt"]');
    const confirmBtn = document.getElementById('confirmBookingBtn');
    
    // Always hide method container to enforce Manual QR/Receipt for payments
    methodContainer.classList.add('d-none');

    if (isBookOnly) {
        paymongoSection.classList.add('d-none');
        manualQrSection.classList.add('d-none');
        if(receiptInput) receiptInput.required = false;

        // Show Book button immediately for Pay Later
        if(confirmBtn) confirmBtn.classList.remove('d-none');
    } else {
        // Enforce Manual QR for Pay Full or Pay 50%
        document.getElementById('pm_manual').checked = true; // Ensure radio is checked
        
        paymongoSection.classList.add('d-none');
        manualQrSection.classList.remove('d-none');
        if(receiptInput) receiptInput.required = true;

        // Show Book button ONLY if receipt is selected
        if(confirmBtn) {
            if (receiptInput && receiptInput.files && receiptInput.files.length > 0) {
                confirmBtn.classList.remove('d-none');
            } else {
                confirmBtn.classList.add('d-none');
            }
        }
    }
}
</script>


<div id="client-config-data"
     data-address="{{ $client->address ?? '' }}"
     data-lat="{{ $client->latitude ?? '' }}"
     data-lng="{{ $client->longitude ?? '' }}"
     style="display:none;"></div>

<script>
// Client Data from Blade
const clientConfig = document.getElementById('client-config-data').dataset;
const clientData = {
    address: clientConfig.address,
    lat: parseFloat(clientConfig.lat) || null,
    lng: parseFloat(clientConfig.lng) || null
};

// Global state for travel fee calculation
window.currentBeauticianLocation = { lat: null, lng: null };
window.currentClientLocation = { lat: null, lng: null };
window.isSuggestionClicked = false;
window.bookingMap = null;
window.bookingMapMarkers = { beautician: null, client: null };
window.routingControl = null;
window.forceShowBeautician = false;

// Initialize client location if available
if (clientData.lat && clientData.lng) {
    window.currentClientLocation = { 
        lat: parseFloat(clientData.lat), 
        lng: parseFloat(clientData.lng) 
    };
}

function resolveBeauticianLocation(query, attempt = 1, originalQuery = null) {
    if (!originalQuery) originalQuery = query;

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ph&limit=1`)
        .then(res => res.json())
        .then(data => {
            if (data && data.length > 0) {
                window.currentBeauticianLocation = {
                    lat: parseFloat(data[0].lat),
                    lng: parseFloat(data[0].lon)
                };
                
                if (window.currentClientLocation.lat && window.currentClientLocation.lng) {
                     calculateTravelFee(window.currentClientLocation.lat, window.currentClientLocation.lng);
                } else {
                     updateBookingMap();
                }
            } else {
                let nextQuery = null;
                
                if (attempt === 1) {
                    let clean = query.replace(/(?:Block|Blk|Lot|Unit|Bldg|Phase|Ph)\.?\s*[\w-]+\s*,?/gi, '')
                                     .replace(/#\d+\s*,?/g, '')
                                     .replace(/,+,/g, ',') 
                                     .trim();
                    if (clean !== query && clean.length > 10) nextQuery = clean;
                    else attempt++; 
                }
                
                if (attempt === 2 || (!nextQuery && attempt === 2)) {
                    const parts = originalQuery.split(',');
                    if (parts.length >= 2) {
                        const lastTwo = parts.slice(-2).join(',').trim();
                        if (lastTwo !== query && lastTwo.length > 3) {
                            nextQuery = lastTwo;
                            if (!nextQuery.toLowerCase().includes('philippines')) nextQuery += ', Philippines';
                        }
                    }
                }

                if (nextQuery) {
                    console.log(`Beautician Location Fallback (${attempt + 1}):`, nextQuery);
                    resolveBeauticianLocation(nextQuery, attempt + 1, originalQuery);
                } else {
                    console.warn('Could not locate beautician address:', originalQuery);
                }
            }
        })
        .catch(err => console.error('Beautician location geocode error:', err));
}

function toggleBeauticianLocation() {
    const bLoc = window.currentBeauticianLocation;
    
    // Check if location is available
    if (!bLoc || bLoc.lat === null || bLoc.lng === null) {
        alert("Beautician location is not available or still loading.");
        return;
    }

    window.forceShowBeautician = !window.forceShowBeautician;
    
    // Update button visual state
    const btn = document.querySelector('button[onclick="toggleBeauticianLocation()"]');
    if (btn) {
        if (window.forceShowBeautician) {
            btn.innerHTML = '<i class="bi bi-eye-slash-fill me-1"></i> Hide Beautician Location';
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-primary');
        } else {
            btn.innerHTML = '<i class="bi bi-geo-alt-fill me-1"></i> Show Beautician Location';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-primary');
        }
    }
    
    updateBookingMap();
}

function updateBookingMap() {
    const mapContainer = document.getElementById('bookingMap');
    if (!mapContainer) return;

    const bLoc = window.currentBeauticianLocation;
    const cLoc = window.currentClientLocation;

    // Init map if needed
    if (!window.bookingMap) {
        window.bookingMap = L.map('bookingMap').setView([14.5995, 120.9842], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(window.bookingMap);
    }
    
    // Always refresh size to ensure proper rendering
    window.bookingMap.invalidateSize();

    const isValidLoc = (loc) => loc && loc.lat !== null && loc.lng !== null && !isNaN(loc.lat) && !isNaN(loc.lng);
    const bValid = isValidLoc(bLoc);
    const cValid = isValidLoc(cLoc);

    // Clean up existing routing control
    if (window.routingControl) {
        window.bookingMap.removeControl(window.routingControl);
        window.routingControl = null;
    }

    // Determine what to show
    // Show beautician marker if:
    // 1. We know where beautician is (bValid) AND
    // 2. (We are routing (cValid) OR User explicitly asked to see it (forceShowBeautician))
    const showBeauticianMarker = bValid && (cValid || window.forceShowBeautician);
    const showRoute = bValid && cValid;

    // Beautician Marker (Pink)
    if (showBeauticianMarker) {
        if (window.bookingMapMarkers.beautician) {
            window.bookingMapMarkers.beautician.setLatLng([bLoc.lat, bLoc.lng]).addTo(window.bookingMap);
        } else {
            const pinkIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color:#D63384; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px black;'></div>",
                iconSize: [12, 12],
                iconAnchor: [6, 6]
            });
            window.bookingMapMarkers.beautician = L.marker([bLoc.lat, bLoc.lng], {icon: pinkIcon}).addTo(window.bookingMap).bindPopup("Beautician");
        }
    } else {
        if (window.bookingMapMarkers.beautician) {
            window.bookingMapMarkers.beautician.remove();
        }
    }

    // Client Marker (Blue/Teal)
    if (cValid) {
        if (window.bookingMapMarkers.client) {
            window.bookingMapMarkers.client.setLatLng([cLoc.lat, cLoc.lng]).addTo(window.bookingMap);
        } else {
            const blueIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color:#20c997; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px black;'></div>",
                iconSize: [12, 12],
                iconAnchor: [6, 6]
            });
            window.bookingMapMarkers.client = L.marker([cLoc.lat, cLoc.lng], {icon: blueIcon}).addTo(window.bookingMap).bindPopup("You");
        }
    } else {
         if (window.bookingMapMarkers.client) {
            window.bookingMapMarkers.client.remove();
        }
    }

    // Routing Logic (OSRM)
    if (showRoute) {
        try {
            window.routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(bLoc.lat, bLoc.lng),
                    L.latLng(cLoc.lat, cLoc.lng)
                ],
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    profile: 'driving'
                }),
                lineOptions: {
                    styles: [{color: '#0d6efd', opacity: 0.8, weight: 6}]
                },
                createMarker: function() { return null; }, // Use our custom markers
                addWaypoints: false,
                draggableWaypoints: false,
                fitSelectedRoutes: true,
                showAlternatives: false,
                containerClassName: 'd-none' 
            }).addTo(window.bookingMap);

            window.routingControl.on('routesfound', function(e) {
                const routes = e.routes;
                if (routes && routes.length > 0) {
                    const summary = routes[0].summary;
                    // summary.totalDistance is in meters
                    const distanceKm = summary.totalDistance / 1000;
                    
                    // Update UI with accurate distance
                    updateFeeUI(distanceKm);
                }
            });
            
            window.routingControl.on('routingerror', function(e) {
                console.error('Routing error:', e);
                updateFeeUI(0);
            });

        } catch (e) {
            console.error('Routing control initialization error:', e);
        }
    } else {
        // If not routing, determine center
        if (showBeauticianMarker && !cValid) {
             window.bookingMap.setView([bLoc.lat, bLoc.lng], 13);
        } else if (cValid) {
             window.bookingMap.setView([cLoc.lat, cLoc.lng], 13);
        }
        
        // Reset Fee
        updateFeeUI(0);
    }
}

function updateFeeUI(distanceKm) {
    const fee = Math.ceil(distanceKm * 10); // 10 pesos per km
    
    document.getElementById('modalTravelDistance').textContent = distanceKm.toFixed(1);
    document.getElementById('modalTravelFee').textContent = '₱' + fee.toFixed(2);
    document.getElementById('inputTravelFee').value = fee;
    document.getElementById('inputDistance').value = distanceKm.toFixed(2);
    
    // Update Total Downpayment
    const servicePriceText = document.getElementById('modalServicePrice').textContent.replace('₱','').replace(',','');
    const servicePrice = parseFloat(servicePriceText) || 0;
    
    const total = servicePrice + fee;
    const downPayment = total * 0.5;
    
    document.getElementById('modalDownPayment').textContent = '₱' + downPayment.toFixed(2);
}

function calculateTravelFee(clientLat, clientLng) {
    // Store client location
    if (clientLat && clientLng) {
        window.currentClientLocation = { lat: clientLat, lng: clientLng };
    }
    // Update map (which triggers routing -> routesfound -> updateFeeUI)
    updateBookingMap();
}

document.addEventListener('DOMContentLoaded', function() {
    const locationInput = document.getElementById('bookingLocationInput');
    const suggestionsList = document.getElementById('locationSuggestions');
    let debounceTimer;

    if (locationInput && suggestionsList) {
        // Handle manual entry blur
        locationInput.addEventListener('blur', function() {
             const query = this.value;
             // If user typed something but didn't pick suggestion, and we don't have coords for it
             // (Or even if we do, re-validating is safer if they changed it)
             if (query && query.length > 3) {
                  // Only geocode if it looks like a new address (simple check: different from last geocoded?)
                  // For now, just geocode to be safe if they leave the field
                  document.getElementById('modalTravelFee').textContent = '...'; // Visual feedback
                  
                  setTimeout(() => {
                      // Check if suggestion was clicked in the meantime
                      if (window.isSuggestionClicked) {
                          window.isSuggestionClicked = false;
                          return;
                      }

                      if (!window.currentClientLocation.lat || window.currentClientLocation.lat) { // Always try
                         fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ph&limit=1`)
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.length > 0) {
                                    window.currentClientLocation = {
                                        lat: parseFloat(data[0].lat),
                                        lng: parseFloat(data[0].lon)
                                    };
                                    // Calculate (or just update map via this function)
                                    calculateTravelFee(window.currentClientLocation.lat, window.currentClientLocation.lng);
                                } else {
                                    // Reset if not found
                                    document.getElementById('modalTravelFee').textContent = '₱0.00';
                                }
                            });
                      }
                  }, 200);
             }
        });

        locationInput.addEventListener('input', function() {
            window.isSuggestionClicked = false; // Reset flag on typing
            const query = this.value;
            if (query.length < 3) {
                suggestionsList.style.display = 'none';
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                // Use Nominatim API for address suggestions
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ph&limit=5`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsList.innerHTML = '';
                        if (data.length > 0) {
                            suggestionsList.style.display = 'block';
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'suggestion-item';
                                div.textContent = item.display_name;
                                div.onclick = function() {
                                    window.isSuggestionClicked = true; // Prevent blur geocoding
                                    locationInput.value = item.display_name;
                                    suggestionsList.style.display = 'none';
                                    
                                    // Visual feedback
                                    document.getElementById('modalTravelFee').textContent = '...';

                                    // Trigger Calculation
                                    calculateTravelFee(parseFloat(item.lat), parseFloat(item.lon));
                                };
                                suggestionsList.appendChild(div);
                            });
                        } else {
                            suggestionsList.style.display = 'none';
                        }
                    })
                    .catch(err => console.error('Autocomplete Error:', err));
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target !== locationInput && !suggestionsList.contains(e.target)) {
                suggestionsList.style.display = 'none';
            }
        });
    }
});
</script>

<form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display:none;">
    @csrf
</form>

<script>
    // Profile Image Preview
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photoInput');
        if (photoInput) {
            photoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.querySelector('#editProfileModal img.rounded-circle');
                        if (img) img.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
</body>
</html>

