<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>Reçu de commande #{{ $ticket->id }}</h1>

    <p><strong>Client :</strong> {{ $ticket->user->name }}</p>
    <p><strong>Date de dépôt :</strong> {{ $ticket->date_recu->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ticket->items as $item)
                <tr>
                    <td>{{ $item->service->libelle }}</td>
                    <td>{{ $item->quantite }}</td>
                    <td>{{ $item->prix_unitaire }} FCFA</td>
                    <td>{{ $item->sous_total }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 20px;"><strong>Montant total : {{ $ticket->montant_total }} FCFA</strong></p>

    <p>Merci de votre confiance — Le Pressing LIC</p>
</body>
</html>