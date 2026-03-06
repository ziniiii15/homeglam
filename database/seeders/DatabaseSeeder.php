<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Beautician;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Availability;
use App\Models\Gallery;
use App\Models\Discount;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clients
        $client1 = Client::create([
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'phone' => '09123456789',
            'address' => '123 Main Street',
            'password' => Hash::make('password123')
        ]);

        $client2 = Client::create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'phone' => '09987654321',
            'address' => '456 Elm Street',
            'password' => Hash::make('password123')
        ]);

        // Beauticians
        $beautician1 = Beautician::create([
            'name' => 'Samantha Lee',
            'email' => 'samantha@example.com',
            'phone' => '09112223344',
            'address' => '789 Maple Ave',
            'experience' => '5 years',
            'base_location' => 'Quezon City',
            'booking_fee' => 150
        ]);

        $beautician2 = Beautician::create([
            'name' => 'Jessica Tan',
            'email' => 'jessica@example.com',
            'phone' => '09223334455',
            'address' => '321 Oak St',
            'experience' => '3 years',
            'base_location' => 'Makati',
            'booking_fee' => 200
        ]);

        // Services
        $service1 = Service::create([
            'service_name' => 'Facial',
            'description' => 'Deep cleansing facial',
            'base_price' => 500
        ]);

        $service2 = Service::create([
            'service_name' => 'Manicure',
            'description' => 'Nail care and polish',
            'base_price' => 300
        ]);

        // Bookings (linked)
        $booking1 = Booking::create([
            'client_id' => $client1->id,
            'beautician_id' => $beautician1->id,
            'service_id' => $service1->id,
            'booking_date' => '2025-09-01',
            'booking_time' => '10:00',
            'location' => $client1->address,
            'total_cost' => 650,
            'status' => 'upcoming',
            'client_notes' => 'Please be gentle.'
        ]);

        $booking2 = Booking::create([
            'client_id' => $client2->id,
            'beautician_id' => $beautician2->id,
            'service_id' => $service2->id,
            'booking_date' => '2025-09-02',
            'booking_time' => '14:00',
            'location' => $client2->address,
            'total_cost' => 500,
            'status' => 'upcoming',
            'client_notes' => 'No nail polish with glitter.'
        ]);

        // Reviews (linked)
        Review::create([
            'booking_id' => $booking1->id,
            'client_id' => $client1->id,
            'beautician_id' => $beautician1->id,
            'rating' => 5,
            'comment' => 'Excellent service!',
            'date' => '2025-09-01'
        ]);

        Review::create([
            'booking_id' => $booking2->id,
            'client_id' => $client2->id,
            'beautician_id' => $beautician2->id,
            'rating' => 4,
            'comment' => 'Good, but arrived late.',
            'date' => '2025-09-02'
        ]);

        // Availabilities (linked)
        Availability::create([
            'beautician_id' => $beautician1->id,
            'day_of_week' => 'Monday',
            'start_time' => '09:00',
            'end_time' => '17:00'
        ]);

        Availability::create([
            'beautician_id' => $beautician2->id,
            'day_of_week' => 'Tuesday',
            'start_time' => '10:00',
            'end_time' => '18:00'
        ]);

        // Galleries (linked)
        Gallery::create([
            'beautician_id' => $beautician1->id,
            'image_url' => 'https://via.placeholder.com/150',
            'description' => 'Facial treatment setup'
        ]);

        Gallery::create([
            'beautician_id' => $beautician2->id,
            'image_url' => 'https://via.placeholder.com/150',
            'description' => 'Manicure station'
        ]);

        // Discounts (linked explicitly)
        Discount::create([
            'code' => 'WELCOME10',
            'discount_type' => 'percentage',
            'value' => 10,
            'valid_from' => '2025-08-25',
            'valid_to' => '2025-12-31',
            'applicable_to' => 'service_id:' . $service1->id
        ]);

        Discount::create([
            'code' => 'BEAUTY50',
            'discount_type' => 'amount',
            'value' => 50,
            'valid_from' => '2025-08-25',
            'valid_to' => '2025-12-31',
            'applicable_to' => 'beautician_id:' . $beautician2->id
        ]);

        // Admins
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'role' => 'super'
        ]);

        Admin::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'role' => 'manager'
        ]);
    }
}
