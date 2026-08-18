<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Tarefa') }}
            </h2>
            <div class="space-x-4">
                <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                    Editar Tarefa
                </a>
                <a href="{{ route('tasks.index') }}" class="text-sm font-medium text-gray-600 hover:underline">
                    &larr; Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 border-b border-gray-200 pb-4">
                    <span class="text-xs uppercase tracking-wide font-semibold text-gray-400">Título</span>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $task->title }}</h3>
                </div>

                <div class="mb-6 border-b border-gray-200 pb-4">
                    <span class="text-xs uppercase tracking-wide font-semibold text-gray-400">Status</span>
                    <div class="mt-1">
                        @if ($task->is_completed)
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Concluída
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pendente
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-6 border-b border-gray-200 pb-4">
                    <span class="text-xs uppercase tracking-wide font-semibold text-gray-400">Descrição</span>
                    <p class="text-gray-700 mt-1 whitespace-pre-line leading-relaxed">
                        {{ $task->description ?: 'Nenhuma descrição fornecida.' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-gray-500">
                    <div>
                        <strong>Criada em:</strong> {{ $task->created_at ? $task->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                    </div>
                    <div>
                        <strong>Última atualização:</strong> {{ $task->updated_at ? $task->updated_at->format('d/m/Y H:i:s') : 'N/A' }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
