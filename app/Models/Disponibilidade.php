<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disponibilidade extends Model
{
    use HasFactory;

    /**
     * Os campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'funcionario_id',
        'dia_da_semana',
        'hora_inicio',
        'hora_fim',
    ];

    /**
     * Esta tabela não precisa das colunas created_at e updated_at.
     */
    public $timestamps = false;

    /**
     * Define o relacionamento: Uma Disponibilidade PERTENCE A um Funcionário.
     */
    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class);
    }
}
