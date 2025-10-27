# Arquitetura de Usuários - ForeverPet

**Data**: 24/10/2025
**Status**: ✅ Definido - Duas Tabelas Distintas

## 🎯 Decisão Arquitetural

### ✅ Abordagem Escolhida: **Duas Tabelas Separadas para Autenticação**

**Separação clara de contextos:**
1. **`users`** - Usuários do aplicativo mobile (donos de pets)
2. **`professional_users`** - Profissionais com acesso ao ERP

```
┌─────────────────────────────────────────────────────────┐
│                    AUTENTICAÇÃO                          │
├──────────────────────────┬──────────────────────────────┤
│         USERS            │    PROFESSIONAL_USERS         │
│   (App - Pet Owners)     │         (ERP)                 │
└──────────────────────────┴──────────────────────────────┘
         │                              │
         │                              │
         ▼                              ▼
   ┌─────────┐                  ┌──────────────┐
   │  Pets   │                  │    Roles &   │
   │ Vaccines│                  │  Permissions │
   │  Meds   │                  │   Management │
   └─────────┘                  └──────────────┘
                                        │
                        ┌───────────────┼───────────────┐
                        ▼               ▼               ▼
                   ┌────────┐    ┌─────────┐    ┌─────────┐
                   │ Admins │    │  Vets   │    │  ONGs   │
                   └────────┘    └─────────┘    └─────────┘
```

## 📊 Estrutura das Tabelas

### 1. **`users`** - Usuários do Aplicativo Mobile

**Contexto**: Aplicativo mobile para donos de pets
**Autenticação**: Laravel Sanctum (API tokens)
**Guard Laravel**: `api`

**Características**:
- Proprietários de pets (usuários finais)
- Login social (Facebook, Google, Apple, Twitter)
- Gerenciam pets, vacinas, medicações, eventos
- Perfil pessoal completo
- Relacionamentos com vets e ONGs (como clientes)

**Campos principais** (já criado na migration existente):
```php
Schema::create('users', function (Blueprint $table) {
    $table->string('id', 50)->primary();
    $table->string('vet_id', 50)->nullable();
    $table->string('ong_id', 50)->nullable();
    $table->string('name');
    $table->string('email');
    $table->string('password')->nullable();
    $table->string('picture', 512)->nullable();
    $table->date('birthdate')->nullable();
    $table->string('gender', 16)->nullable();
    $table->string('phone_number', 32)->nullable();

    // Address fields...
    // OAuth IDs (facebook_id, google_id, apple_id, twitter_id)
    // Terms and conditions
    // Profile management

    $table->foreign('vet_id')->references('id')->on('vets');
    $table->foreign('ong_id')->references('id')->on('ongs');
});
```

**Não precisa de roles/permissions** - usuários finais simples.

---

### 2. **`professional_users`** - Profissionais ERP (NOVA TABELA)

**Contexto**: Sistema ERP para profissionais
**Autenticação**: Laravel Passport (OAuth2) ou Sanctum
**Guard Laravel**: `professional`

**Características**:
- Acesso ao painel administrativo/ERP
- Sistema de roles e permissions (Spatie Permission)
- Múltiplos tipos de profissionais
- Auditoria e segurança reforçada
- 2FA (autenticação de dois fatores)
- Soft deletes

**Estrutura da nova migration**:
```php
Schema::create('professional_users', function (Blueprint $table) {
    $table->id(); // bigint auto-increment

    // Autenticação
    $table->string('email')->unique();
    $table->string('password');
    $table->string('name');

    // Tipo de profissional
    $table->enum('professional_type', [
        'admin',      // Administrador interno
        'vet',        // Veterinário/Clínica
        'ong',        // ONG
        'staff',      // Equipe (atendente, recepcionista)
        'manager'     // Gerente
    ]);

    // Referência ao perfil específico (polymorphic)
    $table->string('professional_id', 50)->nullable();
    $table->string('professional_table')->nullable()->comment('vets, ongs, etc');

    // Status
    $table->enum('status', [
        'active',
        'inactive',
        'suspended',
        'pending_approval'
    ])->default('pending_approval');

    // Segurança
    $table->timestamp('email_verified_at')->nullable();
    $table->boolean('two_factor_enabled')->default(false);
    $table->text('two_factor_secret')->nullable();
    $table->text('two_factor_recovery_codes')->nullable();

    // Auditoria
    $table->timestamp('last_login_at')->nullable();
    $table->string('last_login_ip', 45)->nullable();
    $table->string('last_user_agent')->nullable();

    // Metadados
    $table->json('preferences')->nullable();
    $table->string('theme', 32)->default('light');
    $table->string('language', 5)->default('pt-BR');

    // Timestamps + Soft Delete
    $table->timestamps();
    $table->softDeletes();

    // Indexes
    $table->index('email');
    $table->index('professional_type');
    $table->index('status');
    $table->index(['professional_id', 'professional_table']);
});
```

