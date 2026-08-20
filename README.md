# Tutorial - Todo List com LAravel e SQLite

## PARTE 1: Configuração Inicial do Ambiente e Projeto

---

### 1. Objetivo

Preparar o ambiente dentro do GitHub Codespace, verificar os pré-requisitos essenciais (PHP 8.2+ e Composer) e inicializar uma nova aplicação **Laravel 11+** dentro de uma pasta dedicada chamada `todo-list`.

---

### 2. Implementação Guiada (Comandos + Raciocínio)

#### Passo 1: Validação do Ambiente

Antes de criar qualquer projeto, precisamos garantir que o runtime do Codespace possui a versão mínima de PHP exigida pelo Laravel 11.

Execute no terminal do Codespace:

```bash
php -v && composer -V

```

> **Raciocínio Técnico:**
> O Laravel 11 exige no mínimo o **PHP 8.2**. Esse comando confirma se o runtime do container atende às restrições de versão e se o gerenciador de dependências Composer está pronto para baixar o esqueleto da aplicação.

---

#### Passo 2: Criando a Pasta Dedicada e o Projeto Laravel

Vamos criar o projeto Laravel diretamente dentro da pasta `todo-list`.

Execute:

```bash
composer create-project laravel/laravel todo-list

```

> **Raciocínio Técnico:**
> O `composer create-project` clona o repositório base oficial do Laravel, baixa todas as dependências do ecossistema no diretório `vendor/`, gera a chave de criptografia de aplicação (`APP_KEY` no `.env`) e configura a estrutura base de diretórios sem necessidade de instalar o instalador global do Laravel.

---

#### Passo 3: Navegar para o Diretório do Projeto

Entre na pasta recém-criada para que todos os comandos subsequentes sejam executados na raiz do projeto:

```bash
cd todo-list

```

---

#### Passo 4: Extensões Recomendadas no VS Code / Codespace

Para melhorar o desenvolvimento, instale as extensões recomendadas abrindo a aba de Extensões (`Ctrl+Shift+X` ou `Cmd+Shift+X`) e buscando por:

* **PHP Intelephense** (`bmewburn.vscode-intelephense-client`): Fornece auto-complete, tipagem estática e navegação de código.
* **Laravel Blade Snippets** (`onecentlin.laravel-blade`): Destaca a sintaxe dos arquivos `.blade.php`.

---

#### Passo 5: Inicializando o Servidor Local de Desenvolvimento

Inicie o servidor embutido do Laravel para validar que a instalação foi concluída com sucesso:

```bash
php artisan serve

```

> **Raciocínio Técnico:**
> O comando `php artisan serve` inicializa o servidor de desenvolvimento interno do PHP configurado para rotear todas as requisições públicas para o ponto de entrada `public/index.php`. O GitHub Codespace detectará a porta (`8000`) e criará automaticamente um túnel HTTP para visualização no navegador.

---

### 3. Feedback de Comandos e Solução de Problemas

* **Ao rodar `composer create-project laravel/laravel todo-list`:**
* **Esperado:** O Composer fará o download dos pacotes, gerará o arquivo `.env` e exibirá ao final:
`> @php artisan key:generate --ansi`
`INFO Application key set successfully.`
* **Se der erro de memória ou timeout:** Execute `composer clear-cache` e tente novamente. Se acusar versão incompatível do PHP, certifique-se de que está usando uma imagem de Codespace com PHP 8.2 ou superior.


* **Ao rodar `php artisan serve`:**
* **Esperado:** O terminal exibirá:
`INFO Server running on [[http://127.0.0.1:8000](http://127.0.0.1:8000)].`
Uma notificação pop-up do VS Code/Codespace aparecerá no canto inferior direito dizendo *"Your application running on port 8000 is available"*, com o botão **Open in Browser**.
* **Se a porta já estiver em uso:** O Laravel tentará a porta `8001` automaticamente. Caso precise forçar outra porta, use: `php artisan serve --port=8080`.



---

### 4. Verificação e Testes

1. Clique na notificação do Codespace para abrir a URL em uma nova aba (ou acesse a aba **Ports** do VS Code e clique no ícone do globo na linha da porta `8000`).
2. Você deverá ver a página de boas-vindas padrão do **Laravel 11**.
3. No terminal, você pode interromper o servidor a qualquer momento pressionando `Ctrl + C`.

---
## PARTE 2: Autenticação com Laravel Breeze

---

### 1. Objetivo

Integrar o pacote oficial **Laravel Breeze** configurado exclusivamente com a stack **Blade + Tailwind CSS**. Esta etapa gerará toda a estrutura de autenticação (cadastro, login, logout, redefinição de senha e dashboard protegido), preparando os middlewares e a sessão de usuário necessários para o isolamento dos dados de cada conta.

---

### 2. Implementação Guiada (Comandos + Código + Raciocínio)

Certifique-se de estar dentro do diretório `todo-list` no terminal do Codespace.

```bash
cd todo-list

```

---

#### Passo 1: Instalação do Pacote Laravel Breeze via Composer

Adicionamos o pacote como dependência de desenvolvimento:

```bash
composer require laravel/breeze --dev

```

