<h2>Nouvelle commande reçue</h2>

<p>Le client <strong>{{ $ticket->user->name }}</strong> ({{ $ticket->user->email }}) vient de déposer la commande #{{ $ticket->id }}.</p>

<h3>Détail :</h3>
<ul>
    @foreach ($ticket->items as $item)
        <li>{{ $item->service->libelle }} — {{ $item->quantite }} × {{ $item->prix_unitaire }} FCFA</li>
    @endforeach
</ul>

<p><strong>Montant total : {{ $ticket->montant_total }} FCFA</strong></p>