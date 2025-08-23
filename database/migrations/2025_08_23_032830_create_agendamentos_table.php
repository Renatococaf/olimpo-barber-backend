<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('agendamentos', function (Blueprint $table) {
        $table->id();

        // Chaves Estrangeiras (Relacionamentos)
        // Usamos a convenção do Laravel (ex: cliente_id) para facilitar a vida no Model
        $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
        $table->foreignId('funcionario_id')->constrained('funcionarios')->onDelete('restrict');
        $table->foreignId('servico_id')->constrained('servicos')->onDelete('restrict');

        // Colunas do Agendamento
        $table->timestamp('data_hora_inicio');
        $table->timestamp('data_hora_fim');
        $table->enum('status', ['AGENDADO', 'CONCLUIDO', 'CANCELADO', 'NAO_COMPARECEU'])->default('AGENDADO');
        $table->text('observacoes')->nullable();

        $table->timestamps(); // created_at e updated_at
    });
}

    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
