<?php
$dir = __DIR__.'/../database/migrations';
$files = scandir($dir);

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    $path = $dir . '/' . $file;
    $content = file_get_contents($path);
    
    // Remove the extra braces added by the previous script
    // My previous script added "if (!Schema::hasTable... {" and "});\n        }"
    
    // First, let's restore the files to a clean state if possible.
    // Actually, I can just find the "if (!Schema::hasTable... {" and remove it and the matching "}"
    
    $cleanContent = preg_replace("/if \(!Schema::hasTable\('[^']+'\)\) \{\n\s+Schema::create/", "Schema::create", $content);
    $cleanContent = preg_replace("/\}\);\n\s+\}/", "});", $cleanContent);
    
    if ($cleanContent !== $content) {
        file_put_contents($path, $cleanContent);
        echo "Cleaned $file" . PHP_EOL;
    }
}
