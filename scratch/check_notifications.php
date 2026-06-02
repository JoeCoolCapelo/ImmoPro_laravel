<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Visite;
use Illuminate\Support\Facades\DB;

$lastNotification = DB::table('notifications')->latest()->first();
if ($lastNotification) {
    echo "Last Notification: " . $lastNotification->type . " created at " . $lastNotification->created_at . "\n";
    echo "Data: " . $lastNotification->data . "\n";
} else {
    echo "No notifications found in database.\n";
}

$lastVisite = Visite::latest()->first();
if ($lastVisite) {
    echo "Last Visite ID: " . $lastVisite->id . "\n";
    echo "Interested: " . ($lastVisite->interested === null ? 'NULL' : ($lastVisite->interested ? 'TRUE' : 'FALSE')) . "\n";
}
