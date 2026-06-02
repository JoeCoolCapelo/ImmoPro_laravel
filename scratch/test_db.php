<?php
$configs = [
    ['host' => '127.0.0.1', 'port' => '3306', 'user' => 'root', 'pass' => ''],
    ['host' => 'localhost', 'port' => '3306', 'user' => 'root', 'pass' => ''],
    ['host' => '127.0.0.1', 'port' => '3306', 'user' => 'root', 'pass' => 'root'],
    ['host' => 'localhost', 'port' => '3306', 'user' => 'root', 'pass' => 'root'],
    ['host' => '127.0.0.1', 'port' => '3308', 'user' => 'root', 'pass' => ''],
    ['host' => '127.0.0.1', 'port' => '3308', 'user' => 'root', 'pass' => 'root'],
];

foreach ($configs as $cfg) {
    $host = $cfg['host'];
    $port = $cfg['port'];
    $user = $cfg['user'];
    $pass = $cfg['pass'];
    echo "Testing $user@$host:$port (pass: '$pass')... ";
    
    try {
        $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_TIMEOUT => 2]);
        echo "SUCCESS!\n";
        // Check if database exists
        $stmt = $pdo->query("SHOW DATABASES LIKE 'agence_immo'");
        if ($stmt->fetch()) {
            echo "  - Database 'agence_immo' exists.\n";
        } else {
            echo "  - Database 'agence_immo' NOT FOUND.\n";
        }
        break; 
    } catch (\PDOException $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
}

