# Laravel API Template - Estrutura Modular

Este template Laravel segue os padrões com uma arquitetura modular robusta para APIs RESTful.

## 📁 Estrutura do Projeto

```
app/Api/
├── Http/
│   ├── Controllers/       # Controllers base da API
│   ├── Middlewares/       # Middlewares customizados
│   └── Routes/           # Definição de rotas (api.php, public.php)
├── Modules/              # Módulos da aplicação
│   └── User/             # Exemplo de módulo completo
│       ├── Controllers/  # Controllers do módulo
│       ├── Data/         # DTOs usando Spatie Data
│       ├── Repositories/ # Camada de acesso a dados
│       ├── Resource/     # Resources para formatação de resposta
│       ├── Services/     # Lógica de negócio complexa
│       ├── UseCases/     # Casos de uso (1 ação por classe)
│       ├── Enums/        # Enumerações do módulo
│       ├── Tests/        # Testes do módulo
│       └── config.php    # Configurações do módulo
└── Support/              # Classes de suporte compartilhadas
    ├── Contracts/        # Interfaces
    ├── Dictionaries/     # Enums e dicionários
    ├── Enums/           # Enumerações globais
    ├── Exceptions/      # Sistema de exceções customizado
    ├── Helpers/         # Classes helper
    ├── Repository/      # BaseRepository
    ├── Resources/       # BaseResource
    ├── Services/        # Serviços compartilhados
    └── Traits/          # Traits reutilizáveis
```

## 🚀 Principais Características

### 1. Sistema de Exceções Customizado

O template implementa um sistema robusto de tratamento de exceções:

- **CustomApiException**: Interface para exceções customizadas
- **GenericErrorHandling**: Handler genérico para todas as exceções
- **ValidationErrorHandling**: Handler específico para validações
- **Exceções específicas**: BadRequestException, NotFoundException, ForbiddenException, etc.

**Formato de resposta de erro:**
```json
{
  "code": "not_found",
  "message": "Resource not found",
  "meta": []
}
```

### 2. Padrão de Módulos

Cada módulo segue uma estrutura consistente:

#### Controllers
```php
class UsersController extends ApiBaseController
{
    public function index(UsersQueryData $query, GetAllUsersUseCase $useCase): UsersResource
    {
        $users = $useCase->execute($query);
        return new UsersResource($users);
    }
}
```

#### Data (DTOs com Spatie Data)
```php
#[MapName(SnakeCaseMapper::class)]
class UserData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        public int|Optional $id,
        public string $name,
        public string $email,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($context->payload['id'] ?? null)],
        ];
    }
}
```

#### Repositories
```php
class UsersRepository extends BaseRepository implements RepositoryContract
{
    public function model(): string
    {
        return User::class;
    }

    public function query(): Builder
    {
        return $this->makeModel()->newQuery();
    }

    public function getAll(UsersQueryData $filters): LengthAwarePaginator
    {
        return $this->query()
            ->when($filters->search, fn($q, $search) =>
                $q->where('name', 'like', "%{$search}%")
            )
            ->paginate($filters->perPage);
    }
}
```

#### UseCases
```php
class CreateUserUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserData $data): User
    {
        return $this->repository->create($data);
    }
}
```

#### Resources
```php
class UserResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
```

### 3. Configuração de Filas (SQS e outras)

O arquivo `config/queue.php` foi configurado com suporte a:
- **SQS (Amazon Simple Queue Service)**: Para ambientes de produção
- **Database**: Para desenvolvimento
- **Redis**: Alternativa ao SQS
- **Sync**: Para testes

### 4. Helpers e Traits

#### DataUtilsTrait
```php
// Converte DTO para array do modelo
$data->toArrayModel();

// Validações inline
$this->validateRequired('field', $value);
$this->validateBetween('age', $value, 18, 65);
```

#### Money Helper
```php
Money::fromValueToCurrency(10000); // 100.00
Money::fromCurrencyToValue(100.00); // 10000
Money::format(100.50); // R$ 100,50
```

#### TextHelper
```php
TextHelper::onlyAlphanumeric('ABC-123'); // ABC123
TextHelper::onlyNumbers('(11) 9999-9999'); // 11999999999
TextHelper::slugify('Meu Título'); // meu-titulo
```

#### UserHelper
```php
UserHelper::getUserId();
UserHelper::getUserEmail();
UserHelper::getCompanyId();
UserHelper::isAuthenticated();
```

