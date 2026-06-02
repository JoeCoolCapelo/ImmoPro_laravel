<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$migrationsPath = base_path('database/migrations');
$files = scandir($migrationsPath);

$ran = DB::table('migrations')->pluck('migration')->toArray();

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    $migrationName = str_replace('.php', '', $file);
    if (in_array($migrationName, $ran)) continue;
    
    $content = file_get_contents($migrationsPath . '/' . $file);
    
    // Handle Schema::table migrations
    if (preg_match("/Schema::table\('([^']+)'/", $content, $matches)) {
        $tableName = $matches[1];
        
        // Extract column name from ->string('column_name') or ->add('column_name') etc.
        // This is a bit complex for a regex, but let's try a common pattern
        if (preg_match("/\\$(?:table|blueprint)->(?:[a-z]+)\('([^']+)'/", $content, $colMatches)) {
            $columnName = $colMatches[1];
            
            if (Schema::hasColumn($tableName, $columnName)) {
                echo "Column '$columnName' already exists in '$tableName' for migration '$migrationName'. Marking as RAN." . PHP_EOL;
                DB::table('migrations')->insert([
                    'migration' => $migrationName,
                    'batch' => 2,
                ]);
            } else {
                echo "Migration '$migrationName' adds column '$columnName' to '$tableName' which DOES NOT exist. Leaving it." . PHP_EOL;
            }
        } else {
            echo "Migration '$migrationName' is a table modification but column name not found. Leaving it." . PHP_EOL;
        }
    }
}
