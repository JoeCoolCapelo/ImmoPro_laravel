<?php
use App\Models\Transaction;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$transactions = Transaction::with(['bien', 'client', 'agent'])->latest()->get();
echo "Count: " . $transactions->count() . "\n";
foreach ($transactions as $t) {
    echo "ID: " . $t->id . " | Bien: " . ($t->bien->titre ?? 'N/A') . " | Client: " . ($t->client->name ?? 'N/A') . " | Statut: " . $t->statut . "\n";
}
