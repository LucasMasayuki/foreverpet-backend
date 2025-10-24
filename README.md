# Laravel API Template

> Template Laravel com arquitetura modular robusta

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 11"></a>
<a href="https://www.php.net" target="_blank"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php" alt="PHP 8.2+"></a>
<a href="https://aws.amazon.com/sqs/" target="_blank"><img src="https://img.shields.io/badge/AWS%20SQS-Supported-FF9900?style=for-the-badge&logo=amazon-aws" alt="AWS SQS"></a>
</p>

## 🚀 Sobre Este Template

Este template Laravel fornece uma estrutura completa e pronta para produção de APIs RESTful, seguindo os melhores padrões.

### ✨ Principais Características

- 🏗️ **Arquitetura Modular** - Estrutura organizada em módulos independentes
- 🎯 **Clean Architecture** - UseCases, Repositories, DTOs com Spatie Data
- ⚠️ **Sistema Robusto de Exceções** - Tratamento padronizado de erros
- 📊 **Resources Padronizados** - Formatação consistente de respostas
- 🔄 **Suporte a Filas SQS** - Amazon SQS, Redis, Database
- 📝 **Documentação Completa** - 4 arquivos MD detalhados
- ✅ **Módulo Exemplo** - User module completo como referência
- 🧪 **Pronto para Testes** - Estrutura facilita TDD

## 📋 Documentação

- **[🚀 Getting Started](app/Api/GETTING_STARTED.md)** - Guia de início rápido com tutorial completo
- **[📖 README da API](app/Api/README.md)** - Documentação técnica detalhada
- **[⚙️ Configuração de Ambiente](app/Api/ENV_CONFIGURATION.md)** - Setup de .env e AWS/SQS
- **[📊 Resumo da Implementação](API_TEMPLATE_SUMMARY.md)** - Visão geral do que foi implementado
- **[✅ Checklist](IMPLEMENTATION_CHECKLIST.md)** - Checklist de verificação completo

## 🎯 Início Rápido

```bash
# 1. Instalar dependências
composer install

# 2. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 3. Configurar banco de dados no .env
DB_DATABASE=laravel_api
DB_USERNAME=root
DB_PASSWORD=

# 4. Executar migrations
php artisan migrate

# 5. Instalar Spatie Data (obrigatório)
composer require spatie/laravel-data

# 6. Iniciar servidor
php artisan serve
```

**Acesse:** `http://localhost:8000/api/v1/health`

## 📁 Estrutura do Projeto

```
app/Api/
├── Http/                  # Controllers, Routes, Middlewares
├── Modules/              # Módulos da aplicação
│   └── User/            # Exemplo completo
│       ├── Controllers/
│       ├── Data/        # DTOs (Spatie Data)
│       ├── Repositories/
│       ├── Resource/    # API Resources
│       ├── UseCases/    # Lógica de negócio
│       └── config.php
└── Support/             # Classes compartilhadas
    ├── Contracts/       # Interfaces
    ├── Dictionaries/    # ErrorCode enum
    ├── Exceptions/      # Sistema de exceções
    ├── Helpers/        # TextHelper, UserHelper, Money
    ├── Repository/     # BaseRepository
    └── Traits/         # DataUtilsTrait
```

## 🎨 Padrão de Código

### Controller
```php
public function index(QueryData $query, UseCase $useCase): Resource
{
    $result = $useCase->execute($query);
    return new Resource($result);
}
```

### UseCase (Lógica de Negócio)
```php
class CreateUserUseCase
{
    public function __construct(private UsersRepository $repository) {}

    public function execute(UserData $data): User
    {
        return $this->repository->create($data);
    }
}
```

### Data (DTO com Spatie)
```php
#[MapName(SnakeCaseMapper::class)]
class UserData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        public string $name,
        public string $email,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
        ];
    }
}
```

## 🔧 Configuração AWS/SQS

```env
# Filas
QUEUE_DRIVER=database
QUEUE_DRIVER_SECONDARY=sqs

# AWS
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1

# SQS
SQS_PREFIX=https://sqs.us-east-1.amazonaws.com/your-account-id
SQS_REGION=us-east-1
```

## 📦 Dependências

### Obrigatórias
```bash
composer require spatie/laravel-data
```

### Para Produção (SQS)
```bash
composer require aws/aws-sdk-php
```

## 🧪 Testes

```bash
# Rodar todos os testes
php artisan test

# Com coverage
php artisan test --coverage

# Apenas feature tests
php artisan test --testsuite=Feature
```

## 🎓 Como Criar um Módulo

1. **Crie a estrutura**:
```bash
mkdir -p app/Api/Modules/Product/{Controllers,Data,Repositories,Resource,UseCases}
```

2. **Siga o exemplo do módulo User** em `app/Api/Modules/User/`

3. **Registre no ServiceProvider**:
```php
// app/Api/Support/Providers/ApiServiceProvider.php
protected array $modules = ['user', 'product'];
```

4. **Defina as rotas**:
```php
// app/Api/Http/Routes/api.php
Route::apiResource('products', ProductsController::class);
```

**Veja tutorial completo em:** [Getting Started](app/Api/GETTING_STARTED.md)

## 🏗️ Arquitetura

Este template implementa:

- ✅ **Repository Pattern** - Camada de acesso a dados
- ✅ **UseCase Pattern** - Lógica de negócio isolada
- ✅ **DTO Pattern** - Validação e tipagem com Spatie Data
- ✅ **Resource Pattern** - Formatação de respostas
- ✅ **Exception Handling** - Sistema robusto de erros
- ✅ **Queue System** - SQS, Redis, Database
- ✅ **SOLID Principles** - Código limpo e manutenível

## 🔒 Segurança

- Validações em todos DTOs
- Exceções tratadas globalmente
- Sanitização via Helpers
- Repository Pattern (não expõe Models diretamente)
- Resources controlam dados expostos
- Suporte a rate limiting

## 📊 Formato de Resposta da API

### Sucesso (Collection)
```json
{
  "data": [...],
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1
  }
}
```

### Erro
```json
{
  "code": "not_found",
  "message": "Resource not found",
  "meta": []
}
```

## 🤝 Contribuindo

Este template foi criado para ser reutilizado em novos projetos. Sinta-se livre para:

1. Adicionar novos módulos
2. Melhorar a documentação
3. Adicionar testes
4. Otimizar performance
5. Adicionar novas features

## 📝 Licença

Este template é baseado no Laravel que é open-source sob [MIT license](https://opensource.org/licenses/MIT).

---

## 📚 Laravel Framework

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
