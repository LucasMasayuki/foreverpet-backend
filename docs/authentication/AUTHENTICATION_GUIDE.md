# 🔐 Guia de Autenticação - JWT & OAuth

## 📋 Visão Geral

O sistema ForeverPet Backend implementa autenticação JWT usando **Laravel Sanctum** com suporte completo a:

✅ Login tradicional (username/password)
✅ Social Login OAuth (Facebook, Google, Apple, Twitter)
✅ Tokens JWT stateless
✅ Suporte a múltiplos dispositivos

---

## 🔑 Endpoints de Autenticação

### 1️⃣ Login Tradicional

**POST** `/api/rest/token`

Login com email/telefone e senha. Retorna um token JWT.

**Request:**
```json
{
  "username": "usuario@example.com",  // ou telefone: "11987654321"
  "password": "senha123"
}
```

**Response:**
```json
{
  "access_token": "1|laravel_sanctum_xxxxxxxxxxxxxxx",
  "token_type": "Bearer",
  "user": {
    "id": "uuid-here",
    "name": "João Silva",
    "email": "usuario@example.com",
    "picture": "https://...",
    "phone_number": "(11) 98765-4321",
    "phone_number_confirmed": true,
    "status": 1,
    "register_complete": true
  }
}
```

**Exemplo cURL:**
```bash
curl -X POST http://localhost/api/rest/token \
  -H "Content-Type: application/json" \
  -d '{
    "username": "joao@example.com",
    "password": "senha123"
  }'
```

---

### 2️⃣ Social Login (OAuth)

**POST** `/api/rest/auth/social`

Login ou registro automático com provedores sociais.

**Suporte a:**
- 🔵 Facebook
- 🔴 Google
- ⚫ Apple
- 🐦 Twitter

**Request:**
```json
{
  "provider": "google",           // facebook, google, apple, twitter
  "provider_id": "123456789",     // ID do usuário no provider
  "email": "usuario@gmail.com",   // opcional
  "name": "João Silva",           // obrigatório
  "picture": "https://...",       // opcional
  "access_token": "oauth_token"   // opcional (para validação futura)
}
```

**Response:**
```json
{
  "access_token": "1|laravel_sanctum_xxxxxxxxxxxxxxx",
  "token_type": "Bearer",
  "is_new_user": false,  // true se acabou de criar a conta
  "user": {
    "id": "uuid-here",
    "name": "João Silva",
    "email": "usuario@gmail.com",
    "picture": "https://lh3.googleusercontent.com/...",
    "google_id": "123456789",
    "status": 1
  }
}
```

**Exemplo cURL:**
```bash
curl -X POST http://localhost/api/rest/auth/social \
  -H "Content-Type: application/json" \
  -d '{
    "provider": "facebook",
    "provider_id": "fb_12345",
    "email": "joao@example.com",
    "name": "João Silva",
    "picture": "https://graph.facebook.com/..."
  }'
```

---

### 3️⃣ Registro Manual

**PUT** `/api/rest/Users`

Criar uma nova conta com email e senha.

**Request:**
```json
{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "senha123",
  "phone_number": "11987654321",
  "phone_number_country_code": "55"
}
```

**Response:**
```json
{
  "is_new": true,
  "user": { ... }
}
```

---

## 🔒 Usando o Token JWT

### Headers de Autenticação

Todas as rotas protegidas requerem o header `Authorization`:

```http
Authorization: Bearer 1|laravel_sanctum_xxxxxxxxxxxxxxx
```

### Exemplo de Request Autenticado

```bash
# Obter perfil do usuário
curl -X GET http://localhost/api/rest/Users \
  -H "Authorization: Bearer 1|laravel_sanctum_xxxxxxxxxxxxxxx"
```

---

## 🔄 Fluxo de Autenticação

### Login Tradicional

```
1. Usuário envia email/telefone + senha
   ↓
2. Sistema valida credenciais
   ↓
3. Sistema gera token JWT (Sanctum)
   ↓
4. Retorna access_token + dados do usuário
   ↓
5. Cliente armazena token
   ↓
6. Cliente usa token em todas as requisições
```

### Social Login (OAuth)

```
1. App abre WebView/SDK do provider (Facebook, Google, etc)
   ↓
2. Usuário autoriza o app
   ↓
3. Provider retorna: provider_id, email, name, picture
   ↓
4. App envia dados para /rest/auth/social
   ↓
5. Sistema busca usuário por provider_id
   ├── Encontrou → faz login
   └── Não encontrou → cria novo usuário
   ↓
6. Sistema gera token JWT
   ↓
7. Retorna access_token + is_new_user + dados
```

---

## 🎯 Lógica de Negócio

### Login Tradicional

✅ Aceita email OU telefone no campo `username`
✅ Verifica se telefone está bloqueado
✅ Valida senha com Hash
✅ Atualiza `last_login_at` e `last_visit_at`
✅ Gera token com scope `api:access`

### Social Login

✅ **Busca por provider_id primeiro**
✅ **Se não encontrar**, busca por email
✅ **Se email existir**, vincula conta social
✅ **Se não existir**, cria novo usuário
✅ Usuários sociais têm `password = 'USE_OTP'`
✅ Foto do perfil é atualizada automaticamente
✅ Aceita termos e privacidade automaticamente
✅ Status = 1 (Confirmed) imediatamente

### Vinculação de Contas

Se um usuário criar conta com email e depois fizer login social com o mesmo email:

