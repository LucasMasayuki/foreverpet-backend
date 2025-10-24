# 🚀 Laravel API Template - START HERE

> Template Laravel simplificado com API v1 única, sem complexidade de múltiplas versões

## ⚡ Quick Start (5 minutos)

```bash
# 1. Instalar dependências
composer install
composer require spatie/laravel-data

# 2. Configurar
cp .env.example .env
php artisan key:generate

# 3. Banco de dados
php artisan migrate

# 4. Testar
php artisan serve
curl http://localhost:8000/api/v1/health
```

## 📍 URLs da API

Todas as rotas começam com `/api/v1/`:

```
http://localhost:8000/api/v1/health       # ✅ Pública
http://localhost:8000/api/v1/version      # ✅ Pública
http://localhost:8000/api/v1/users        # 🔒 Autenticada
```

## 📂 Estrutura Simples

```
app/Api/
├── Http/Routes/
│   ├── api.php       → Rotas autenticadas
│   └── public.php    → Rotas públicas
├── Modules/
│   └── User/         → Exemplo completo
└── Support/          → Helpers, Exceptions, etc
```

## 🎯 Adicionar Nova Rota

### Rota Autenticada
Edite `app/Api/Http/Routes/api.php`:
```php
Route::apiResource('products', ProductsController::class);
```

### Rota Pública
Edite `app/Api/Http/Routes/public.php`:
```php
Route::get('/status', fn() => response()->json(['status' => 'online']));
```

## 📦 Criar Novo Módulo

1. **Copie a estrutura do módulo User**:
```bash
cp -r app/Api/Modules/User app/Api/Modules/Product
```

2. **Registre no ServiceProvider**:
```php
// app/Api/Support/Providers/ApiServiceProvider.php
protected array $modules = ['user', 'product'];
```

3. **Adicione as rotas** em `api.php`

4. **Pronto!** Siga o padrão do User

## 📚 Documentação Completa

1. **[README.md](README.md)** - Overview geral
2. **[app/Api/GETTING_STARTED.md](app/Api/GETTING_STARTED.md)** - Tutorial detalhado
3. **[app/Api/README.md](app/Api/README.md)** - Documentação técnica
4. **[CHANGELOG_V1.md](CHANGELOG_V1.md)** - O que mudou da versão original

## 🔑 Principais Características

- ✅ **API v1 Única** - Sem confusão de versões
- ✅ **Modular** - Cada módulo é independente
- ✅ **Clean Architecture** - UseCases, Repositories, DTOs
- ✅ **Validação Automática** - Spatie Data
- ✅ **Erros Padronizados** - Sistema robusto de exceções
- ✅ **Filas SQS** - Pronto para produção
- ✅ **Documentado** - 5 arquivos de documentação

## 🧪 Exemplo de Teste

```bash
# Criar usuário (em tinker)
php artisan tinker
>>> $user = User::factory()->create();
>>> $token = $user->createToken('test')->plainTextToken;
>>> echo $token;

# Testar API
curl http://localhost:8000/api/v1/users \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

## ⚙️ Configurar SQS (Produção)

```env
# .env
QUEUE_DRIVER_SECONDARY=sqs
SQS_KEY=sua-chave-aws
SQS_SECRET=seu-secret-aws
SQS_PREFIX=https://sqs.us-east-1.amazonaws.com/sua-conta
SQS_REGION=us-east-1
```

```bash
composer require aws/aws-sdk-php
```

## 🎨 Padrão de Código

### Controller → UseCase → Repository

```php
// Controller
public function store(UserData $data, CreateUserUseCase $useCase)
{
    $user = $useCase->execute($data);
    return new UserResource($user);
}

// UseCase
public function execute(UserData $data): User
{
    return $this->repository->create($data);
}

// Repository
public function create(UserData $data): User
{
    return $this->query()->create($data->toArrayModel());
}
```

## 🛠️ Comandos Úteis

```bash
# Desenvolvimento
php artisan serve
php artisan queue:work

# Testes
php artisan test

# Cache (Produção)
php artisan config:cache
php artisan route:cache
```

## 🆘 Problemas Comuns

### Erro: "Class 'Spatie\LaravelData\Data' not found"
```bash
composer require spatie/laravel-data
```

### Erro: Rota não encontrada
```bash
php artisan route:clear
php artisan config:clear
```

### Erro: Validação não funciona
Verifique se o Data tem o método `rules()` implementado.

## ✨ Próximos Passos

1. Ler [GETTING_STARTED.md](app/Api/GETTING_STARTED.md)
2. Explorar o módulo User
3. Criar seu primeiro módulo
4. Configurar autenticação (Sanctum)
5. Escrever testes
6. Deploy!

---

**Dúvidas?** Leia a [documentação completa](app/Api/README.md) ou o [checklist](IMPLEMENTATION_CHECKLIST.md)

**Pronto para começar! 🎉**

