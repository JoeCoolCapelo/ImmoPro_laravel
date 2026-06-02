<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "biens.vues: " . (Schema::hasColumn('biens', 'vues') ? "Exists" : "Missing") . PHP_EOL;
echo "visites.feedback_agent: " . (Schema::hasColumn('visites', 'feedback_agent') ? "Exists" : "Missing") . PHP_EOL;
