<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'statut',
        'montant_total',
        'date_recu',
        'date_en_traitement',
        'date_pret',
        'date_recupere',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'date_recu' => 'datetime',
        'date_en_traitement' => 'datetime',
        'date_pret' => 'datetime',
        'date_recupere' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TicketItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}