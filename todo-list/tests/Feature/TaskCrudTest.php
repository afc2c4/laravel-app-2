<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Cria a tabela temporária de tarefas em memória para execução dos testes.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->boolean('is_completed')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Teste 1: Visitante não autenticado é redirecionado para /login.
     */
    public function test_unauthenticated_user_cannot_access_tasks(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Teste 2: Usuário autenticado pode criar uma tarefa vinculada a ele.
     */
    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'Comprar mantimentos',
            'description' => 'Leite, pão e café',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'Comprar mantimentos',
            'user_id' => $user->id,
            'is_completed' => false,
        ]);
    }

    /**
     * Teste 3: Usuário só enxerga suas próprias tarefas na listagem.
     */
    public function test_user_only_sees_their_own_tasks(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskA = $userA->tasks()->create(['title' => 'Tarefa do Usuário A', 'is_completed' => false]);
        $taskB = $userB->tasks()->create(['title' => 'Tarefa do Usuário B', 'is_completed' => false]);

        $response = $this->actingAs($userA)->get(route('tasks.index'));

        $response->assertOk();
        $response->assertSee('Tarefa do Usuário A');
        $response->assertDontSee('Tarefa do Usuário B');
    }

    /**
     * Teste 4: Usuário pode atualizar sua própria tarefa.
     */
    public function test_user_can_update_their_own_task(): void
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create(['title' => 'Título Antigo', 'is_completed' => false]);

        $response = $this->actingAs($user)->put(route('tasks.update', $task), [
            'title' => 'Título Atualizado',
            'description' => 'Nova descrição',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Título Atualizado',
            'description' => 'Nova descrição',
        ]);
    }

    /**
     * Teste 5: Usuário NÃO pode editar tarefa de outro usuário (Erro 403).
     */
    public function test_user_cannot_update_another_users_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskB = $userB->tasks()->create(['title' => 'Tarefa de B', 'is_completed' => false]);

        $response = $this->actingAs($userA)->put(route('tasks.update', $taskB), [
            'title' => 'Tentativa Hacker',
        ]);

        $response->assertForbidden();
    }
}
