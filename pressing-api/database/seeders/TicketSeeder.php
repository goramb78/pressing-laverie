<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $client = User::firstOrCreate(
            ['email' => 'client.demo@pressing.com'],
            [
                'name' => 'Client Démo',
                'password' => Hash::make('demo1234'),
                'role' => 'client',
            ]
        );

        $lavage = Service::where('libelle', 'Lavage')->first();
        $repassage = Service::where('libelle', 'Repassage')->first();

        if (! $lavage || ! $repassage) {
            return;
        }

        // Ticket 1 : reçu, non payé
        $ticket1 = Ticket::create([
            'user_id' => $client->id,
            'statut' => 'recu',
            'date_recu' => now()->subDays(2),
        ]);
        $ticket1->items()->create([
            'service_id' => $lavage->id,
            'quantite' => 3,
            'prix_unitaire' => $lavage->prix_unitaire,
            'sous_total' => $lavage->prix_unitaire * 3,
        ]);
        $ticket1->update(['montant_total' => $lavage->prix_unitaire * 3]);

        // Ticket 2 : prêt, non payé
        $ticket2 = Ticket::create([
            'user_id' => $client->id,
            'statut' => 'pret',
            'date_recu' => now()->subDays(4),
            'date_en_traitement' => now()->subDays(3),
            'date_pret' => now()->subDay(),
        ]);
        $ticket2->items()->create([
            'service_id' => $repassage->id,
            'quantite' => 5,
            'prix_unitaire' => $repassage->prix_unitaire,
            'sous_total' => $repassage->prix_unitaire * 5,
        ]);
        $ticket2->update(['montant_total' => $repassage->prix_unitaire * 5]);

        // Ticket 3 : récupéré, payé
        $ticket3 = Ticket::create([
            'user_id' => $client->id,
            'statut' => 'recupere',
            'date_recu' => now()->subDays(6),
            'date_en_traitement' => now()->subDays(5),
            'date_pret' => now()->subDays(4),
            'date_recupere' => now()->subDays(3),
        ]);
        $ticket3->items()->create([
            'service_id' => $lavage->id,
            'quantite' => 2,
            'prix_unitaire' => $lavage->prix_unitaire,
            'sous_total' => $lavage->prix_unitaire * 2,
        ]);
        $montant = $lavage->prix_unitaire * 2;
        $ticket3->update(['montant_total' => $montant]);
        $ticket3->payment()->create([
            'montant' => $montant,
            'date_paiement' => now()->subDays(3),
        ]);
    }
}