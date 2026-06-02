<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'josephbangoura0204@gmail.com')->first();
if ($user) {
    $user->syncRoles(['admin']);
    echo "User Joseph Bangoura (client) promoted to admin.\n";
} else {
    $user = User::create([
        'name' => 'Joseph Bangoura',
        'email' => 'josephbangoura0204@gmail.com',
        'password' => Hash::make('password'), // A changer à la première connexion
    ]);
    $user->assignRole('admin');
    echo "User Joseph Bangoura created as admin.\n";
}