> **Raciocínio Técnico:**
> O **Laravel Breeze** é o *starter kit* oficial que implementa autenticação completa sem dependências pesadas de SPAs. Ele é instalado com a flag `--dev` porque sua principal função é atuar como um gerador de código (scaffolding): ele publica rotas, controllers, formulários Blade e regras de validação diretamente dentro da nossa base de código, dando total controle para customização.

---

#### Passo 2: Execução do Scaffolding do Breeze

Executamos o comando Artisan para gerar as views em Blade e desativar qualquer execução automática de banco neste momento (já que definimos que o banco será configurado no final):

```bash
php artisan breeze:install blade --no-interaction

```

> **Raciocínio Técnico:**
> O argumento `blade` instrui o Artisan a publicar views puras em Blade (`resources/views/auth/`, `resources/views/layouts/`) estilizadas com Tailwind CSS, em vez de gerar componentes React ou Vue. A flag `--no-interaction` aceita os padrões recomendados e impede prompts interativos desnecessários no terminal do Codespace.

---

#### Passo 3: Compilação dos Assets de Frontend (Tailwind + Vite)

O Breeze publica as diretivas do Tailwind no arquivo `resources/css/app.css` e a configuração do bundler `vite.config.js`. Precisamos instalar os pacotes Node.js e compilar os arquivos estáticos:

```bash
npm install && npm run build

```

> **Raciocínio Técnico:**
> O Vite processa as classes utilitárias do Tailwind CSS usadas nos componentes Blade e gera os bundles otimizados em `public/build/`. O comando `npm run build` cria a versão de produção dos assets, eliminando a necessidade de manter um processo `npm run dev` rodando em paralelo no Codespace durante o desenvolvimento das lógicas de backend.

---

#### Passo 4: Análise e Ajuste do Arquivo de Rotas Web

O instalador do Breeze modificou o arquivo de rotas em `routes/web.php` e incluiu um arquivo auxiliar em `routes/auth.php`.

Vamos verificar e consolidar o arquivo completo de rotas para entender seu fluxo:

* **Origem:** `routes/web.php` **[MANUAL / VERIFICAÇÃO]**
* **Localização:** `todo-list/routes/web.php`

```php
<?php

use App\Http\Controllers\ProfileController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação Geradas pelo Breeze
|--------------------------------------------------------------------------
| Carrega todas as rotas de login, registro, logout e recuperação de senha
| definidas dentro do arquivo auth.php.
*/
require __DIR__.'/auth.php';

```

> **Raciocínio Técnico da Estrutura de Rotas:**
> 1. **Separação de Responsabilidades:** O Laravel mantém rotas públicas e autenticadas isoladas logicamente através do middleware `auth`.
> 2. **Nomeação de Rotas (`->name(...)`):** Permite referenciar as rotas nas views através do helper `route('dashboard')` ou `route('login')`, tornando o sistema resiliente a eventuais mudanças de URL física.
> 3. **Modularidade:** O `require __DIR__.'/auth.php';` mantém o arquivo principal `web.php` limpo, delegando as rotas granulares de autenticação para um arquivo dedicado.
> 
> 

---

### 3. Feedback de Comandos e Solução de Problemas

* **Ao rodar `composer require laravel/breeze --dev`:**
* **Esperado:** O terminal listará a resolução de dependências e concluirá com:
`Package operations: X installs, 0 updates, 0 removals`
`Generating autoload files`
* **Se acusar conflito de memória:** Adicione a variável temporária de ambiente: `COMPOSER_MEMORY_LIMIT=-1 composer require laravel/breeze --dev`.


* **Ao rodar `php artisan breeze:install blade --no-interaction`:**
* **Esperado:** Mensagens indicando a publicação de controllers, views e rotas:
`INFO Breeze scaffolding installed successfully.`
* **Se houver erro de permissão:** Certifique-se de estar dentro do diretório `todo-list` e que o usuário do Codespace possui permissão de escrita (`chmod -R 775 storage bootstrap/cache`).


* **Ao rodar `npm install && npm run build`:**
* **Esperado:** Download dos pacotes `node_modules` e exibição do relatório do Vite:
`✓ built in ...ms`
`public/build/assets/app-*.css`
`public/build/assets/app-*.js`
* **Se der erro de versão do Node:** O Vite exige Node 18+. Verifique no terminal com `node -v`. Nos Codespaces padrão do GitHub, a versão já vem configurada no LTS (v20+).



---

### 4. Verificação e Estrutura dos Arquivos

Neste ponto, o Breeze gerou os seguintes componentes no projeto:

1. **Controllers de Autenticação:** `app/Http/Controllers/Auth/` (Login, Registro, Password Reset, etc.).
2. **Views de Autenticação:** `resources/views/auth/` (templates Blade para cada tela).
3. **Layouts Base:** `resources/views/layouts/app.blade.php` (layout autenticado) e `resources/views/layouts/guest.blade.php` (layout para visitantes).

---

A autenticação básica e os layouts estão estruturados no projeto.

---

## PARTE 3: Estrutura das Tarefas — Operação CREATE

---

### 1. Objetivo

Implementar a criação de novas tarefas. Construiremos o modelo de dados em memória (`Task`), o controller de controle de fluxo (`TaskController`), as regras de validação e a tela com formulário em Blade. O foco central aqui é garantir que **cada tarefa criada seja obrigatoriamente vinculada ao usuário autenticado** (`user_id`).

