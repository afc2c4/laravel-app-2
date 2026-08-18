<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa (Mass Assignment).
     *
     * Raciocínio: O Laravel bloqueia por padrão a inserção em lote de dados
     * via Task::create() para evitar injeções maliciosas (Mass Assignment Vulnerability).
     * Declarar $fillable autoriza explicitamente quais colunas o controller pode preencher.
     */
    protected $fillable = [
        'title',
        'description',
        'is_completed',
        'user_id',
    ];

    /**
     * Define o relacionamento: Uma Tarefa pertence a um Usuário (N:1).
     *
     * Raciocínio: Permite acessar o dono da tarefa via $task->user e garante
     * a integridade semântica do Eloquent ORM.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
