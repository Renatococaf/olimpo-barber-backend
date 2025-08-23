<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agendamento extends Model
{
    use HasFactory;

    /**
     * A propriedade $fillable define quais colunas da tabela
     * podem ser preenchidas em massa usando o método create().
     * Sem isso, o seu método store() no controller falharia por segurança.
     */
    protected $fillable = [
        'cliente_id',
        'funcionario_id',
        'servico_id',
        'data_hora_inicio',
        'data_hora_fim',
        'status',
        'observacoes',
    ];

    /**
     * A propriedade $casts converte automaticamente os dados do banco
     * para tipos de dados úteis no PHP. Aqui, convertemos as strings
     * de data/hora para objetos Carbon, que são muito mais fáceis de manipular.
     */
    protected $casts = [
        'data_hora_inicio' => 'datetime',
        'data_hora_fim' => 'datetime',
    ];

    /**
     * Define o relacionamento: Um Agendamento PERTENCE A um Cliente.
     * Isso permite que a gente faça $agendamento->cliente para pegar os dados do cliente.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Define o relacionamento: Um Agendamento PERTENCE A um Funcionário.
     */
    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class);
    }

    /**
     * Define o relacionamento: Um Agendamento PERTENCE A um Serviço.
     */
    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class);
    }
}