## 🔐 Sistema de Autenticação

### Configuração: `config/auth.php`

```php
return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        // Guard para usuários do app
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],

        // Guard para profissionais (ERP)
        'professional' => [
            'driver' => 'sanctum', // ou 'passport'
            'provider' => 'professionals',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'professionals' => [
            'driver' => 'eloquent',
            'model' => App\Models\ProfessionalUser::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'professionals' => [
            'provider' => 'professionals',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];
```

## 🎭 Tipos de Profissionais

### 1. **Admin** (Administrador Interno)
- **professional_type**: `admin`
- **professional_id**: `null` (não vinculado a perfil externo)
- **Acesso**: Total ao sistema
- **Roles**: `super-admin`, `admin`

**Uso**: Equipe interna da empresa

---

### 2. **Vet** (Veterinário/Clínica)
- **professional_type**: `vet`
- **professional_id**: ID da tabela `vets`
- **professional_table**: `vets`
- **Acesso**: Módulo de atendimentos, agenda, pets
- **Roles**: `vet`, `vet-manager`, `vet-staff`

**Uso**: Profissionais da saúde animal

**Exemplo**:
```php
$vet = Vet::create(['name' => 'Clínica VetCare', ...]);

$professionalUser = ProfessionalUser::create([
    'email' => 'contato@vetcare.com',
    'password' => Hash::make('senha'),
    'name' => 'Dr. João Silva',
    'professional_type' => 'vet',
    'professional_id' => $vet->id,
    'professional_table' => 'vets',
]);

$professionalUser->assignRole('vet');
```

---

### 3. **ONG** (Organização)
- **professional_type**: `ong`
- **professional_id**: ID da tabela `ongs`
- **professional_table**: `ongs`
- **Acesso**: Portal de adoção, animais, contatos
- **Roles**: `ong-admin`, `ong-member`

**Uso**: ONGs de proteção animal

**Exemplo**:
```php
$ong = Ong::create(['name' => 'ONG Amigo Fiel', ...]);

$professionalUser = ProfessionalUser::create([
    'email' => 'contato@amigofiel.org',
    'password' => Hash::make('senha'),
    'name' => 'Maria Santos',
    'professional_type' => 'ong',
    'professional_id' => $ong->id,
    'professional_table' => 'ongs',
]);

$professionalUser->assignRole('ong-admin');
```

---

### 4. **Staff** (Equipe)
- **professional_type**: `staff`
- **professional_id**: Pode referenciar vet_id ou ong_id (opcional)
- **Acesso**: Limitado (atendimento, recepção)
- **Roles**: `staff`, `receptionist`

**Uso**: Atendentes, recepcionistas de clínicas/ONGs

---

### 5. **Manager** (Gerente)
- **professional_type**: `manager`
- **professional_id**: Pode referenciar múltiplas entidades
- **Acesso**: Dashboards, relatórios, configurações
- **Roles**: `manager`, `business-manager`

**Uso**: Gerentes de negócio, supervisores

## 🔑 Sistema de Roles & Permissions

### Package: Spatie Laravel Permission

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Roles Iniciais

```php
// database/seeders/RoleSeeder.php
Role::create(['name' => 'super-admin', 'guard_name' => 'professional']);
Role::create(['name' => 'admin', 'guard_name' => 'professional']);
Role::create(['name' => 'vet', 'guard_name' => 'professional']);
Role::create(['name' => 'vet-manager', 'guard_name' => 'professional']);
Role::create(['name' => 'vet-staff', 'guard_name' => 'professional']);
Role::create(['name' => 'ong-admin', 'guard_name' => 'professional']);
Role::create(['name' => 'ong-member', 'guard_name' => 'professional']);
Role::create(['name' => 'staff', 'guard_name' => 'professional']);
Role::create(['name' => 'manager', 'guard_name' => 'professional']);
```

### Permissions por Módulo

