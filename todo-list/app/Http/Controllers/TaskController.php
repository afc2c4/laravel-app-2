<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Lista apenas as tarefas do usuário autenticado.
     */
    public function index(Request $request): View
    {
        $tasks = $request->user()
            ->tasks()
            ->latest()
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Exibe o formulário de criação de tarefa.
     *
     * Raciocínio: Retorna a view tasks.create renderizada dentro do layout autenticado.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Processa a validação e salva a nova tarefa vinculada ao usuário logado.
     *
     * Raciocínio de Negócio:
     * 1. Validação: Impede campos vazios ou formatos inválidos.
     * 2. Associação Segura: Usamos $request->user()->tasks()->create(...) em vez de
     *    Task::create([... 'user_id' => $id ...]). Isso impede que um usuário tente
     *    injetar o ID de outra pessoa no payload da requisição.
     * 3. Redirecionamento com Flash Message: Envia o usuário de volta com mensagem de feedback.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->user()->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_completed' => false,
        ]);

        return redirect()->route('tasks.index')->with('status', 'Tarefa criada com sucesso!');
    }


    /**
     * Exibe os detalhes de uma tarefa específica.
     *
     * Raciocínio de Isolamento de Dados:
     * O Laravel faz o Route Model Binding injetando a instância de $task pelo ID da URL.
     * Validamos se o `user_id` da tarefa confere com o ID do usuário autenticado.
     * Se forem diferentes, abortamos com erro HTTP 403 (Acesso Negado).
     */
    public function show(Request $request, Task $task): View
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Exibe o formulário de edição da tarefa.
     *
     * Raciocínio de Posse:
     * Antes de exibir a view, validamos se a tarefa pertence ao usuário logado.
     * Caso contrário, a requisição é bloqueada imediatamente com erro 403.
     */
    public function edit(Request $request, Task $task): View
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * Atualiza os dados da tarefa existente.
     *
     * Raciocínio de Negócio:
     * 1. Verificação de Posse: Impede edição indevida via manipulação de ID na URL.
     * 2. Validação: Assegura que o título continue preenchido e dentro dos limites.
     * 3. Atualização Segura: Aplica apenas os atributos validados diretamente no model.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('tasks.index')->with('status', 'Tarefa atualizada com sucesso!');
    }

    /**
     * Remove a tarefa especificada do banco de dados.
     *
     * Raciocínio de Segurança e Integridade:
     * 1. Verificação de Posse: Garantimos que o usuário autenticado é o dono do registro.
     * 2. Exclusão Atômica: O método $task->delete() emite uma query DELETE precisa por ID.
     * 3. Redirecionamento: Retorna para a listagem com mensagem de feedback de sucesso.
     */
    public function destroy(Request $request, Task $task): RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('status', 'Tarefa excluída com sucesso!');
    }

}
