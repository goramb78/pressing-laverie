<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'gestionnaire@pressing.com'],
            [
                'name' => 'Gestionnaire Pressing',
                'password' => Hash::make('gestionnaire2026'),
                'role' => 'gestionnaire',
            ]
        );
    }
}