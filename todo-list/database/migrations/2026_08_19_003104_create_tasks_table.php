<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as alterações no banco de dados.
     *
     * Raciocínio de Arquitetura de Dados:
     * 1. foreignId('user_id'): Cria a coluna de chave estrangeira indexada.
     * 2. constrained(): Estabelece a restrição referencial com a tabela 'users'.
     * 3. cascadeOnDelete(): Se um usuário for excluído, todas as suas tarefas serão
     *    apagadas automaticamente pelo banco, evitando registros órfãos.
     * 4. boolean('is_completed')->default(false): Define que novas tarefas começam pendentes.
     * 5. timestamps(): Cria automaticamente as colunas 'created_at' e 'updated_at'.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverte as alterações (Rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