```php
// Users
Permission::create(['name' => 'users.view', 'guard_name' => 'professional']);
Permission::create(['name' => 'users.create', 'guard_name' => 'professional']);
Permission::create(['name' => 'users.edit', 'guard_name' => 'professional']);
Permission::create(['name' => 'users.delete', 'guard_name' => 'professional']);

// Pets
Permission::create(['name' => 'pets.view', 'guard_name' => 'professional']);
Permission::create(['name' => 'pets.create', 'guard_name' => 'professional']);
Permission::create(['name' => 'pets.edit', 'guard_name' => 'professional']);
Permission::create(['name' => 'pets.delete', 'guard_name' => 'professional']);

// Vets
Permission::create(['name' => 'vets.view', 'guard_name' => 'professional']);
Permission::create(['name' => 'vets.manage', 'guard_name' => 'professional']);

// ONGs
Permission::create(['name' => 'ongs.view', 'guard_name' => 'professional']);
Permission::create(['name' => 'ongs.manage', 'guard_name' => 'professional']);

// Agenda
Permission::create(['name' => 'agenda.view', 'guard_name' => 'professional']);
Permission::create(['name' => 'agenda.manage', 'guard_name' => 'professional']);

// Reports
Permission::create(['name' => 'reports.view', 'guard_name' => 'professional']);
Permission::create(['name' => 'reports.export', 'guard_name' => 'professional']);

// Settings
Permission::create(['name' => 'settings.view', 'guard_name' => 'professional']);
Permission::create(['name' => 'settings.edit', 'guard_name' => 'professional']);
```

### Atribuir Permissions aos Roles

```php
// Super Admin - tudo
$superAdmin = Role::findByName('super-admin', 'professional');
$superAdmin->givePermissionTo(Permission::where('guard_name', 'professional')->get());

// Admin - quase tudo
$admin = Role::findByName('admin', 'professional');
$admin->givePermissionTo([
    'users.view', 'users.edit',
    'pets.view', 'pets.edit',
    'vets.view', 'vets.manage',
    'ongs.view', 'ongs.manage',
    'reports.view', 'reports.export',
]);

// Vet - seu escopo
$vet = Role::findByName('vet', 'professional');
$vet->givePermissionTo([
    'users.view',
    'pets.view', 'pets.create', 'pets.edit',
    'agenda.view', 'agenda.manage',
]);

// ONG - adoções
$ong = Role::findByName('ong-admin', 'professional');
$ong->givePermissionTo([
    'pets.view', 'pets.create', 'pets.edit',
    'ongs.view', 'ongs.manage',
]);
```

## 💻 Models Eloquent

### User Model (App)

```php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'name', 'email', 'password', 'phone_number',
        'vet_id', 'ong_id', // ...
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'birthdate' => 'date',
        'terms_and_conditions_accepted' => 'boolean',
        // ...
    ];

    // Relationships
    public function vet()
    {
        return $this->belongsTo(Vet::class, 'vet_id');
    }

    public function ong()
    {
        return $this->belongsTo(Ong::class, 'ong_id');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'user_id');
    }
}
```

### ProfessionalUser Model (ERP)

```php
// app/Models/ProfessionalUser.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class ProfessionalUser extends Authenticatable
{
    use HasApiTokens, SoftDeletes, HasRoles;

    protected $guard_name = 'professional';
    protected $table = 'professional_users';

    protected $fillable = [
        'email', 'password', 'name',
        'professional_type', 'professional_id', 'professional_table',
        'status', 'theme', 'language', 'preferences',
    ];

    protected $hidden = [
        'password', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'preferences' => 'json',
    ];

    // Polymorphic relationship
    public function professional()
    {
        return $this->morphTo('professional', 'professional_table', 'professional_id');
    }

    // Helpers
    public function isAdmin(): bool
    {
        return $this->professional_type === 'admin';
    }

    public function isVet(): bool
    {
        return $this->professional_type === 'vet';
    }

    public function isOng(): bool
    {
        return $this->professional_type === 'ong';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('professional_type', $type);
    }
}
```

## 🛡️ Middleware de Autenticação

### Middleware para Professional Users

```php
// app/Http/Middleware/AuthenticateProfessional.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateProfessional
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('professional')->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $professional = Auth::guard('professional')->user();

        if ($professional->status !== 'active') {
            return response()->json(['message' => 'Account inactive'], 403);
        }

        return $next($request);
    }
}
```

### Middleware para Role Checking

