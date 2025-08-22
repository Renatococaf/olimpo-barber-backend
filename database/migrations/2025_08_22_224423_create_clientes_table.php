<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Este método é executado quando você roda `php artisan migrate`.
     * Ele é responsável por CRIAR a estrutura da tabela.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            // Coluna de ID auto-incremental (SERIAL PRIMARY KEY)
            $table->id();

            // Colunas de texto (VARCHAR)
            $table->string('nome', 100);
            $table->string('sobrenome', 100);
            $table->string('email')->unique(); // Garante que não haja e-mails duplicados
            $table->string('telefone', 20)->unique()->nullable(); // Único, mas pode ser nulo (opcional)

            // Coluna para a senha. O nome 'password' é uma convenção do Laravel.
            // O hash da senha será armazenado aqui.
            $table->string('password');

            // Coluna para o saldo de moedas (DECIMAL)
            $table->decimal('saldo_olimpo_coins', 10, 2)->default(0.00);

            // Colunas especiais do Laravel
            $table->rememberToken(); // Usado para a funcionalidade "Lembrar de mim"
            $table->timestamps(); // Cria as colunas `created_at` e `updated_at` automaticamente
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
