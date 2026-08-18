<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
| A rota raiz exibe a view de boas-vindas padrão do Laravel/Breeze.
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Autenticação (Área Logada)
|--------------------------------------------------------------------------
| O grupo abaixo utiliza dois middlewares cruciais:
| 1. 'auth': Garante que apenas usuários com sessão ativa acessem essas URLs.
| 2. 'verified': Exige e-mail verificado (se habilitado).
| Se um usuário não autenticado tentar acessar /dashboard, o Laravel o
| redirecionará automaticamente para a tela de login (/login).
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rotas de Perfil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do CRUD de Tarefas
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
});

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação Geradas pelo Breeze
|--------------------------------------------------------------------------
| Carrega todas as rotas de login, registro, logout e recuperação de senha
| definidas dentro do arquivo auth.php.
*/
require __DIR__.'/auth.php';
