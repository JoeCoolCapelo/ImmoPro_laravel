<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$assignments = [
    2 => 'agent',
    3 => 'proprietaire',
    4 => 'client',
    9 => 'admin', // Probablement le compte principal de l'utilisateur
    13 => 'client',
    16 => 'client',
    17 => 'client',
    18 => 'client',
];

foreach ($assignments as $userId => $role) {
    $user = \App\Models\User::find($userId);
    if ($user) {
        $user->syncRoles([$role]);
        echo "User ID $userId ($user->name) assigned role: $role" . PHP_EOL;
    }
}
