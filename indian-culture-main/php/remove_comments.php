<?php
echo "<html><head><title>Remove Comments</title>
<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
    h1 { color: #333; }
    .success { color: green; }
    .info { color: blue; }
    pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow: auto; }
</style>
</head><body>";

echo "<h1>PHP Comment Remover</h1>";
echo "<p>This script removes comments from all PHP files in the current directory.</p>";

// Function to remove comments from PHP code
function removeComments($code) {
    // Remove single-line comments (//...)
    $code = preg_replace('!//.*!', '', $code);
    
    // Remove multi-line comments (/* ... */)
    $code = preg_replace('!/\*.*?\*/!s', '', $code);
    
    // Remove empty lines that might remain after comment removal
    $code = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $code);
    
    return $code;
}

// Get all PHP files in current directory and subdirectories
$phpFiles = glob('*.php');

// Process each file
$count = 0;
foreach ($phpFiles as $file) {
    // Skip the current script
    if ($file == basename(__FILE__)) {
        continue;
    }
    
    // Read file content
    $content = file_get_contents($file);
    
    // Remove comments
    $newContent = removeComments($content);
    
    // Write back to file if content changed
    if ($content !== $newContent) {
        file_put_contents($file, $newContent);
        echo "<p class='success'>✅ Comments removed from: <strong>{$file}</strong></p>";
        $count++;
    } else {
        echo "<p class='info'>ℹ️ No comments found in: <strong>{$file}</strong></p>";
    }
}

echo "<h2>Summary</h2>";
echo "<p>Processed " . count($phpFiles) - 1 . " PHP files. Modified {$count} files.</p>";

echo "<p><a href='http://localhost/indian-culture-main/indian-culture-main/'>Return to Home</a></p>";
echo "</body></html>";
?> 