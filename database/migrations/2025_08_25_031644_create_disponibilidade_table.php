<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('disponibilidades', function (Blueprint $table) {
        $table->id();
        $table->foreignId('funcionario_id')->constrained()->cascadeOnDelete(); // Se o funcionário for deletado, seus horários também são.

        // Usaremos um inteiro para o dia da semana (0 = Domingo, 1 = Segunda, ..., 6 = Sábado)
        $table->tinyInteger('dia_da_semana');

        $table->time('hora_inicio');
        $table->time('hora_fim');

        // Garante que um funcionário não pode ter o mesmo dia da semana cadastrado duas vezes
        $table->unique(['funcionario_id', 'dia_da_semana']);
    });
}
    public function down(): void
    {
        Schema::dropIfExists('disponibilidade');
    }
};