```php
1. Existe user com email "joao@gmail.com"
2. Login social Google com email "joao@gmail.com"
3. Sistema vincula google_id ao usuário existente
4. Usuário agora pode logar com senha OU Google
```

---

## 🛡️ Segurança

### Validações

- ✅ Senha mínima de 8 caracteres
- ✅ Email válido (formato)
- ✅ Provider deve ser: facebook, google, apple, twitter
- ✅ Bloqueio de telefones (blacklist)
- ✅ Usuários removidos/optedOut não podem logar

### Proteções

- ✅ Passwords são hasheados com `bcrypt`
- ✅ Tokens são únicos por dispositivo
- ✅ Múltiplos tokens por usuário (multi-device)
- ✅ Tokens podem ser revogados
- ✅ Rate limiting (Laravel padrão)

### Revogação de Token

```bash
# Revogar token atual (logout)
curl -X POST http://localhost/api/rest/logout \
  -H "Authorization: Bearer {token}"
```

```php
// No backend (futuro)
$user->tokens()->delete(); // Revoga TODOS os tokens
$user->currentAccessToken()->delete(); // Revoga apenas o atual
```

---

## 📱 Integração com Mobile

### Flutter/React Native

```dart
// 1. Login
final response = await http.post(
  Uri.parse('https://api.foreverpet.com/rest/token'),
  body: json.encode({
    'username': 'joao@example.com',
    'password': 'senha123',
  }),
);

final data = json.decode(response.body);
final token = data['access_token'];

// 2. Salvar token
await storage.write(key: 'jwt_token', value: token);

// 3. Usar em requests
final profileResponse = await http.get(
  Uri.parse('https://api.foreverpet.com/rest/Users'),
  headers: {'Authorization': 'Bearer $token'},
);
```

### Social Login com Flutter

```dart
// Facebook Login
import 'package:flutter_facebook_auth/flutter_facebook_auth.dart';

final LoginResult result = await FacebookAuth.instance.login();

if (result.status == LoginStatus.success) {
  final userData = await FacebookAuth.instance.getUserData();

  // Enviar para backend
  final response = await http.post(
    Uri.parse('https://api.foreverpet.com/rest/auth/social'),
    body: json.encode({
      'provider': 'facebook',
      'provider_id': userData['id'],
      'email': userData['email'],
      'name': userData['name'],
      'picture': userData['picture']['data']['url'],
    }),
  );
}
```

---

## 🧪 Testes

### Testar Login

```bash
# 1. Criar usuário
curl -X PUT http://localhost/api/rest/Users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Teste User",
    "email": "teste@example.com",
    "password": "senha123"
  }'

# 2. Fazer login
curl -X POST http://localhost/api/rest/token \
  -H "Content-Type: application/json" \
  -d '{
    "username": "teste@example.com",
    "password": "senha123"
  }'

# 3. Usar token (copie o access_token da resposta)
TOKEN="1|laravel_sanctum_xxxxxxxx"

curl -X GET http://localhost/api/rest/Users \
  -H "Authorization: Bearer $TOKEN"
```

### Testar Social Login

```bash
curl -X POST http://localhost/api/rest/auth/social \
  -H "Content-Type: application/json" \
  -d '{
    "provider": "google",
    "provider_id": "google_123456",
    "email": "teste@gmail.com",
    "name": "Teste Google"
  }'
```

---

## ⚙️ Configuração

### .env

```env
# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,localhost:3000
SESSION_DRIVER=cookie

# OAuth (se for validar tokens dos providers)
FACEBOOK_APP_ID=your_app_id
FACEBOOK_APP_SECRET=your_app_secret

GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret

APPLE_CLIENT_ID=your_client_id
APPLE_CLIENT_SECRET=your_client_secret
```

### Middleware

Rotas autenticadas usam `auth:sanctum`:

```php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/rest/Users', [UserProfileController::class, 'show']);
    // ...
});
```

---

## 📊 Banco de Dados

### Tabela: personal_access_tokens

Sanctum cria automaticamente uma tabela para armazenar tokens:

```sql
id              bigint
tokenable_type  varchar  (App\Models\User)
tokenable_id    varchar  (user_id)
name            varchar  (api-token)
token           varchar  (hashed token)
abilities       text     (["api:access"])
last_used_at    timestamp
expires_at      timestamp (null = never expires)
created_at      timestamp
updated_at      timestamp
```

### Consultas Úteis

```sql
-- Ver tokens ativos de um usuário
SELECT * FROM personal_access_tokens
WHERE tokenable_id = 'user-uuid-here';

-- Revogar todos os tokens de um usuário
DELETE FROM personal_access_tokens
WHERE tokenable_id = 'user-uuid-here';

-- Ver últimos logins
SELECT name, email, last_login_at
FROM users
ORDER BY last_login_at DESC
LIMIT 10;
```

---

## 🎉 Pronto!

A autenticação JWT com OAuth está **100% funcional**!

### ✅ Implementado:
- Login com username/password
- Social Login (4 providers)
- Geração de tokens JWT
- Vinculação de contas
- Multi-device support
- Segurança robusta

### 📝 Documentos Relacionados:
- `USER_MODULE_COMPLETED.md` - Módulo User completo
- `MIGRATION_PROGRESS.md` - Progresso geral
- `app/Api/Modules/User/MIGRATION_STATUS.md` - Status detalhado

---

**Última Atualização**: 28/10/2025
**Versão**: 1.0.0

