<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$migrationsPath = base_path('database/migrations');
$files = scandir($migrationsPath);

// Get already run migrations
$ran = DB::table('migrations')->pluck('migration')->toArray();

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    $migrationName = str_replace('.php', '', $file);
    
    if (in_array($migrationName, $ran)) continue;
    
    $content = file_get_contents($migrationsPath . '/' . $file);
    
    // Extract table name from Schema::create('table_name', ...
    if (preg_match("/Schema::create\('([^']+)'/", $content, $matches)) {
        $tableName = $matches[1];
        
        if (Schema::hasTable($tableName)) {
            echo "Table '$tableName' already exists for migration '$migrationName'. Marking as RAN." . PHP_EOL;
            DB::table('migrations')->insert([
                'migration' => $migrationName,
                'batch' => 2, // Use a new batch number
            ]);
        } else {
            echo "Migration '$migrationName' creates table '$tableName' which DOES NOT exist. Leaving it for artisan migrate." . PHP_EOL;
        }
    } else {
        echo "Migration '$migrationName' is not a simple create migration. Leaving it." . PHP_EOL;
    }
}
