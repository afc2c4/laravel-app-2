<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Minhas Tarefas') }}
            </h2>
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Nova Tarefa
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Flash Message de Feedback --}}
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($tasks->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-lg mb-4">Você ainda não possui nenhuma tarefa cadastrada.</p>
                        <a href="{{ route('tasks.create') }}" class="text-indigo-600 font-semibold hover:underline">
                            Clique aqui para criar sua primeira tarefa
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status (Clique p/ alternar)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criada em</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($tasks as $task)
                                    <tr class="{{ $task->is_completed ? 'bg-gray-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- Botão de Toggle de Status --}}
                                            <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" title="Clique para alterar o status" class="inline-flex items-center">
                                                    @if ($task->is_completed)
                                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 hover:bg-green-200 transition">
                                                            ✓ Concluída
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition">
                                                            ○ Pendente
                                                        </span>
                                                    @endif
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $task->is_completed ? 'line-through text-gray-400' : 'text-gray-900' }}">
                                            {{ $task->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $task->created_at ? $task->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="inline-flex items-center space-x-3">
                                                <a href="{{ route('tasks.show', $task) }}" class="text-gray-600 hover:text-gray-900">Ver</a>
                                                <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>

                                                <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
