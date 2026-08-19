<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'description',
        'prix_unitaire',
        'actif',
        'archived_at',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'prix_unitaire' => 'decimal:2',
        'archived_at' => 'datetime',
    ];

    public function ticketItems(): HasMany
    {
        return $this->hasMany(TicketItem::class);
    }
}