## ⚙️ Configuração

### 1. Registrar o ApiServiceProvider

O provider já está registrado em `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Api\Support\Providers\ApiServiceProvider::class,
];
```

### 2. Variáveis de Ambiente

Adicione ao seu `.env`:

```env
# Queue Configuration
QUEUE_DRIVER=database
QUEUE_DRIVER_SECONDARY=sqs

# SQS Configuration
SQS_KEY=your-aws-access-key
SQS_SECRET=your-aws-secret-key
SQS_PREFIX=https://sqs.us-east-1.amazonaws.com/your-account-id
SQS_QUEUE=default
SQS_REGION=us-east-1

# Redis Queue (alternativa ao SQS)
REDIS_QUEUE_CONNECTION=default
REDIS_QUEUE=default
REDIS_QUEUE_RETRY_AFTER=90
```

### 3. Instalar Dependências

```bash
composer require spatie/laravel-data
composer require aws/aws-sdk-php  # Para SQS
```

## 📝 Como Criar um Novo Módulo

### 1. Criar Estrutura de Pastas

```bash
mkdir -p app/Api/Modules/NomeModulo/{Controllers,Data,Repositories,Resource,Services,UseCases,Tests}
```

### 2. Seguir o Padrão

Use o módulo `User` como referência:

1. **Data**: Criar DTOs para input e query
2. **Repository**: Implementar camada de dados
3. **UseCases**: Um caso de uso por ação
4. **Controller**: Orquestrar os UseCases
5. **Resource**: Formatar resposta da API
6. **config.php**: Configurações específicas

### 3. Registrar Rotas

Edite `app/Api/Http/Routes/api.php`:

```php
Route::apiResource('nome-modulo', NomeModuloController::class);
```

### 4. Registrar no ServiceProvider

Edite `app/Api/Support/Providers/ApiServiceProvider.php`:

```php
protected array $modules = [
    'user',
    'nomeModulo', // adicione aqui
];
```

## 🧪 Testes

Estrutura de testes por módulo:

```php
// tests/Feature/Api/Modules/User/UsersTest.php
use Tests\TestCase;

class UsersTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_list_users()
    {
        $response = $this->getJson('/api/v1/users');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email']
                ],
                'pagination'
            ]);
    }
}
```

## 🔐 Autenticação

Por padrão, as rotas da API usam `auth:sanctum`. Ajuste conforme necessário:

```php
// Para usar Passport
Route::middleware(['auth:api'])->group(function () {
    // rotas
});

// Para rotas públicas (adicione em app/Api/Http/Routes/public.php)
Route::get('/public-endpoint', function () {
    // ...
});
```

## 📊 Padrões de Resposta

### Sucesso (Collection)
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  ],
  "pagination": {
    "total": 100,
    "count": 15,
    "per_page": 15,
    "current_page": 1,
    "total_pages": 7
  }
}
```

### Sucesso (Item único)
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com"
}
```

### Erro de Validação
```json
{
  "code": "unprocessable_entity",
  "message": "Invalid Validation",
  "meta": [
    {
      "field": "email",
      "validations": [
        {
          "type": "Unique",
          "value": []
        }
      ]
    }
  ]
}
```

## 🎯 Boas Práticas

1. **Um UseCase por ação**: GetUser, CreateUser, UpdateUser, etc.
2. **Validação nos Data objects**: Use Spatie Data com validações
3. **Repository para acesso a dados**: Nunca use Model diretamente nos Controllers
4. **Resources para formatação**: Sempre retorne Resources dos Controllers
5. **Exceptions customizadas**: Use as exceções do namespace `App\Api\Support\Exceptions`
6. **snake_case na API**: Use `#[MapName(SnakeCaseMapper::class)]` nos DTOs
7. **Testes por módulo**: Mantenha testes dentro da pasta do módulo

## 🔧 Comandos Úteis

```bash
# Criar migration
php artisan make:migration create_nome_table

# Rodar testes
php artisan test

# Processar filas
php artisan queue:work

# Processar fila específica do SQS
php artisan queue:work sqs_emails --queue=emails

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## 📚 Recursos Adicionais

- [Spatie Laravel Data](https://spatie.be/docs/laravel-data)
- [Laravel Queues](https://laravel.com/docs/queues)
- [Laravel API Resources](https://laravel.com/docs/eloquent-resources)
- [AWS SQS](https://aws.amazon.com/sqs/)

---

