<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Client;
use Illuminate\Support\Facades\Hash;

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all clients
$clients = Client::all();

foreach ($clients as $client) {
    // Only hash if it doesn't look hashed (length < 60)
    if (strlen($client->password) < 60) {
        $client->password = Hash::make($client->password);
        $client->save();
        echo "Hashed password for: " . $client->email . PHP_EOL;
    }
}

echo "✅ All plain-text client passwords have been hashed." . PHP_EOL;