```php
// app/Http/Middleware/CheckProfessionalRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProfessionalRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user('professional');

        if (!$user || !$user->hasAnyRole($roles)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
```

## 🔀 Rotas de Exemplo

```php
// routes/api.php

// Rotas públicas do APP
Route::post('/register', [AppAuthController::class, 'register']);
Route::post('/login', [AppAuthController::class, 'login']);

// Rotas autenticadas do APP
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [UserController::class, 'show']);
    Route::resource('pets', PetController::class);
    Route::resource('vaccines', VaccineController::class);
});

// Rotas públicas do ERP
Route::prefix('professional')->group(function () {
    Route::post('/login', [ProfessionalAuthController::class, 'login']);
    Route::post('/register', [ProfessionalAuthController::class, 'register']);
});

// Rotas autenticadas do ERP
Route::prefix('professional')->middleware('auth:professional')->group(function () {
    Route::get('/profile', [ProfessionalController::class, 'profile']);

    // Admin only
    Route::middleware('role:super-admin,admin')->group(function () {
        Route::resource('admin/users', AdminUserController::class);
        Route::resource('admin/professionals', AdminProfessionalController::class);
    });

    // Vets
    Route::middleware('role:vet,vet-manager')->group(function () {
        Route::resource('vet/appointments', AppointmentController::class);
        Route::resource('vet/clients', ClientController::class);
    });

    // ONGs
    Route::middleware('role:ong-admin,ong-member')->group(function () {
        Route::resource('ong/adoptions', AdoptionController::class);
        Route::resource('ong/animals', AnimalController::class);
    });
});
```

## ✅ Vantagens desta Abordagem

1. ✅ **Separação Total**: Autenticação completamente isolada
2. ✅ **Segurança**: Diferentes guards, políticas e níveis de acesso
3. ✅ **Simplicidade**: Cada tabela tem seu propósito claro
4. ✅ **Escalabilidade**: Fácil adicionar novos tipos de profissionais
5. ✅ **Performance**: Queries mais rápidas (tabelas menores e focadas)
6. ✅ **Manutenibilidade**: Código mais limpo e organizado
7. ✅ **Laravel Native**: Usa multi-guard nativo do Laravel
8. ✅ **Auditoria**: Logs separados para cada tipo de usuário

## ❌ O que NÃO fazer

❌ Não misturar usuários app com profissionais na mesma tabela
❌ Não usar um único guard para ambos contextos
❌ Não compartilhar tokens de autenticação entre app e ERP
❌ Não dar acesso ERP para usuários app sem conversão explícita

## 📝 Migrations Necessárias

### ✅ Já Criadas
- `users` (app users) - ✅ Completa
- `admin_users` - ✅ (pode ser depreciada e consolidada em professional_users)
- `vets` - ✅ Completa
- `ongs` - ✅ Completa

### 🆕 A Criar
1. **`professional_users`** - Nova tabela de autenticação ERP
2. **Spatie Permission Tables** - roles, permissions, model_has_roles, etc.

### 📦 Depreciar (Opcional)
- `admin_users` - Migrar dados para `professional_users` com type='admin'

## 🚀 Plano de Implementação

### Fase 1: Criar Estrutura (Agora) ✅
- [ ] Criar migration `professional_users`
- [ ] Instalar Spatie Permission
- [ ] Criar migrations de roles/permissions
- [ ] Atualizar `config/auth.php`

### Fase 2: Models & Seeders
- [ ] Criar `ProfessionalUser` model
- [ ] Atualizar `User` model (remover campos ERP se houver)
- [ ] Criar seeders de roles e permissions
- [ ] Criar seeder de professional users de teste

### Fase 3: Controllers & Auth
- [ ] Criar `ProfessionalAuthController`
- [ ] Criar middleware `AuthenticateProfessional`
- [ ] Criar middleware `CheckProfessionalRole`
- [ ] Implementar rotas ERP

### Fase 4: Migração de Dados (Se necessário)
- [ ] Migrar `admin_users` para `professional_users`
- [ ] Vincular vets e ongs a professional_users
- [ ] Depreciar tabela `admin_users`

### Fase 5: Testes
- [ ] Unit tests para ProfessionalUser model
- [ ] Feature tests para autenticação
- [ ] Tests de autorização (roles/permissions)
- [ ] Tests de integração

## 🎯 Próximo Passo

**Posso começar criando a migration `professional_users` e instalando o Spatie Permission?**
