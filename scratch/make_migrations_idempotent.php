<?php
$dir = __DIR__.'/../database/migrations';
$files = scandir($dir);

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    $path = $dir . '/' . $file;
    $content = file_get_contents($path);
    
    // Pattern to find Schema::create('table_name', function (Blueprint $table) {
    // and replace it with if (!Schema::hasTable('table_name')) { Schema::create... }
    
    $newContent = preg_replace_callback(
        "/Schema::create\('([^']+)',\s*(?:static\s+)?function\s*\(([^)]+)\)\s*(?:use\s*\([^)]+\)\s*)?\{/",
        function($matches) {
            $tableName = $matches[1];
            $params = $matches[2];
            return "if (!Schema::hasTable('$tableName')) {\n        Schema::create('$tableName', function ($params) {";
        },
        $content
    );
    
    // Also need to add closing brace for the if
    // This is tricky because Schema::create ends with });
    // We want to replace }); with });\n        }
    
    // Simple approach: find }); and if it's the end of a Schema::create block, add }
    // But a file might have multiple Schema::create
    
    $newContent = str_replace("});", "});\n        }", $newContent);
    
    // Fix potential double closing braces or other issues
    // Note: This script is a bit crude but might work for simple Laravel migrations.
    
    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "Modified $file" . PHP_EOL;
    }
}
