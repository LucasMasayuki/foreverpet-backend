# AWS S3 Storage Integration Guide

## 📦 Overview

Este guia detalha a integração completa com **AWS S3** para upload e gerenciamento de arquivos no ForeverPet Backend.

## ✨ Features

- ✅ **Signed Upload URLs**: URLs pré-assinadas para upload direto do cliente ao S3
- ✅ **Múltiplos Drivers**: Suporte para S3 (produção) e Local (desenvolvimento)
- ✅ **Organização Automática**: Estrutura de pastas por tipo de arquivo e usuário
- ✅ **Upload Direto**: Cliente faz upload direto ao S3 sem passar pelo backend
- ✅ **Segurança**: URLs expiráveis e controle de acesso
- ✅ **Fallback Local**: Sistema funcional mesmo sem configuração S3

---

## 🏗️ Arquitetura

### Interface de Serviço
**`StorageServiceInterface`** define o contrato para todos os serviços de storage:

- `createSignedUploadUrl()` - Gera URL assinada para upload direto
- `upload()` - Upload direto pelo backend
- `delete()` - Remove arquivo do storage
- `getPublicUrl()` - Obtém URL pública de um arquivo
- `exists()` - Verifica se arquivo existe

### Implementações

#### 1. **S3StorageService** (Produção)
- Usa AWS S3 SDK
- Gera Pre-Signed URLs para upload direto
- URLs públicas CDN-friendly
- Controle de ACL e Content-Type

#### 2. **LocalStorageService** (Desenvolvimento)
- Usa disco `public` do Laravel
- URLs locais via `storage_link`
- Ideal para testes

---

## ⚙️ Configuração

### 1. Variáveis de Ambiente

Adicione ao seu `.env`:

```env
# Storage Driver (local ou s3)
STORAGE_DRIVER=s3
AWS_S3_ENABLED=true

# AWS S3 Credentials
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=foreverpet-uploads
AWS_URL=https://foreverpet-uploads.s3.amazonaws.com
AWS_USE_PATH_STYLE_ENDPOINT=false

# Para desenvolvimento local
# STORAGE_DRIVER=local
# AWS_S3_ENABLED=false
```

### 2. Configuração do Filesystem

O arquivo `config/filesystems.php` já está configurado com o disco `s3`:

```php
's3' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    'bucket' => env('AWS_BUCKET'),
    'url' => env('AWS_URL'),
    'endpoint' => env('AWS_ENDPOINT'),
    'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
    'throw' => false,
],
```

### 3. Service Provider

O `StorageServiceProvider` já está registrado em `bootstrap/providers.php`.

---

## 📂 Estrutura de Pastas

Os arquivos são organizados automaticamente:

```
uploads/
└── users/
    └── {userId}/
        ├── profile/              # Fotos de perfil
        ├── pets/
        │   └── {year}/{month}/   # Fotos de pets
        ├── documents/
        │   └── {year}/{month}/   # Documentos
        ├── exams/
        │   └── {year}/{month}/   # Exames
        └── prescriptions/
            └── {year}/{month}/   # Receitas
```

---

## 🚀 Uso no Frontend

### 1. Solicitar URL de Upload

**Endpoint**: `POST /api/v1/rest/Users/CreateSignedUploadUrl`

**Request**:
```json
{
  "extension": "jpg",
  "remote_dir": "users"
}
```

**Response**:
```json
{
  "upload_url": "https://foreverpet.s3.amazonaws.com/uploads/users/123/profile/uuid.jpg?...",
  "method": "PUT",
  "headers": {
    "Content-Type": "image/jpeg"
  },
  "key": "uploads/users/123/profile/uuid.jpg",
  "public_url": "https://foreverpet.s3.amazonaws.com/uploads/users/123/profile/uuid.jpg",
  "expires_in": 3600
}
```

### 2. Upload Direto ao S3

**JavaScript Example**:
```javascript
// 1. Obter signed URL
const response = await fetch('/api/v1/rest/Users/CreateSignedUploadUrl', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    extension: 'jpg',
    remote_dir: 'users'
  })
});

const { upload_url, method, headers, public_url } = await response.json();

// 2. Upload do arquivo diretamente ao S3
const file = document.querySelector('input[type="file"]').files[0];

await fetch(upload_url, {
  method: method,
  headers: headers,
  body: file
});

// 3. Salvar public_url no perfil do usuário
await fetch('/api/v1/rest/Users', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    picture: public_url
  })
});
```

### React Native Example

```typescript
import { launchImageLibrary } from 'react-native-image-picker';
import RNFS from 'react-native-fs';

async function uploadProfilePicture(token: string) {
  // 1. Selecionar imagem
  const result = await launchImageLibrary({
    mediaType: 'photo',
    quality: 0.8,
  });

  if (!result.assets?.[0]) return;

  const asset = result.assets[0];
  const extension = asset.fileName?.split('.').pop() || 'jpg';

  // 2. Obter signed URL
  const signedResponse = await fetch(
    'https://api.foreverpet.com/api/v1/rest/Users/CreateSignedUploadUrl',
    {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        extension,
        remote_dir: 'users'
      })
    }
  );

  const { upload_url, headers, public_url } = await signedResponse.json();

  // 3. Upload ao S3
  await RNFS.uploadFiles({
    toUrl: upload_url,
    files: [{
      name: 'file',
      filename: asset.fileName,
      filepath: asset.uri,
      filetype: asset.type,
    }],
    method: 'PUT',
    headers,
  });

  // 4. Atualizar perfil
  await fetch('https://api.foreverpet.com/api/v1/rest/Users', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      picture: public_url
    })
  });
}
```

