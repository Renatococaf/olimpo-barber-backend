<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servico extends Model
{
    use HasFactory;

    /**
     * A lista de campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'duracao_minutos',
        'categoria',
        'pontos_olimpo_coin_gerados',
    ];

    /**
     * Conversões de tipo automáticas.
     */
    protected $casts = [
        'preco' => 'decimal:2',
    ];

    /**
     * Relacionamento: Um Serviço ESTÁ EM MUITOS Agendamentos.
     */
    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }
}
