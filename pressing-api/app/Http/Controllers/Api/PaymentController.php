<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    // Enregistrer un paiement en espèces (au dépôt ou au retrait), gestionnaire uniquement
    public function store(Request $request, Ticket $ticket)
    {
        if ($ticket->payment) {
            return response()->json(['message' => 'Ce ticket a déjà été payé.'], 422);
        }

        $validator = Validator::make($request->all(), [
            'montant' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = $ticket->payment()->create([
            'montant' => $request->montant,
            'date_paiement' => now(),
        ]);

        return response()->json($payment, 201);
    }
}