---

### 2. Implementação Guiada (Comandos + Código + Raciocínio)

#### Passo 1: Criação do Model `Task`

Execute no terminal:

```bash
php artisan make:model Task

```

> **Raciocínio Técnico:**
> Como definimos que o banco/migrations ficarão para o final, usamos apenas `make:model Task` (sem a flag `-m`). O Model atua como a camada ORM (Eloquent), mapeando os atributos e relacionamentos da entidade antes mesmo da tabela física existir.

Agora, abra o arquivo gerado e defina os campos preenchíveis e o relacionamento com o usuário:

* **Origem:** `app/Models/Task.php` **[ARTISAN]**
* **Localização:** `todo-list/app/Models/Task.php`

```php
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

```

---

#### Passo 2: Atualização do Model `User`

Precisamos ensinar ao modelo de Usuário que ele pode possuir múltiplas tarefas.

* **Origem:** `app/Models/User.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/app/Models/User.php`

Substitua todo o conteúdo do arquivo por:

```php
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

```

---

#### Passo 3: Criação do Controller `TaskController`

Execute no terminal:

```bash
php artisan make:controller TaskController

```

Abra o arquivo gerado e adicione a lógica dos métodos `create` e `store`:

* **Origem:** `app/Http/Controllers/TaskController.php` **[ARTISAN]**
* **Localização:** `todo-list/app/Http/Controllers/TaskController.php`

```php
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
}

```

---

#### Passo 4: Registro das Rotas de Criação

Precisamos expor as URLs protegidas por autenticação no arquivo `routes/web.php`.

* **Origem:** `routes/web.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/routes/web.php`

Substitua todo o conteúdo do arquivo:

```php
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Autenticação
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rotas de Perfil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do CRUD de Tarefas - CREATE
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
});

require __DIR__.'/auth.php';

```

---

#### Passo 5: Criação da View Blade para o Formulário de Criação

Crie a pasta `resources/views/tasks` e dentro dela o arquivo `create.blade.php`:

Execute no terminal:

```bash
mkdir -p resources/views/tasks && touch resources/views/tasks/create.blade.php

```

Abra o arquivo recém-criado e insira o código completo:

* **Origem:** `resources/views/tasks/create.blade.php` **[MANUAL]**
* **Localização:** `todo-list/resources/views/tasks/create.blade.php`

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Tarefa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Mensagem de Sucesso (Flash Message) --}}
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Formulário de Criação --}}
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf

                    <!-- Título -->
                    <div class="mb-4">
                        <label for="title" class="block font-medium text-sm text-gray-700">Título da Tarefa *</label>
                        <input id="title" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="title" value="{{ old('title') }}" required autofocus />
                        @error('title')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div class="mb-4">
                        <label for="description" class="block font-medium text-sm text-gray-700">Descrição (Opcional)</label>
                        <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Salvar Tarefa') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

```

> **Raciocínio Técnico da View:**
> 1. `<x-app-layout>`: Reutiliza a casca principal gerada pelo Breeze (navbar com logout, estilos e scripts).
> 2. `@csrf`: Adiciona o token oculto obrigatório do Laravel para proteger o formulário contra ataques de Cross-Site Request Forgery.
> 3. `value="{{ old('title') }}"`: Garante que, se a validação falhar, o usuário não perca o texto digitado.
> 4. `@error('title')`: Diretiva do Blade que injeta a mensagem de erro específica caso a validação do controller reprove a requisição.
> 
> 

---

### 3. Feedback de Comandos e Solução de Problemas

* **Ao rodar `php artisan make:model Task`:**
* **Esperado:** `INFO Model [app/Models/Task.php] created successfully.`


* **Ao rodar `php artisan make:controller TaskController`:**
* **Esperado:** `INFO Controller [app/Http/Controllers/TaskController.php] created successfully.`


* **Ao rodar `mkdir -p resources/views/tasks && touch ...`:**
* **Esperado:** O diretório e arquivo vazio serão criados silenciosamente. Se listar com `ls resources/views/tasks`, verá `create.blade.php`.



---

### 4. Verificação das Rotas

Para validar que as rotas de criação foram registradas corretamente pelo Laravel, execute no terminal:

```bash
php artisan route:list --path=tasks

```

O terminal deverá exibir:

* `GET|HEAD tasks/create .......................... tasks.create › TaskController@create`
* `POST     tasks ................................. tasks.store › TaskController@store`

---

Sim, está **100% correto**!

A estrutura na sua tela confirma o sucesso de todos os passos:

* **Vite Build concluído:** O terminal exibiu `✓ built in 828ms` e gerou os arquivos compilados em `public/build/assets/` (`app-*.css` e `app-*.js`).
* **Controllers e Views do Breeze:** As pastas `app/Http/Controllers/Auth` e `resources/views/auth` foram criadas com todos os componentes necessários.
* **Arquivo de rotas:** O `routes/web.php` está com a estrutura exata e os comentários explicativos.

---

## PARTE 3: Estrutura das Tarefas — Operação CREATE

---

### 1. Objetivo

Implementar a criação de novas tarefas. Construiremos o modelo de dados em memória (`Task`), o controller de controle de fluxo (`TaskController`), as regras de validação e a tela com formulário em Blade. O foco central aqui é garantir que **cada tarefa criada seja obrigatoriamente vinculada ao usuário autenticado** (`user_id`).

---

### 2. Implementação Guiada (Comandos + Código + Raciocínio)

#### Passo 1: Criação do Model `Task`

Execute no terminal:

```bash
php artisan make:model Task

