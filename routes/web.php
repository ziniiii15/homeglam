<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BeauticianController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\BeauticianAuthController;
use App\Http\Middleware\BeauticianAuth; // <- added
use App\Http\Middleware\CheckBeauticianBan; // <- added
use Illuminate\Support\Facades\Redirect;


use App\Http\Controllers\BanAppealController; // <- added
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

// DO NOT TOUCH AND CHANGE EVERYTHING IN THIS CODE!!!
// --------------------
// HOME / DEFAULT
// --------------------
Route::get('/', fn() => view('welcome'))->name('welcome');

// --------------------
// RESOURCE ROUTES
// --------------------
Route::resources([
    'clients' => ClientController::class,
    'beauticians' => BeauticianController::class,
    'services' => ServiceController::class,
    'bookings' => BookingController::class,
    'reviews' => ReviewController::class,
    'availabilities' => AvailabilityController::class,
    'galleries' => GalleryController::class,
    'discounts' => DiscountController::class,
    'admins' => AdminController::class,
]);

// --------------------
// AUTH REDIRECT
// --------------------
Route::get('/login', function () {
    return redirect()->route('welcome');
})->name('login');

// --------------------
// ADMIN AUTH
// --------------------
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Admin Reports
        Route::get('reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::patch('reports/{report}/status', [ReportController::class, 'updateStatus'])->name('admin.reports.update_status');

        // Admin Appeals
        Route::get('appeals', [AdminController::class, 'indexAppeals'])->name('admin.appeals.index');
        Route::post('appeals/{appeal}/resolve', [AdminController::class, 'resolveAppeal'])->name('admin.appeals.resolve');

        // Verify Beautician
        Route::post('beauticians/{beautician}/verify', [AdminController::class, 'verifyBeautician'])->name('admin.beauticians.verify');
        Route::post('beauticians/{beautician}/test-subscription', [AdminController::class, 'testBeauticianSubscription'])->name('admin.beauticians.test_subscription');

        // Category Management
        Route::post('categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
        Route::delete('categories/{category}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

        // Ban Beauticians
        Route::post('beauticians/{beautician}/ban', [AdminController::class, 'banBeautician'])->name('admin.beauticians.ban');
        Route::post('beauticians/{beautician}/unban', [AdminController::class, 'unbanBeautician'])->name('admin.beauticians.unban');

        Route::post('subscription-qr', [AdminController::class, 'updateSubscriptionQr'])->name('admin.subscription_qr.update');
    });
});

