<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\TicketItem;
use Carbon\Carbon;

class StatsController extends Controller
{
    // Statistiques du jour : tickets créés, tickets clôturés, recette
    public function daily()
    {
        $today = Carbon::today();

        $ticketsCreated = Ticket::whereDate('date_recu', $today)->count();
        $ticketsCompleted = Ticket::whereDate('date_recupere', $today)->count();
        $revenue = Payment::whereDate('date_paiement', $today)->sum('montant');

        return response()->json([
            'tickets_created_today' => $ticketsCreated,
            'tickets_completed_today' => $ticketsCompleted,
            'revenue_today' => $revenue,
        ]);
    }

    // Nombre de tickets par mois
    public function ticketsPerMonth()
    {
        $data = Ticket::selectRaw("DATE_FORMAT(date_recu, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json($data);
    }

    // Répartition du chiffre d'affaires par service, par mois
    public function revenuePerService()
    {
        $data = TicketItem::join('services', 'ticket_items.service_id', '=', 'services.id')
            ->join('tickets', 'ticket_items.ticket_id', '=', 'tickets.id')
            ->selectRaw("services.libelle as service, DATE_FORMAT(tickets.date_recu, '%Y-%m') as month, SUM(ticket_items.sous_total) as total")
            ->groupBy('services.libelle', 'month')
            ->orderBy('month')
            ->get();

        return response()->json($data);
    }
}