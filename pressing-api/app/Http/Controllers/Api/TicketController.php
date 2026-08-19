<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewTicketNotificationMail;
use App\Mail\TicketConfirmationMail;
use App\Mail\TicketReadyMail;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Ticket::with('items.service', 'user', 'payment');

        if ($user->role !== 'gestionnaire') {
            $query->where('user_id', $user->id);
        }

        return response()->json($query->latest()->get());
    }

    public function show(Request $request, Ticket $ticket)
    {
        $user = $request->user();

        if ($user->role !== 'gestionnaire' && $ticket->user_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        return response()->json($ticket->load('items.service', 'user', 'payment'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantite' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'statut' => 'recu',
            'date_recu' => now(),
        ]);

        $montantTotal = 0;

        foreach ($request->items as $item) {
            $service = Service::findOrFail($item['service_id']);
            $sousTotal = $service->prix_unitaire * $item['quantite'];

            $ticket->items()->create([
                'service_id' => $service->id,
                'quantite' => $item['quantite'],
                'prix_unitaire' => $service->prix_unitaire,
                'sous_total' => $sousTotal,
            ]);

            $montantTotal += $sousTotal;
        }

        $ticket->update(['montant_total' => $montantTotal]);
        $ticket->load('items.service', 'user');

        Mail::to($ticket->user->email)->send(new TicketConfirmationMail($ticket));

        $gestionnaires = User::where('role', 'gestionnaire')->get();
        foreach ($gestionnaires as $gestionnaire) {
            Mail::to($gestionnaire->email)->send(new NewTicketNotificationMail($ticket));
        }

        return response()->json($ticket, 201);
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:en_traitement,pret,recupere',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $nouveauStatut = $request->statut;

        if ($nouveauStatut === 'recupere' && ! $ticket->payment) {
            return response()->json(['message' => 'Le ticket doit être payé avant d\'être marqué comme récupéré.'], 422);
        }

        $ticket->statut = $nouveauStatut;

        match ($nouveauStatut) {
            'en_traitement' => $ticket->date_en_traitement = now(),
            'pret' => $ticket->date_pret = now(),
            'recupere' => $ticket->date_recupere = now(),
        };

        $ticket->save();
        $ticket->load('items.service', 'user', 'payment');

        if ($nouveauStatut === 'pret') {
            Mail::to($ticket->user->email)->send(new TicketReadyMail($ticket));
        }

        return response()->json($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        if (in_array($ticket->statut, ['pret', 'recupere'])) {
            return response()->json(['message' => 'Ce ticket ne peut plus être annulé.'], 422);
        }

        $ticket->delete();

        return response()->json(['message' => 'Ticket annulé.']);
    }
}