```

> **Raciocínio Técnico:**
> Como definimos que o banco/migrations ficarão para o final, usamos apenas `make:model Task` (sem a flag `-m`). O Model atua como a camada ORM (Eloquent), mapeando os atributos e relacionamentos da entidade antes mesmo da tabela física existir.

Agora, abra o arquivo gerado e defina os campos preenchíveis e o relacionamento com o usuário:

* **Origem:** `app/Models/Task.php` **[ARTISAN]**
* **Localização:** `todo-list/app/Models/Task.php`

```php
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

```

---

#### Passo 2: Atualização do Model `User`

Precisamos ensinar ao modelo de Usuário que ele pode possuir múltiplas tarefas.

* **Origem:** `app/Models/User.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/app/Models/User.php`

Substitua todo o conteúdo do arquivo por:

```php
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

```

---

#### Passo 3: Criação do Controller `TaskController`

Execute no terminal:

```bash
php artisan make:controller TaskController

```

Abra o arquivo gerado e adicione a lógica dos métodos `create` e `store`:

* **Origem:** `app/Http/Controllers/TaskController.php` **[ARTISAN]**
* **Localização:** `todo-list/app/Http/Controllers/TaskController.php`

```php
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
}

```

---

#### Passo 4: Registro das Rotas de Criação

Precisamos expor as URLs protegidas por autenticação no arquivo `routes/web.php`.

* **Origem:** `routes/web.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/routes/web.php`

Substitua todo o conteúdo do arquivo:

```php
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Autenticação
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rotas de Perfil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do CRUD de Tarefas - CREATE
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
});

require __DIR__.'/auth.php';

```

---

#### Passo 5: Criação da View Blade para o Formulário de Criação

Crie a pasta `resources/views/tasks` e dentro dela o arquivo `create.blade.php`:

Execute no terminal:

```bash
mkdir -p resources/views/tasks && touch resources/views/tasks/create.blade.php

```

Abra o arquivo recém-criado e insira o código completo:

* **Origem:** `resources/views/tasks/create.blade.php` **[MANUAL]**
* **Localização:** `todo-list/resources/views/tasks/create.blade.php`

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Tarefa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Mensagem de Sucesso (Flash Message) --}}
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Formulário de Criação --}}
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf

                    <!-- Título -->
                    <div class="mb-4">
                        <label for="title" class="block font-medium text-sm text-gray-700">Título da Tarefa *</label>
                        <input id="title" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="title" value="{{ old('title') }}" required autofocus />
                        @error('title')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div class="mb-4">
                        <label for="description" class="block font-medium text-sm text-gray-700">Descrição (Opcional)</label>
                        <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Salvar Tarefa') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

```

> **Raciocínio Técnico da View:**
> 1. `<x-app-layout>`: Reutiliza a casca principal gerada pelo Breeze (navbar com logout, estilos e scripts).
> 2. `@csrf`: Adiciona o token oculto obrigatório do Laravel para proteger o formulário contra ataques de Cross-Site Request Forgery.
> 3. `value="{{ old('title') }}"`: Garante que, se a validação falhar, o usuário não perca o texto digitado.
> 4. `@error('title')`: Diretiva do Blade que injeta a mensagem de erro específica caso a validação do controller reprove a requisição.
> 
> 

---

### 3. Feedback de Comandos e Solução de Problemas

* **Ao rodar `php artisan make:model Task`:**
* **Esperado:** `INFO Model [app/Models/Task.php] created successfully.`


* **Ao rodar `php artisan make:controller TaskController`:**
* **Esperado:** `INFO Controller [app/Http/Controllers/TaskController.php] created successfully.`


* **Ao rodar `mkdir -p resources/views/tasks && touch ...`:**
* **Esperado:** O diretório e arquivo vazio serão criados silenciosamente. Se listar com `ls resources/views/tasks`, verá `create.blade.php`.



---

### 4. Verificação das Rotas

Para validar que as rotas de criação foram registradas corretamente pelo Laravel, execute no terminal:

```bash
php artisan route:list --path=tasks

```

O terminal deverá exibir:

* `GET|HEAD tasks/create .......................... tasks.create › TaskController@create`
* `POST     tasks ................................. tasks.store › TaskController@store`

---

## PARTE 4: Estrutura das Tarefas — Operação READ (Listagem e Visualização)

---

### 1. Objetivo

Implementar a leitura dos dados em duas frentes:

1. **Listagem Geral (`index`):** Recuperar e listar exclusivamente as tarefas pertencentes ao usuário logado, ordenadas das mais recentes para as mais antigas.
2. **Visualização Individual (`show`):** Exibir os detalhes de uma tarefa específica, garantindo que usuários não consigam visualizar tarefas de outros usuários.

---