---

## 🔐 Segurança

### URLs Assinadas
- ✅ Válidas por 60 minutos
- ✅ Não requer autenticação adicional durante upload
- ✅ Permite upload direto sem expor credenciais AWS

### Controle de Acesso
- ✅ Apenas usuários autenticados podem solicitar URLs
- ✅ Arquivos são públicos (ACL: public-read) após upload
- ✅ Path inclui user ID para isolamento

### Validação
- ✅ Extensões permitidas: `jpg, jpeg, png, gif, webp, pdf, mp4, mov`
- ✅ Diretórios válidos: `users, pets, documents, exams, prescriptions`

---

## 📝 Diretórios Disponíveis

| Diretório | Uso | Path Pattern |
|-----------|-----|--------------|
| `users` | Fotos de perfil | `uploads/users/{userId}/profile/` |
| `pets` | Fotos de pets | `uploads/users/{userId}/pets/{year}/{month}/` |
| `documents` | Documentos gerais | `uploads/users/{userId}/documents/{year}/{month}/` |
| `exams` | Exames veterinários | `uploads/users/{userId}/exams/{year}/{month}/` |
| `prescriptions` | Receitas médicas | `uploads/users/{userId}/prescriptions/{year}/{month}/` |

---

## 🧪 Testing

### Desenvolvimento Local

1. Configure `.env` para usar storage local:
```env
STORAGE_DRIVER=local
AWS_S3_ENABLED=false
```

2. Crie o link simbólico:
```bash
docker exec php-erp php artisan storage:link
```

3. Teste o endpoint:
```bash
curl -X POST http://localhost:8000/api/v1/rest/Users/CreateSignedUploadUrl \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "extension": "jpg",
    "remote_dir": "users"
  }'
```

### Produção com S3

1. Configure credenciais AWS no `.env`

2. Verifique permissões do bucket:
```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "PublicReadGetObject",
      "Effect": "Allow",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": "arn:aws:s3:::foreverpet-uploads/*"
    }
  ]
}
```

3. Configure CORS no bucket:
```json
[
  {
    "AllowedHeaders": ["*"],
    "AllowedMethods": ["GET", "PUT", "POST"],
    "AllowedOrigins": ["*"],
    "ExposeHeaders": ["ETag"]
  }
]
```

---

## 🚨 Troubleshooting

### URL expirada
**Erro**: "Request has expired"
**Solução**: URLs são válidas por 60 minutos. Solicite nova URL.

### CORS Error
**Erro**: "Access-Control-Allow-Origin"
**Solução**: Configure CORS no bucket S3 (ver seção Testing).

### Invalid Credentials
**Erro**: "The AWS Access Key Id you provided does not exist"
**Solução**: Verifique `AWS_ACCESS_KEY_ID` e `AWS_SECRET_ACCESS_KEY` no `.env`.

### Upload Failed
**Erro**: "SignatureDoesNotMatch"
**Solução**:
- Verifique o horário do servidor (deve estar sincronizado)
- Use o método HTTP correto (PUT, não POST)
- Use os headers exatamente como fornecidos

---

## 📊 Logs

O serviço registra todas as operações:

```php
// Sucesso
Log::info('S3 signed URL created', [
    'key' => $key,
    'expires_in' => $expirationMinutes . ' minutes',
]);

// Erro
Log::error('Failed to create S3 signed URL', [
    'error' => $e->getMessage(),
    'path' => $path,
    'filename' => $filename,
]);
```

---

## 🔄 Migration do Sistema Antigo

Se você tem imagens no sistema antigo, crie um comando para migrar:

```php
// app/Console/Commands/MigrateImagesToS3.php
public function handle(StorageServiceInterface $storage)
{
    $users = User::whereNotNull('picture')->get();

    foreach ($users as $user) {
        $oldPath = public_path('uploads/' . $user->picture);

        if (file_exists($oldPath)) {
            $content = file_get_contents($oldPath);
            $filename = basename($user->picture);

            $publicUrl = $storage->upload(
                "uploads/users/{$user->id}/profile",
                $filename,
                $content
            );

            $user->update(['picture' => $publicUrl]);

            $this->info("Migrated: {$user->name}");
        }
    }
}
```

---

## 📚 Referências

- [AWS S3 Documentation](https://docs.aws.amazon.com/s3/)
- [Laravel Filesystem Documentation](https://laravel.com/docs/filesystem)
- [Pre-Signed URLs](https://docs.aws.amazon.com/AmazonS3/latest/userguide/PresignedUrlUploadObject.html)

---

## ✅ Checklist de Implementação

- [x] Interface `StorageServiceInterface`
- [x] Implementação `S3StorageService`
- [x] Implementação `LocalStorageService`
- [x] Service Provider registrado
- [x] Data DTO `CreateSignedUploadUrlData`
- [x] Use Case `CreateSignedUploadUrlUseCase`
- [x] Controller integrado
- [x] Configuração em `config/services.php`
- [x] Documentação completa

---

**Pronto para upload! 🚀**

