<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- ADICIONADO
use Illuminate\Notifications\Notifiable;             // <-- ADICIONADO
use Laravel\Sanctum\HasApiTokens;                      // <-- ADICIONADO (O MAIS IMPORTANTE)

// MUDE DE 'extends Model' PARA 'extends Authenticatable'
class Funcionario extends Authenticatable
{
    // ADICIONE 'HasApiTokens' e 'Notifiable' AQUI
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * A "lista de permissão" de campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'nome_completo',
        'email',
        'password',
        'tipo_funcionario',
        'ativo',
    ];

    /**
     * Campos que devem ser ocultados na serialização (respostas JSON).
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Conversões de tipo automáticas.
     */
    protected $casts = [
        'password' => 'hashed',
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento: Um Funcionário TEM MUITOS Agendamentos.
     */
    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }

    public function disponibilidades(): HasMany
    {
        return $this->hasMany(Disponibilidade::class);
    }
}