### 2. Implementação Guiada (Comandos + Código + Raciocínio)

#### Passo 1: Atualização do `TaskController`

Vamos atualizar o controller para adicionar os métodos `index` e `show`.

* **Origem:** `app/Http/Controllers/TaskController.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/app/Http/Controllers/TaskController.php`

Substitua todo o conteúdo do arquivo por:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Lista apenas as tarefas associadas ao usuário atualmente autenticado.
     * 
     * Raciocínio de Negócio:
     * Em vez de consultar `Task::all()`, usamos `$request->user()->tasks()` para que
     * a cláusula WHERE `user_id = ?` seja aplicada na query.
     * Ordenamos por `latest()` para exibir os registros mais novos no topo.
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
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Processa e persiste a nova tarefa associada ao usuário autenticado.
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
}

```

> **Raciocínio Técnico:**
> O método `abort(403)` dispara uma `HttpException` que interrompe a requisição e exibe a página padrão de "Acesso Proibido", evitando vazamento de dados de tarefas entre usuários distintos.

---

#### Passo 2: Atualização do Arquivo de Rotas (`routes/web.php`)

Adicionamos as rotas nomeadas para `tasks.index` e `tasks.show`.

* **Origem:** `routes/web.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/routes/web.php`

Substitua todo o conteúdo do arquivo por:

```php
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Autenticação
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rotas de Perfil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do CRUD de Tarefas (CREATE e READ)
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
});

require __DIR__.'/auth.php';

```

---

#### Passo 3: Criação da View de Listagem (`tasks/index.blade.php`)

Crie o arquivo no terminal:

```bash
touch resources/views/tasks/index.blade.php

```

Abra o arquivo e insira o código completo:

* **Origem:** `resources/views/tasks/index.blade.php` **[MANUAL]**
* **Localização:** `todo-list/resources/views/tasks/index.blade.php`

```html
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criada em</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($task->is_completed)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Concluída
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pendente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $task->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $task->created_at ? $task->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver Detalhes</a>
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

```

---

#### Passo 4: Criação da View de Visualização Única (`tasks/show.blade.php`)

Crie o arquivo no terminal:

```bash
touch resources/views/tasks/show.blade.php

```

Abra o arquivo e insira o código completo:

* **Origem:** `resources/views/tasks/show.blade.php` **[MANUAL]**
* **Localização:** `todo-list/resources/views/tasks/show.blade.php`

```html
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Tarefa') }}
            </h2>
            <a href="{{ route('tasks.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">
                &larr; Voltar para a lista
            </a>
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

