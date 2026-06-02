<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin Immo',
            'email' => 'admin@immopro.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Agent
        $agent = User::create([
            'name' => 'Agent Jean',
            'email' => 'agent@immopro.com',
            'password' => Hash::make('password'),
        ]);
        $agent->assignRole('agent');

        // Owner
        $owner = User::create([
            'name' => 'Propriétaire Paul',
            'email' => 'owner@immopro.com',
            'password' => Hash::make('password'),
        ]);
        $owner->assignRole('proprietaire');

        // Client
        $client = User::create([
            'name' => 'Client Claire',
            'email' => 'client@immopro.com',
            'password' => Hash::make('password'),
        ]);
        $client->assignRole('client');
    }
}
