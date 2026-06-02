<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$migration = '2026_05_07_162248_add_feedback_and_views_fields';
DB::table('migrations')->where('migration', $migration)->delete();
echo "Unmarked $migration" . PHP_EOL;

// Now run migrate
\Illuminate\Support\Facades\Artisan::call('migrate');
echo "Migrate output: " . \Illuminate\Support\Facades\Artisan::output() . PHP_EOL;