```

---

#### Passo 5: Atualização da Navegação Principal

Para facilitar o acesso à listagem de tarefas no menu do topo, vamos atualizar o componente de navegação do Breeze.

* **Origem:** `resources/views/layouts/navigation.blade.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/resources/views/layouts/navigation.blade.php`

Substitua todo o conteúdo do arquivo por:

```html
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                        {{ __('Tarefas') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                {{ __('Tarefas') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

```

---

### 3. Feedback de Comandos e Solução de Problemas

* **Ao rodar `touch resources/views/tasks/index.blade.php resources/views/tasks/show.blade.php`:**
* **Esperado:** Arquivos criados com sucesso sem saída de erros.
* **Se acusar "No such file or directory":** Verifique se o diretório `resources/views/tasks` existe. Se não existir, crie com `mkdir -p resources/views/tasks`.



---

### 4. Verificação das Rotas

Execute no terminal:

```bash
php artisan route:list --path=tasks

```

O terminal deverá exibir:

* `GET|HEAD tasks ......................... tasks.index › TaskController@index`
* `POST     tasks ......................... tasks.store › TaskController@store`
* `GET|HEAD tasks/create .................. tasks.create › TaskController@create`
* `GET|HEAD tasks/{task} .................. tasks.show › TaskController@show`

---

## PARTE 5: Estrutura das Tarefas — Operação UPDATE (Edição de Tarefas)

---

### 1. Objetivo

Implementar o fluxo completo de edição e atualização de tarefas (`edit` e `update`). Garantiremos que apenas o proprietário da tarefa possa carregar o formulário de edição e submeter alterações no registro.

---

### 2. Implementação Guiada (Comandos + Código + Raciocínio)

#### Passo 1: Atualização do `TaskController`

Adicionamos os métodos `edit` e `update` com a verificação de posse (`user_id`).

* **Origem:** `app/Http/Controllers/TaskController.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/app/Http/Controllers/TaskController.php`

Substitua todo o conteúdo do arquivo por:

```php
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
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Salva a nova tarefa vinculada ao usuário logado.
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
}

```

---

#### Passo 2: Atualização do Arquivo de Rotas (`routes/web.php`)

Registramos as rotas `tasks.edit` (GET) e `tasks.update` (PUT/PATCH).

* **Origem:** `routes/web.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/routes/web.php`

Substitua todo o conteúdo do arquivo por:

```php
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Autenticação
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rotas de Perfil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do CRUD de Tarefas (CREATE, READ, UPDATE)
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
});

require __DIR__.'/auth.php';

```

---

#### Passo 3: Criação da View de Edição (`tasks/edit.blade.php`)

Crie o arquivo no terminal:

```bash
touch resources/views/tasks/edit.blade.php

```

Abra o arquivo e insira o código completo:

* **Origem:** `resources/views/tasks/edit.blade.php` **[MANUAL]**
* **Localização:** `todo-list/resources/views/tasks/edit.blade.php`

```html
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

```

> **Raciocínio Técnico do Formulário de Edição:**
> 1. `@method('PUT')`: Navegadores HTML só suportam `GET` e `POST` em tags `<form>`. A diretiva `@method('PUT')` insere um input oculto `_method="PUT"` que o Laravel interpreta para rotear a requisição ao verbo HTTP correto (`Route::put`).
> 2. `old('title', $task->title)`: Retorna o valor digitado anteriormente se a validação falhar; caso contrário, usa o valor atual salvo no banco de dados.
> 
> 

---

#### Passo 4: Atualização das Views `index.blade.php` e `show.blade.php` com Link de Edição

Atualize a tabela na view de listagem para incluir o botão de editar:

* **Origem:** `resources/views/tasks/index.blade.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/resources/views/tasks/index.blade.php`

Substitua todo o conteúdo do arquivo por:

```html
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criada em</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($task->is_completed)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Concluída
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pendente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $task->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $task->created_at ? $task->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-gray-600 hover:text-gray-900">Ver</a>
                                            <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
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

```

Atualize a tela de detalhes para incluir o botão de edição:

* **Origem:** `resources/views/tasks/show.blade.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/resources/views/tasks/show.blade.php`

Substitua todo o conteúdo do arquivo por:

```html
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

```

---

### 3. Feedback de Comandos e Solução de Problemas

* **Ao rodar `touch resources/views/tasks/edit.blade.php`:**
* **Esperado:** Criação silenciosa do arquivo no diretório.
* **Se acusar "No such file or directory":** Verifique se o diretório `resources/views/tasks` existe.



---

### 4. Bateria de Testes Automatizados via Terminal (Feature Test)

Como o banco de dados definitivo será migrado na Parte 8, criamos uma suíte de testes de integração HTTP usando **SQLite em memória (`:memory:`)**. Isso permite testar **Rotas**, **Autenticação**, **Isolamento de Usuários** e os fluxos de **Create**, **Read** e **Update** imediatamente via terminal.

#### Passo A: Gerar a classe de teste

Execute no terminal:

```bash
php artisan make:test TaskCrudTest

```

> **Raciocínio Técnico:**
> O Artisan cria uma classe de teste de feature em `tests/Feature/TaskCrudTest.php`. Feature tests simulam requisições HTTP reais (GET, POST, PUT), executando os Middlewares, Controllers, Validações e Blade templates.

---

#### Passo B: Inserir os Casos de Teste

Abra o arquivo gerado e substitua pelo código completo:

* **Origem:** `tests/Feature/TaskCrudTest.php` **[ARTISAN]**
* **Localização:** `todo-list/tests/Feature/TaskCrudTest.php`

```php
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

```

---

#### Passo C: Executando os Testes via Terminal

Execute:

```bash
php artisan test --filter=TaskCrudTest

```

* **Resultado Esperado no Terminal:**
```text
PASS  Tests\Feature\TaskCrudTest
✓ unauthenticated user cannot access tasks
✓ authenticated user can create task
✓ user only sees their own tasks
✓ user can update their own task
✓ user cannot update another users task

Tests:    5 passed (12 assertions)
Duration: 0.25s

```


* **Se algum teste falhar:** Verifique se as rotas em `routes/web.php` e os métodos em `TaskController.php` foram copiados na íntegra.

---

### 5. Verificação das Rotas Registradas

Execute no terminal:

```bash
php artisan route:list --path=tasks

```

O terminal listará todas as operações implementadas até agora:

* `GET|HEAD tasks ......................... tasks.index › TaskController@index`
* `POST     tasks ......................... tasks.store › TaskController@store`
* `GET|HEAD tasks/create .................. tasks.create › TaskController@create`
* `GET|HEAD tasks/{task} .................. tasks.show › TaskController@show`
* `PUT      tasks/{task} .................. tasks.update › TaskController@update`
* `GET|HEAD tasks/{task}/edit ............. tasks.edit › TaskController@edit`

---

## PARTE 7: Requisitos Funcionais Específicos — Alternar Status (Concluída / Não Concluída)

---

### 1. Objetivo

Implementar uma ação rápida (toggle) para que o usuário possa marcar ou desmarcar uma tarefa como concluída diretamente na tabela de listagem, sem precisar abrir a tela de edição completa.

---

### 2. Implementação Guiada (Comandos + Código + Raciocínio)

#### Passo 1: Atualização do `TaskController`

Adicionamos o método `toggleComplete`, que inverte o valor booleano do atributo `is_completed`.

* **Origem:** `app/Http/Controllers/TaskController.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/app/Http/Controllers/TaskController.php`

Substitua todo o conteúdo do arquivo por:

```php
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
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Salva a nova tarefa vinculada ao usuário logado.
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
     */
    public function destroy(Request $request, Task $task): RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('status', 'Tarefa excluída com sucesso!');
    }

    /**
     * Alterna o estado de conclusão da tarefa (Toggle: Concluída <-> Pendente).
     * 
     * Raciocínio Técnico de Negócio:
     * 1. Verificação de Posse: Garante que apenas o proprietário altere o estado da tarefa.
     * 2. Inversão Booleana: Atribui !$task->is_completed, simplificando a lógica sem necessidade de passar parâmetros adicionais no payload.
     * 3. Redirecionamento: Retorna para a listagem com mensagem dinâmica de feedback.
     */
    public function toggleComplete(Request $request, Task $task): RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $task->update([
            'is_completed' => !$task->is_completed,
        ]);

        $mensagem = $task->is_completed 
            ? 'Tarefa marcada como concluída!' 
            : 'Tarefa reaberta como pendente!';

        return redirect()->route('tasks.index')->with('status', $mensagem);
    }
}

