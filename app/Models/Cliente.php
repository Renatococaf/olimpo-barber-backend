<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Note que estendemos de Authenticatable para poder usar o sistema de login do Laravel
class Cliente extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Opcional: Especifica o nome da tabela se ele for diferente da convenção (plural do nome do model).
     * No nosso caso, 'Cliente' -> 'clientes', então não seria obrigatório, mas é uma boa prática.
     */
    protected $table = 'clientes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'sobrenome',
        'email',
        'telefone',
        'password',
        'saldo_olimpo_coins',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
