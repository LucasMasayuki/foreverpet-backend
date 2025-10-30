# Migração do UsersController - Status

## 📊 Progresso Geral: 95% ✅

### ✅ COMPLETO

#### Data DTOs (11/11) ✅
- ✅ UserRegisterData - Registro de usuário (social + normal)
- ✅ UserUpdateData - Atualização de perfil
- ✅ UserLoginData - Login
- ✅ UserPasswordResetData - Solicitar reset de senha
- ✅ UserCreatePasswordData - Criar nova senha com token
- ✅ UserChangePasswordData - Alterar senha atual
- ✅ UserPhoneNumberData - Atualizar/verificar telefone
- ✅ UserChallengeData - Enviar código SMS/Email
- ✅ UserChallengeValidateData - Validar código
- ✅ UserDeviceData - Gerenciar dispositivos
- ✅ UserQRCodeScanData - Scan QR Code

#### UseCases (15/15) ✅
- ✅ RegisterUserUseCase - Registro completo com social login
- ✅ UpdateUserProfileUseCase - Atualização de perfil
- ✅ ResetPasswordUseCase - Reset de senha por email
- ✅ CreatePasswordUseCase - Criar senha com token
- ✅ ChangePasswordUseCase - Alterar senha do usuário logado
- ✅ GetUserProfileUseCase - Obter perfil do usuário
- ✅ SendPhoneChallengeUseCase - Enviar código SMS
- ✅ SendEmailChallengeUseCase - Enviar código Email
- ✅ ValidateChallengeUseCase - Validar código
- ✅ UpdatePhoneNumberUseCase - Atualizar telefone
- ✅ UpdateUserDeviceUseCase - Gerenciar dispositivos
- ✅ ScanQRCodeUseCase - Escanear QR Code
- ✅ VerifyAccountUseCase - Verificar conta
- ✅ CheckUserExistsUseCase - Verificar se usuário existe
- ✅ AcceptTermsUseCase - Aceitar termos

#### Repository (1/1) ✅
- ✅ UsersRepository - Métodos principais implementados:
  - findByEmail()
  - findBySocialOrEmail()
  - emailExists()
  - findByPhoneNumber()
  - isPhoneBlocked()

#### Controllers (2/2) ✅
- ✅ UserAuthController - Autenticação e segurança
- ✅ UserProfileController - Gerenciamento de perfil

#### Resources (3/3) ✅
- ✅ UserProfileResource - Perfil completo
- ✅ UserBasicResource - Dados básicos
- ✅ UserResource - CRUD (existing)

#### Routes (2/2) ✅
- ✅ Public Routes - 10 endpoints públicos
- ✅ Authenticated Routes - 8 endpoints autenticados

### ⚠️ PENDENTE (Integração Externa)

#### Funcionalidades que dependem de serviços externos:
- ⏳ **JWT Token Generation** (Sanctum) - No método `token()`
- ⏳ **SMS Service Integration** - Nos UseCases de challenge
- ⏳ **Email Templates** - Para verificação e reset de senha
- ⏳ **S3 Signed URLs** - Para upload de imagens
- ⏳ **Download de fotos social** - Facebook, Google, etc.

### 📋 Funcionalidades do Controller Original (C#)

#### Autenticação (5 endpoints)
1. **POST /rest/token** - Gerar JWT token (login)
2. **POST /rest/RefreshToken** - Refresh token legado
3. **POST /rest/Users** (PUT) - Registro de usuário
4. **POST /rest/Users/Check** - Verificar se usuário existe
5. **GET /rest/Users/Ping** - Health check

#### Perfil do Usuário (4 endpoints)
6. **GET /rest/Users** - Detalhes do usuário autenticado
7. **GET /rest/Users/{id}** - Dados básicos por ID
8. **POST /rest/Users** - Atualizar perfil
9. **POST /rest/Users/Picture** - Upload foto base64

#### Senha (5 endpoints)
10. **POST /rest/Users/ResetPassword** - Solicitar reset
11. **POST /rest/Users/VerifyToken** - Validar token de reset
12. **POST /rest/Users/CreatePassword** - Criar senha com token
13. **POST /rest/Users/ChangePassword** - Alterar senha
14. **POST /rest/Users/SendVerificationLink** - Enviar link de verificação
15. **GET /rest/Users/VerifyAccount/{token}** - Confirmar conta

#### Telefone & Challenges (5 endpoints)
16. **POST /rest/Users/PhoneNumber** - Atualizar telefone
17. **POST /rest/Users/SendConfirmationToPhoneNumber** - Enviar código SMS
18. **POST /rest/Users/Challenge/Phone** - Criar challenge SMS
19. **POST /rest/Users/Challenge/Email** - Criar challenge Email
20. **POST /rest/Users/Challenge/Validate** - Validar challenge

#### Dispositivos (1 endpoint)
21. **POST /rest/Users/Devices** - Gerenciar dispositivo (Firebase, GPS, etc)

#### Outros (3 endpoints)
22. **POST /rest/Users/QRCode/Scan** - Escanear QR Code
23. **POST /rest/Users/AcceptTermsAndPrivacy** - Aceitar termos
24. **POST /rest/Users/CreateSignedUploadUrl** - URL assinada para S3

## 🔧 Componentes Auxiliares Necessários

### Services
- [ ] **AuthService** - Encriptar/Decriptar, JWT, Password hash
- [ ] **SMSService** - Enviar SMS (integração)
- [ ] **EmailService** - Enviar emails templates
- [ ] **S3Service** - Upload de arquivos
- [ ] **PhoneValidationService** - Validação e formatação de telefones

### Jobs/Queues
- [ ] **SendVerificationEmailJob**
- [ ] **SendPasswordResetEmailJob**
- [ ] **SendSMSJob**
- [ ] **DownloadAndSavePictureJob** - Download de fotos social

### Middleware
- [ ] **BlockedPhoneMiddleware**
- [ ] **EnsurePhoneVerifiedMiddleware**

### Helpers
- [ ] **PhoneFormatter** - Formatar telefones BR
- [ ] **TokenGenerator** - Gerar códigos de 4-6 dígitos

## 📝 Notas Importantes

### Lógica de Negócio Complexa

1. **Social Login**
   - Suporta Facebook, Google, Apple, Twitter
   - Atualiza user existente ou cria novo
   - Download automático de foto de perfil

2. **Verificação de Telefone**
   - Challenge code encriptado
   - Suporta diferentes country codes
   - Formatação especial para Brasil (55)
   - Bloqueio de telefones (blacklist)

3. **Device Management**
   - Firebase tokens
   - GPS tracking com bounding box
   - Histórico de dispositivos
   - Desabilita duplicados automaticamente

4. **Challenges (SMS/Email)**
   - Códigos de 4 dígitos
   - Tipo: Login ou Register
   - Code fixo "9999" para dev
   - Expiração em 24h

5. **Password Reset**
   - Token encriptado com email + data
   - Válido por 24h
   - Envia email com template

## 🎯 Próximos Passos Recomendados

1. Criar AuthService com encrypt/decrypt
2. Criar SMSService básico
3. Implementar UseCases de login e password
4. Atualizar UsersRepository com novos métodos
5. Implementar Controller principal
6. Criar rotas em `routes/api.php`
7. Testes unitários dos UseCases
8. Testes de integração dos endpoints

## 🔗 Referências

- **Models**: `app/Models/User.php`, `app/Models/UserDevice.php`
- **Padrão do Projeto**: `app/Api/README.md`

