<h2>Bonjour {{ $ticket->user->name }},</h2>

<p>Votre commande #{{ $ticket->id }} a bien été enregistrée.</p>

<h3>Détail de votre commande :</h3>
<ul>
    @foreach ($ticket->items as $item)
        <li>{{ $item->service->libelle }} — {{ $item->quantite }} × {{ $item->prix_unitaire }} FCFA = {{ $item->sous_total }} FCFA</li>
    @endforeach
</ul>

<p><strong>Montant total : {{ $ticket->montant_total }} FCFA</strong></p>

<p>Nous vous informerons dès que votre commande sera prête.</p>

<p>Merci de votre confiance,<br>Le Pressing LIC</p>