```

---

#### Passo 2: Registro da Rota Customizada (`routes/web.php`)

Adicionamos a rota com o método `PATCH` para a operação de toggle.

* **Origem:** `routes/web.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/routes/web.php`

Substitua todo o conteúdo do arquivo por:

```php
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Autenticação
|--------------------------------------------------------------------------
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
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Rota Específica: Alternar Conclusão (PATCH)
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.toggle');
});

require __DIR__.'/auth.php';

```

> **Raciocínio Técnico:**
> Usamos o verbo `PATCH` porque estamos atualizando apenas uma propriedade parcial do recurso (`is_completed`), mantendo a semântica REST correta.

---

#### Passo 3: Atualização da View de Listagem (`tasks/index.blade.php`)

Transformamos o badge de status em um botão interativo que dispara o formulário de alternância de status.

* **Origem:** `resources/views/tasks/index.blade.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/resources/views/tasks/index.blade.php`

Substitua todo o conteúdo do arquivo por:

```html
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

```

---

### 3. Verificação e Testes Automatizados

Vamos adicionar o teste de alternância de status ao nosso conjunto de testes.

* **Origem:** `tests/Feature/TaskCrudTest.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/tests/Feature/TaskCrudTest.php`

Substitua todo o conteúdo do arquivo por:

```php
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

    public function test_unauthenticated_user_cannot_access_tasks(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertRedirect(route('login'));
    }

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
        ]);
    }

    public function test_user_cannot_update_another_users_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskB = $userB->tasks()->create(['title' => 'Tarefa de B', 'is_completed' => false]);

        $response = $this->actingAs($userA)->put(route('tasks.update', $taskB), [
            'title' => 'Tentativa de invasão',
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_delete_their_own_task(): void
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create(['title' => 'Tarefa para Excluir', 'is_completed' => false]);

        $response = $this->actingAs($user)->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_user_cannot_delete_another_users_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskB = $userB->tasks()->create(['title' => 'Tarefa de B', 'is_completed' => false]);

        $response = $this->actingAs($userA)->delete(route('tasks.destroy', $taskB));

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', [
            'id' => $taskB->id,
        ]);
    }

    /**
     * Teste 8: Usuário pode alternar o status da tarefa (Toggle).
     */
    public function test_user_can_toggle_task_completion(): void
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create(['title' => 'Tarefa Pendente', 'is_completed' => false]);

        // Alterna para Concluída
        $response = $this->actingAs($user)->patch(route('tasks.toggle', $task));
        $response->assertRedirect(route('tasks.index'));
        $this->assertTrue($task->fresh()->is_completed);

        // Alterna de volta para Pendente
        $response = $this->actingAs($user)->patch(route('tasks.toggle', $task));
        $response->assertRedirect(route('tasks.index'));
        $this->assertFalse($task->fresh()->is_completed);
    }
}

```

Execute os testes no terminal:

```bash
php artisan test --filter=TaskCrudTest

```

* **Esperado:**
```text
PASS  Tests\Feature\TaskCrudTest
✓ unauthenticated user cannot access tasks
✓ authenticated user can create task
✓ user only sees their own tasks
✓ user can update their own task
✓ user cannot update another users task
✓ user can delete their own task
✓ user cannot delete another users task
✓ user can toggle task completion

Tests:    8 passed (21 assertions)

```



---
---

### Passo a Passo de Pré-Configuração (Sem mexer no Banco ainda)

---

#### 1. Configurar a `APP_URL` e `ASSET_URL` no `.env`

Abra o arquivo `.env` na raiz de `todo-list` e altere a linha do `APP_URL` com o endereço público exato do seu Codespace:

* **Origem:** `.env` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/.env`

Localize o bloco de ambiente no topo do `.env` e configure-o assim:

```env
APP_NAME=TodoList
APP_ENV=local
APP_KEY=base64:... (mantenha a sua chave gerada)
APP_DEBUG=true
APP_TIMEZONE=America/Recife
APP_URL=https://fantastic-palm-tree-w4g4v46xvj3vj9p-8000.app.github.dev
ASSET_URL=https://fantastic-palm-tree-w4g4v46xvj3vj9p-8000.app.github.dev

```

> **Raciocínio Técnico:**
> Definir o `APP_URL` e o `ASSET_URL` com o domínio real do Codespace faz o Laravel gerar todos os links Blade (`route('register')`, botões e arquivos CSS/JS do Vite) apontando diretamente para a URL pública HTTPS da nuvem em vez de redirecionar para `localhost`.

