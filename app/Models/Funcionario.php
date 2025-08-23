<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable; // Para futuras notificações
use Illuminate\Foundation\Auth\User as Authenticatable; // Para permitir login

class Funcionario extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * A lista de campos que podem ser preenchidos em massa.
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
        'password' => 'hashed', // Garante que a senha seja sempre criptografada
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento: Um Funcionário TEM MUITOS Agendamentos.
     */
    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }
}
