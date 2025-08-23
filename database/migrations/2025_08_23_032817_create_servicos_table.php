<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->decimal('preco', 10, 2);
            $table->integer('duracao_minutos');
            $table->enum('categoria', ['BARBEARIA', 'SKINCARE']);
            $table->integer('pontos_olimpo_coin_gerados')->default(0);
            $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('servicos');
    }
};
