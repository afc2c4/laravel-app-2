<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
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

        return redirect()->route('tasks.create')->with('status', 'Tarefa criada com sucesso!');
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

}