---

#### 2. Configurar o TrustProxies (Obrigatório para o Codespace)

Como o Codespace usa um proxy reverso HTTPS na frente do servidor, o Laravel precisa confiar nos cabeçalhos enviados pelo proxy para não bloquear redirecionamentos nem gerar URLs `http://` inseguras.

Abra o arquivo `bootstrap/app.php` e adicione o método `trustProxies`:

* **Origem:** `bootstrap/app.php` **[MANUAL / EDIÇÃO]**
* **Localização:** `todo-list/bootstrap/app.php`

Substitua todo o conteúdo do arquivo por:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Confia em todos os proxies reversos do GitHub Codespace
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

```

---

#### 3. Recompilar os Estilos e Limpar os Caches do Laravel

No terminal do Codespace (dentro de `todo-list`), execute:

```bash
php artisan config:clear && php artisan route:clear && php artisan view:clear

```

E em seguida, gere a compilação final dos assets:

```bash
npm run build

```

---

#### 4. Iniciar o Servidor Laravel

Agora inicie o servidor escutando externamente:

```bash
php artisan serve --host=0.0.0.0 --port=8000

```

---

### Verificação

1. Volte à aba do navegador onde está `[https://fantastic-palm-tree-w4g4v46xvj3vj9p-8000.app.github.dev](https://fantastic-palm-tree-w4g4v46xvj3vj9p-8000.app.github.dev)` e pressione `Ctrl + F5` (ou `Cmd + Shift + R`) para recarregar sem cache.
2. A tela inicial deverá renderizar estilizada com o Tailwind CSS perfeitamente formatado.
3. Ao clicar em **Log in** ou **Register**, você será levado para a tela de autenticação estilizada dentro do mesmo domínio `.app.github.dev`, sem tentar redirecionar para o `localhost`.

---

Vai aparecer um link de tarefas e ao clicar vai aparecer um erro.

Esse erro (`SQLSTATE[HY000]: General error: 1 no such table: tasks`) ocorreu porque a tabela física **`tasks`** ainda não foi criada no banco de dados SQLite.

Chegamos exatamente na **PARTE 8: Banco de Dados (FINAL)**. Vamos criar a migration da tabela `tasks`, executá-la e deixar o sistema 100% funcional.

---

## PARTE 8: Banco de Dados — SQLite e Migrations Físicas

---

### 1. Objetivo

Criar a migration da tabela `tasks` com todos os campos necessários (`title`, `description`, `is_completed`, `user_id` com Foreign Key e exclusão em cascata), executar a migração no arquivo `database.sqlite` e testar a aplicação na íntegra.

---

### 2. Implementação Guiada (Comandos + Código + Raciocínio)

#### Passo 1: Criação do Arquivo de Migration

No terminal do Codespace (dentro da pasta `todo-list`), execute:

```bash
php artisan make:migration create_tasks_table

```

> **Raciocínio Técnico:**
> O comando gera um arquivo timestamped em `database/migrations/xxxx_xx_xx_xxxxxx_create_tasks_table.php`. O versionamento de schema do Laravel garante que qualquer ambiente onde o código for clonado possa recriar a estrutura idêntica do banco de dados.

---

#### Passo 2: Definição do Schema da Tabela `tasks`

Abra o arquivo recém-criado dentro da pasta `database/migrations/` (será o último arquivo com o sufixo `_create_tasks_table.php`).

* **Origem:** `database/migrations/xxxx_create_tasks_table.php` **[ARTISAN]**
* **Localização:** `todo-list/database/migrations/[data]_create_tasks_table.php`

Substitua todo o conteúdo do arquivo por:

```php
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

```

---

#### Passo 3: Executar a Migration no Banco SQLite

Execute no terminal:

```bash
php artisan migrate

```

> **Raciocínio Técnico:**
> O Artisan lê os arquivos de migrations pendentes, traduz os métodos do Blueprint para comandos SQL nativos do SQLite e registra a execução na tabela `migrations`.

---

### 3. Feedback de Comandos e Solução de Problemas

* **Ao rodar `php artisan make:migration create_tasks_table`:**
* **Esperado:** `INFO Migration [database/migrations/..._create_tasks_table.php] created successfully.`


* **Ao rodar `php artisan migrate`:**
* **Esperado:**
```text
INFO  Running migrations.
xxxx_xx_xx_xxxxxx_create_tasks_table .................................... DONE

```


* **Se acusar "Database file does not exist":** Execute `touch database/database.sqlite` e rode `php artisan migrate` novamente.



---

### 4. Verificação Final na Interface Web

1. Volte à aba do seu navegador onde deu o erro 500 e recarregue a página (`F5`).
2. A tela **"Minhas Tarefas"** aparecerá limpa com a mensagem *"Você ainda não possui nenhuma tarefa cadastrada"*.
3. Teste o ciclo completo:
* Clique em **+ Nova Tarefa** e crie uma tarefa com título e descrição.
* Clique no botão de status (**○ Pendente**) para alterná-la para **✓ Concluída**.
* Clique em **Editar** para alterar o texto.
* Clique em **Ver** para conferir a tela individual de detalhes.
* Clique em **Excluir** para remover a tarefa.



---


---