// --------------------
// CLIENT AUTH
// --------------------
Route::prefix('client')->group(function () {
    Route::get('login', [ClientAuthController::class, 'showLoginForm'])->name('client.login');
    Route::post('login', [ClientAuthController::class, 'login']);
    Route::get('register', [ClientAuthController::class, 'showRegisterForm'])->name('client.register');
    Route::post('register', [ClientAuthController::class, 'register']);
    Route::post('logout', [ClientAuthController::class, 'logout'])->name('client.logout');

    Route::get('password/forgot', [ClientAuthController::class, 'showForgotPasswordForm'])->name('client.password.forgot');
    Route::post('password/forgot', [ClientAuthController::class, 'handleForgotPassword'])->name('client.password.forgot.submit');
    Route::get('password/otp', [ClientAuthController::class, 'showOtpForm'])->name('client.password.otp.form');
    Route::post('password/otp/send', [ClientAuthController::class, 'sendOtp'])->name('client.password.otp.send');
    Route::post('password/otp/verify', [ClientAuthController::class, 'verifyOtp'])->name('client.password.otp.verify');
    Route::get('password/reset', [ClientAuthController::class, 'showResetForm'])->name('client.password.reset.form');
    Route::post('password/reset', [ClientAuthController::class, 'resetPassword'])->name('client.password.reset');

    Route::middleware(['auth:client'])->group(function () {
        Route::get('dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');
        Route::get('transactions', [ClientController::class, 'transactions'])->name('client.transactions');

        // Client booking actions
        Route::post('bookings/store', [ClientController::class, 'store_booking'])->name('client.bookings.store');

        // Payment Callbacks
        Route::get('payment/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
        Route::get('payment/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');

        // Client Reports
        Route::post('reports', [ReportController::class, 'store'])->name('client.reports.store');

        Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('client.cancel_booking');
// --- Add this inside the client middleware group ---
Route::patch('profile/update', [ClientController::class, 'updateProfile'])->name('client.profile.update');
Route::post('location/update', [ClientController::class, 'updateLocation'])->name('client.location.update');

        // Notifications
        Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('client.notifications');
        Route::post('notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('client.notifications.read');
       
        // Filter by category (more specific)
Route::get('services/category/{category}/beauticians', [ClientController::class, 'getBeauticiansByCategory'])
    ->name('client.services.beauticians');

// Filter by service (generic)
Route::get('services/{service}', [ClientController::class, 'getBeauticiansByService'])
    ->name('client.services.filter');

    });
});

// --------------------
// BEAUTICIAN AUTH
// --------------------
Route::prefix('beautician')->group(function () {
    Route::get('login', [BeauticianAuthController::class, 'showLoginForm'])->name('beautician.login');
    Route::post('login', [BeauticianAuthController::class, 'login']);
    Route::get('register', [BeauticianAuthController::class, 'showRegisterForm'])->name('beautician.register');
    Route::post('register', [BeauticianAuthController::class, 'register']);
    Route::post('logout', [BeauticianAuthController::class, 'logout'])->name('beautician.logout');
    Route::get('password/forgot', [BeauticianAuthController::class, 'showForgotPasswordForm'])->name('beautician.password.forgot');
    Route::post('password/forgot', [BeauticianAuthController::class, 'handleForgotPassword'])->name('beautician.password.forgot.submit');
    Route::get('password/otp', [BeauticianAuthController::class, 'showOtpForm'])->name('beautician.password.otp.form');
    Route::post('password/otp/send', [BeauticianAuthController::class, 'sendOtp'])->name('beautician.password.otp.send');
    Route::post('password/otp/verify', [BeauticianAuthController::class, 'verifyOtp'])->name('beautician.password.otp.verify');
    Route::get('password/reset', [BeauticianAuthController::class, 'showResetForm'])->name('beautician.password.reset.form');
    Route::post('password/reset', [BeauticianAuthController::class, 'resetPassword'])->name('beautician.password.reset');

    Route::middleware([BeauticianAuth::class])->group(function () {
        
        // Pending Verification
        Route::get('pending', function () {
            if (auth()->guard('beautician')->user()->is_verified) {
                return redirect()->route('beautician.dashboard');
            }
            return view('auth.pending');
        })->name('beautician.pending');

        Route::get('subscription/renew', function () {
            $beautician = auth()->guard('beautician')->user();
            if (
                $beautician->is_verified &&
                $beautician->subscription_expires_at &&
                $beautician->subscription_expires_at->isFuture()
            ) {
                return redirect()->route('beautician.dashboard');
            }
            return view('auth.subscription-renew');
        })->name('beautician.subscription_renew');

        // Banned Page
        Route::get('banned', function () {
            return view('beautician.banned');
        })->name('beautician.banned');

        // Appeal Ban
        Route::post('banned/appeal', [BanAppealController::class, 'store'])->name('beautician.appeal');

        Route::post('subscription-proof', [BeauticianController::class, 'uploadSubscriptionProof'])->name('beautician.subscription_proof');

        Route::middleware([CheckBeauticianBan::class])->group(function () {
            // Dashboard — FIXED to use BeauticianController
            Route::get('dashboard', [BeauticianController::class, 'dashboard'])->name('beautician.dashboard');
            Route::get('transactions', [BeauticianController::class, 'transactions'])->name('beautician.transactions');

            // Services
            Route::get('services', [BeauticianController::class, 'services'])->name('beautician.services');
            Route::post('services/store', [BeauticianController::class, 'storeService'])->name('beautician.services.store');
            Route::get('services/{service}/edit', [BeauticianController::class, 'editService'])->name('beautician.edit_service');
            Route::put('services/{service}', [BeauticianController::class, 'updateService'])->name('beautician.update_service');
            Route::delete('services/{service}', [BeauticianController::class, 'deleteService'])->name('beautician.delete_service');

            // Update booking status
            Route::post('bookings/{booking}/notify-client', [BeauticianController::class, 'notifyClientOnTheWay'])->name('beautician.notify_client');
            Route::put('bookings/{booking}/status', [BeauticianController::class, 'updateBookingStatus'])->name('beautician.update_booking_status');
            Route::put('bookings/{booking}/discount', [BeauticianController::class, 'applyDiscount'])->name('beautician.apply_discount');

            // Profile
            Route::get('profile', [BeauticianController::class, 'editProfile'])->name('beautician.edit_profile');
            Route::put('profile', [BeauticianController::class, 'updateProfile'])->name('beautician.update_profile');
            Route::post('location/update', [BeauticianController::class, 'updateLocation'])->name('beautician.location.update');

            // Appointment Schedules
            Route::get('schedules', [BeauticianController::class, 'getSchedules'])->name('beautician.schedules.index');
            Route::post('schedules', [BeauticianController::class, 'storeSchedule'])->name('beautician.schedules.store');
            Route::post('schedules/batch', [BeauticianController::class, 'storeMultipleSchedules'])->name('beautician.schedules.store_batch');
            Route::delete('schedules/{id}', [BeauticianController::class, 'deleteSchedule'])->name('beautician.schedules.destroy');

            // Gallery
            Route::post('gallery', [BeauticianController::class, 'addGalleryImage'])->name('beautician.gallery.store');
            Route::delete('gallery/{gallery}', [BeauticianController::class, 'deleteGalleryImage'])->name('beautician.gallery.destroy');

            // Notifications
            Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('beautician.notifications');
            Route::post('notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('beautician.notifications.read');
        });
    });
});

// --------------------
// AJAX route fallback
// --------------------

Route::post('/beautician/availability', [BeauticianController::class, 'saveAvailability'])
    ->name('beautician.save_availability');


    Route::put('/beautician/booking-limit', [BeauticianController::class, 'updateBookingLimit'])
    ->name('beautician.update_booking_limit')
    ->middleware('auth:beautician');
Route::get('/view-asset/{path}', function ($path) {
    // Strip redundant prefixes
    $cleanPath = preg_replace('/^view-asset\//', '', $path);
    $cleanPath = preg_replace('/^storage\//', '', $cleanPath);
    
    // Check multiple potential locations in storage/
    $locations = [
        base_path('storage/' . $cleanPath),
        base_path('storage/galleries/' . preg_replace('/^galleries\//', '', $cleanPath)),
        base_path('storage/review_images/' . preg_replace('/^review_images\//', '', $cleanPath)),
        base_path('storage/uploads/' . preg_replace('/^uploads\//', '', $cleanPath)),
    ];

    $filePath = null;
    foreach ($locations as $loc) {
        if (File::exists($loc) && !File::isDirectory($loc)) {
            $filePath = $loc;
            break;
        }
    }

    if (!$filePath) {
        abort(404);
    }

    $file = File::get($filePath);
    $type = File::mimeType($filePath);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    $response->header("Cache-Control", "public, max-age=86400");

    return $response;
})->where('path', '.*');

Route::get('/view-upload/{path}', function ($path) {
    $path = preg_replace('/^view-upload\//', '', $path);
    $path = preg_replace('/^uploads\//', '', $path);
    $path = base_path('storage/uploads/' . $path);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    $response->header("Cache-Control", "public, max-age=86400");

    return $response;
})->where('path', '.*');

Route::get('/uploads/{path}', function ($path) {
    return redirect('/view-upload/' . $path);
})->where('path', '.*');

Route::get('/galleries/{path}', function ($path) {
    return redirect('/view-asset/galleries/' . $path);
})->where('path', '.*');

Route::get('/review_images/{path}', function ($path) {
    return redirect('/view-asset/review_images/' . $path);
})->where('path', '.*');

Route::post('/beautician/time-slots', [BeauticianController::class, 'storeTimeSlot'])
    ->name('beautician.time-slots.store');

    Route::get('/beautician/time-slots', [BeauticianController::class, 'getAvailableSlots'])
     ->name('beautician.time-slots');

     Route::get('/available-slots', [BeauticianController::class, 'getAvailableSlots']);
