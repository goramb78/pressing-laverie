<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('statut', ['recu', 'en_traitement', 'pret', 'recupere'])
                  ->default('recu');
            $table->decimal('montant_total', 8, 2)->default(0);
            $table->timestamp('date_recu')->useCurrent();
            $table->timestamp('date_en_traitement')->nullable();
            $table->timestamp('date_pret')->nullable();
            $table->timestamp('date_recupere')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};