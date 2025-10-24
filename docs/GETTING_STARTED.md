# Guia de Início Rápido

Este guia irá ajudá-lo a começar a usar o template Laravel API rapidamente.

## 🎯 Pré-requisitos

- PHP 8.2 ou superior
- Composer
- MySQL/PostgreSQL ou SQLite
- Redis (opcional, mas recomendado)
- AWS Account (para usar SQS em produção)

## 📦 Instalação

### 1. Instalar Dependências

```bash
composer install
npm install && npm run build
```

### 2. Configurar Ambiente

```bash
# Copiar arquivo de configuração
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 3. Configurar Banco de Dados

Edite o arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_api
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Executar Migrations

```bash
php artisan migrate
```

### 5. Instalar Spatie Laravel Data

```bash
composer require spatie/laravel-data
```

### 6. (Opcional) Configurar AWS SQS

Se for usar SQS em produção:

```bash
composer require aws/aws-sdk-php
```

Edite o `.env`:
```env
QUEUE_DRIVER_SECONDARY=sqs
SQS_KEY=your-aws-key
SQS_SECRET=your-aws-secret
SQS_PREFIX=https://sqs.us-east-1.amazonaws.com/your-account-id
SQS_REGION=us-east-1
```

## 🚀 Executando o Projeto

### Servidor de Desenvolvimento

```bash
php artisan serve
```

A API estará disponível em: `http://localhost:8000`

### Processar Filas

Em outro terminal:

```bash
php artisan queue:work
```

## 📝 Primeiro Teste

### 1. Testar Health Check

```bash
curl http://localhost:8000/api/v1/health
```

Resposta esperada:
```json
{
  "status": "ok",
  "timestamp": "2025-10-21T12:00:00+00:00"
}
```

### 2. Criar um Usuário (exemplo)

Primeiro, você precisa autenticar. Para desenvolvimento, você pode:

**Opção A: Criar usuário via Tinker**
```bash
php artisan tinker
```

```php
$user = User::factory()->create([
    'email' => 'test@example.com',
    'password' => bcrypt('password')
]);

$token = $user->createToken('test-token')->plainTextToken;
echo $token;
```

**Opção B: Usar rota não autenticada**

Modifique temporariamente `app/Api/Http/Routes/api.php`:

```php
// Fora do middleware auth
Route::post('/users', [UsersController::class, 'store']);
```

```bash
curl -X POST http://localhost:8000/api/v1/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 3. Listar Usuários (autenticado)

```bash
curl http://localhost:8000/api/v1/users \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 🏗️ Criando Seu Primeiro Módulo

Vamos criar um módulo de "Produtos" como exemplo:

### 1. Criar Estrutura de Diretórios

```bash
mkdir -p app/Api/Modules/Product/{Controllers,Data,Repositories,Resource,Services,UseCases,Tests}
```

### 2. Criar o Model e Migration

```bash
php artisan make:model Product -m
```

Edite a migration:
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->integer('stock')->default(0);
    $table->timestamps();
    $table->softDeletes();
});
```

Execute:
```bash
php artisan migrate
```

### 3. Criar o Data (DTO)

`app/Api/Modules/Product/Data/ProductData.php`:

```php
<?php

namespace App\Api\Modules\Product\Data;

use App\Api\Support\Contracts\DataSerializer;
use App\Api\Support\Traits\DataUtilsTrait;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class ProductData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        #[FromRouteParameter('productId')]
        public int|Optional $id,
        public string $name,
        public string|Optional|null $description,
        public float $price,
        public int $stock = 0,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['integer', 'min:0'],
        ];
    }
}
```

### 4. Criar o Repository

`app/Api/Modules/Product/Repositories/ProductsRepository.php`:

```php
<?php

namespace App\Api\Modules\Product\Repositories;

