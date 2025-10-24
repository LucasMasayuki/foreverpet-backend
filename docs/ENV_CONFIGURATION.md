# Configurações de Variáveis de Ambiente (.env)

Este documento lista todas as variáveis de ambiente necessárias para o funcionamento correto da API.

## 📋 Variáveis Obrigatórias

### Aplicação Base
```env
APP_NAME="Laravel API"
APP_ENV=local
APP_KEY=base64:...  # Gerar com: php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=UTC
```

### Banco de Dados
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_api
DB_USERNAME=root
DB_PASSWORD=
```

### Filas (Queue)
```env
# Driver principal (sync, database, redis, sqs)
QUEUE_DRIVER=database

# Driver secundário para filas específicas
QUEUE_DRIVER_SECONDARY=sqs
```

## ☁️ Configurações AWS/SQS

### Credenciais AWS
```env
AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=my-bucket-name
```

### Configuração SQS
```env
SQS_KEY="${AWS_ACCESS_KEY_ID}"
SQS_SECRET="${AWS_SECRET_ACCESS_KEY}"
SQS_PREFIX=https://sqs.us-east-1.amazonaws.com/123456789012
SQS_QUEUE=default
SQS_REGION="${AWS_DEFAULT_REGION}"
SQS_SUFFIX=
```

### Como Obter as Credenciais AWS

1. Acesse o [AWS Console](https://console.aws.amazon.com/)
2. Navegue para **IAM (Identity and Access Management)**
3. Crie um novo usuário ou use um existente
4. Gere as **Access Keys** (Access Key ID e Secret Access Key)
5. Configure as permissões necessárias para SQS

### Permissões IAM Necessárias para SQS

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "sqs:SendMessage",
        "sqs:ReceiveMessage",
        "sqs:DeleteMessage",
        "sqs:GetQueueAttributes",
        "sqs:GetQueueUrl"
      ],
      "Resource": "arn:aws:sqs:us-east-1:123456789012:*"
    }
  ]
}
```

## 🗄️ Redis (Alternativa ao SQS)

```env
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Configurações de fila Redis
REDIS_QUEUE_CONNECTION=default
REDIS_QUEUE=default
REDIS_QUEUE_RETRY_AFTER=90
```

### Instalar Redis (Ubuntu/Debian)
```bash
sudo apt update
sudo apt install redis-server
sudo systemctl start redis
sudo systemctl enable redis
```

### Instalar extensão PHP Redis
```bash
sudo apt install php-redis
# ou
sudo pecl install redis
```

## 📧 Configuração de E-mail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🔐 Cache e Sessão

```env
CACHE_STORE=redis
CACHE_PREFIX=laravel_api

SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
```

## 📊 Configurações de Log

```env
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug

# Canais disponíveis: single, daily, slack, syslog, errorlog
```

## 🔄 Configurações de Queue por Ambiente

### Desenvolvimento Local
```env
QUEUE_DRIVER=sync  # Executa jobs imediatamente
# ou
QUEUE_DRIVER=database  # Usa tabela jobs
```

### Staging/Testing
```env
QUEUE_DRIVER=database
QUEUE_DRIVER_SECONDARY=redis
```

### Produção
```env
QUEUE_DRIVER=database
QUEUE_DRIVER_SECONDARY=sqs

# Configurações de SQS
SQS_KEY="${AWS_ACCESS_KEY_ID}"
SQS_SECRET="${AWS_SECRET_ACCESS_KEY}"
SQS_PREFIX=https://sqs.us-east-1.amazonaws.com/123456789012
SQS_REGION=us-east-1
```

## 🚀 Exemplo Completo de .env para Produção

```env
# Application
APP_NAME="My API"
APP_ENV=production
APP_KEY=base64:generated-key-here
APP_DEBUG=false
APP_URL=https://api.myapp.com
APP_TIMEZONE=America/Sao_Paulo

# Database
DB_CONNECTION=mysql
DB_HOST=production-db.cluster.us-east-1.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=my_api_prod
DB_USERNAME=api_user
DB_PASSWORD=secure-password-here

# Queue
QUEUE_DRIVER=database
QUEUE_DRIVER_SECONDARY=sqs

# AWS/SQS
AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=my-production-bucket

SQS_KEY="${AWS_ACCESS_KEY_ID}"
SQS_SECRET="${AWS_SECRET_ACCESS_KEY}"
SQS_PREFIX=https://sqs.us-east-1.amazonaws.com/123456789012
SQS_QUEUE=production-default
SQS_REGION=us-east-1

# Redis
REDIS_CLIENT=phpredis
REDIS_HOST=production-redis.cache.amazonaws.com
REDIS_PASSWORD=secure-redis-password
REDIS_PORT=6379

# Cache
CACHE_STORE=redis
CACHE_PREFIX=api_prod

# Session
SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=true

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@myapp.com"
MAIL_FROM_NAME="${APP_NAME}"

# Logs
LOG_CHANNEL=stack
LOG_LEVEL=error
```

## 🔍 Testando Configurações

### Testar Conexão com Banco de Dados
```bash
php artisan migrate:status
```

### Testar Fila
```bash
# Adicionar job na fila
php artisan queue:work --once

# Verificar jobs falhados
php artisan queue:failed
```

### Testar SQS
```bash
# Processar fila SQS
php artisan queue:work sqs_emails --queue=emails --tries=3

# Ver status da fila
aws sqs get-queue-attributes \
  --queue-url https://sqs.us-east-1.amazonaws.com/123456789012/emails \
  --attribute-names All
```

### Testar Redis
```bash
redis-cli ping
# Deve retornar: PONG

# Testar cache
php artisan cache:clear
php artisan config:cache
```

## ⚠️ Segurança

### Nunca commite o arquivo .env!

O arquivo `.env` contém informações sensíveis e **NUNCA** deve ser commitado no Git.

Sempre use `.env.example` como template:

```bash
cp .env.example .env
php artisan key:generate
```

### Variáveis Sensíveis

As seguintes variáveis devem ser tratadas como **ALTAMENTE CONFIDENCIAIS**:

- `APP_KEY`
- `DB_PASSWORD`
- `AWS_ACCESS_KEY_ID`
- `AWS_SECRET_ACCESS_KEY`
- `SQS_KEY`
- `SQS_SECRET`
- `REDIS_PASSWORD`
- `MAIL_PASSWORD`

### Rotação de Credenciais

Recomenda-se rotacionar credenciais AWS a cada 90 dias:

1. Crie novas Access Keys no AWS IAM
2. Atualize o `.env` com as novas credenciais
3. Teste a aplicação
4. Delete as credenciais antigas no AWS IAM

## 📞 Suporte

Para dúvidas sobre configuração:

- **AWS SQS**: [Documentação AWS SQS](https://docs.aws.amazon.com/sqs/)
- **Laravel Queues**: [Documentação Laravel](https://laravel.com/docs/queues)
- **Redis**: [Documentação Redis](https://redis.io/documentation)

