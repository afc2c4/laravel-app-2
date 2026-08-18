<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Tarefa') }}
            </h2>
            <a href="{{ route('tasks.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">
                &larr; Cancelar e Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Formulário de Edição --}}
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf
                    @method('PUT')

                    <!-- Título -->
                    <div class="mb-4">
                        <label for="title" class="block font-medium text-sm text-gray-700">Título da Tarefa *</label>
                        <input id="title" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="title" value="{{ old('title', $task->title) }}" required autofocus />
                        @error('title')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div class="mb-4">
                        <label for="description" class="block font-medium text-sm text-gray-700">Descrição (Opcional)</label>
                        <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4 gap-4">
                        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 hover:underline">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Salvar Alterações') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