use App\Api\Support\Contracts\RepositoryContract;
use App\Api\Support\Repository\BaseRepository;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductsRepository extends BaseRepository implements RepositoryContract
{
    public function model(): string
    {
        return Product::class;
    }

    public function query(): Builder
    {
        return $this->makeModel()->newQuery();
    }

    public function getAll()
    {
        return $this->query()->paginate(15);
    }
}
```

### 5. Criar Use Cases

`app/Api/Modules/Product/UseCases/GetAllProductsUseCase.php`:

```php
<?php

namespace App\Api\Modules\Product\UseCases;

use App\Api\Modules\Product\Repositories\ProductsRepository;

class GetAllProductsUseCase
{
    public function __construct(
        private ProductsRepository $repository
    ) {}

    public function execute()
    {
        return $this->repository->getAll();
    }
}
```

### 6. Criar Controller

`app/Api/Modules/Product/Controllers/ProductsController.php`:

```php
<?php

namespace App\Api\Modules\Product\Controllers;

use App\Api\Http\Controllers\ApiBaseController;
use App\Api\Modules\Product\Resource\ProductsResource;
use App\Api\Modules\Product\UseCases\GetAllProductsUseCase;

class ProductsController extends ApiBaseController
{
    public function index(GetAllProductsUseCase $useCase)
    {
        $products = $useCase->execute();
        return new ProductsResource($products);
    }
}
```

### 7. Criar Resource

`app/Api/Modules/Product/Resource/ProductResource.php`:

```php
<?php

namespace App\Api\Modules\Product\Resource;

use App\Api\Support\Resources\BaseResource;

class ProductResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
```

`app/Api/Modules/Product/Resource/ProductsResource.php`:

```php
<?php

namespace App\Api\Modules\Product\Resource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsResource extends ResourceCollection
{
    public $collects = ProductResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'pagination' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
            ],
        ];
    }
}
```

### 8. Registrar Rotas

Edite `app/Api/Http/Routes/api.php`:

```php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('users', \App\Api\Modules\User\Controllers\UsersController::class);
    Route::apiResource('products', \App\Api\Modules\Product\Controllers\ProductsController::class);
});
```

### 9. Registrar no Service Provider

Edite `app/Api/Support/Providers/ApiServiceProvider.php`:

```php
protected array $modules = [
    'user',
    'product',
];
```

### 10. Testar

```bash
# Criar alguns produtos via Tinker
php artisan tinker
```

```php
Product::create([
    'name' => 'Produto Teste',
    'description' => 'Descrição do produto',
    'price' => 99.90,
    'stock' => 10
]);
```

```bash
# Listar produtos
curl http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 🧪 Executando Testes

### Criar um Teste

```bash
php artisan make:test Api/Modules/Product/ProductsTest
```

Exemplo de teste:

```php
<?php

namespace Tests\Feature\Api\Modules\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        $user = User::factory()->create();
        Product::factory()->count(5)->create();

        $response = $this->actingAs($user)
            ->getJson('/api/v1/products');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'price']
                ],
                'pagination'
            ]);
    }
}
```

### Executar Testes

```bash
php artisan test
```

## 📚 Próximos Passos

1. **Leia a documentação completa**: `app/Api/README.md`
2. **Configure variáveis de ambiente**: `app/Api/ENV_CONFIGURATION.md`
3. **Explore o módulo User**: Veja como está implementado
4. **Crie seus próprios módulos**: Siga o padrão estabelecido
5. **Configure autenticação**: Laravel Sanctum ou Passport
6. **Configure filas SQS**: Para produção

## ❓ Problemas Comuns

### Erro: "Class 'Spatie\LaravelData\Data' not found"

```bash
composer require spatie/laravel-data
```

### Erro: "Queue driver [sqs] is not supported"

```bash
composer require aws/aws-sdk-php
```

### Erro: "SQLSTATE[HY000] [2002] Connection refused"

Verifique se o MySQL/PostgreSQL está rodando e as credenciais no `.env` estão corretas.

### Rotas não encontradas

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## 🆘 Suporte

- Documentação completa: `app/Api/README.md`
- Configurações avançadas: `app/Api/ENV_CONFIGURATION.md`
- Exemplo prático: Módulo `User` em `app/Api/Modules/User/`

---

**Boa codificação! 🚀**

