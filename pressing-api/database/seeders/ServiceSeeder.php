<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['libelle' => 'Lavage', 'description' => 'Lavage au kilo', 'prix_unitaire' => 1500, 'actif' => true],
            ['libelle' => 'Repassage', 'description' => 'Repassage à la pièce', 'prix_unitaire' => 500, 'actif' => true],
            ['libelle' => 'Nettoyage à sec', 'description' => 'Pour vêtements délicats', 'prix_unitaire' => 3000, 'actif' => true],
            ['libelle' => 'Détachage', 'description' => 'Traitement de taches spécifiques', 'prix_unitaire' => 1000, 'actif' => true],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['libelle' => $service['libelle']], $service);
        }
    }
}