<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos permitidos para cadastro em massa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos na serialização (JSON/Array).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversão de tipos nativos (Casting).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define o relacionamento: Um Usuário possui muitas Tarefas (1:N).
     *
     * Raciocínio: Esse método permite criar e buscar tarefas vinculadas diretamente
     * à sessão ativa com uma sintaxe limpa: $request->user()->tasks()->create(...)
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
