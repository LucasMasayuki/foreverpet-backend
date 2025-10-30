# 📱 Exemplos de Integração Mobile - JWT & OAuth

## 🎯 Guia Prático para Desenvolvedores Mobile

Este documento contém exemplos práticos de código para integração com a API ForeverPet Backend.

---

## 🔧 Flutter / Dart

### 1. Setup Inicial

```dart
// pubspec.yaml
dependencies:
  http: ^1.1.0
  shared_preferences: ^2.2.2
  flutter_facebook_auth: ^6.0.3
  google_sign_in: ^6.1.5
  sign_in_with_apple: ^5.0.0
```

### 2. Service de Autenticação

```dart
// lib/services/auth_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class AuthService {
  static const String baseUrl = 'https://api.foreverpet.com/v1/rest';

  // Login Normal
  Future<Map<String, dynamic>> login(String username, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/token'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode({
        'username': username,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      await _saveToken(data['access_token']);
      return data;
    } else {
      throw Exception('Login failed: ${response.body}');
    }
  }

  // Social Login
  Future<Map<String, dynamic>> socialLogin({
    required String provider,
    required String providerId,
    required String email,
    required String name,
    String? picture,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/social'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode({
        'provider': provider,
        'provider_id': providerId,
        'email': email,
        'name': name,
        'picture': picture,
      }),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      await _saveToken(data['access_token']);
      return data;
    } else {
      throw Exception('Social login failed: ${response.body}');
    }
  }

  // Salvar Token
  Future<void> _saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('jwt_token', token);
  }

  // Obter Token
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('jwt_token');
  }

  // Request Autenticado
  Future<http.Response> authenticatedRequest(String endpoint) async {
    final token = await getToken();
    return await http.get(
      Uri.parse('$baseUrl/$endpoint'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
    );
  }

  // Logout
  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('jwt_token');
  }
}
```

### 3. Login com Google

```dart
// lib/services/google_auth_service.dart
import 'package:google_sign_in/google_sign_in.dart';
import 'auth_service.dart';

class GoogleAuthService {
  final GoogleSignIn _googleSignIn = GoogleSignIn(
    scopes: ['email', 'profile'],
  );
  final AuthService _authService = AuthService();

  Future<Map<String, dynamic>?> signInWithGoogle() async {
    try {
      final GoogleSignInAccount? account = await _googleSignIn.signIn();

      if (account != null) {
        // Enviar para backend
        return await _authService.socialLogin(
          provider: 'google',
          providerId: account.id,
          email: account.email,
          name: account.displayName ?? 'User',
          picture: account.photoUrl,
        );
      }
    } catch (error) {
      print('Google Sign In Error: $error');
    }
    return null;
  }

  Future<void> signOut() async {
    await _googleSignIn.signOut();
  }
}
```

### 4. Login com Facebook

```dart
// lib/services/facebook_auth_service.dart
import 'package:flutter_facebook_auth/flutter_facebook_auth.dart';
import 'auth_service.dart';

class FacebookAuthService {
  final AuthService _authService = AuthService();

  Future<Map<String, dynamic>?> signInWithFacebook() async {
    try {
      final LoginResult result = await FacebookAuth.instance.login();

      if (result.status == LoginStatus.success) {
        final userData = await FacebookAuth.instance.getUserData();

        // Enviar para backend
        return await _authService.socialLogin(
          provider: 'facebook',
          providerId: userData['id'],
          email: userData['email'] ?? '',
          name: userData['name'] ?? 'User',
          picture: userData['picture']?['data']?['url'],
        );
      }
    } catch (error) {
      print('Facebook Sign In Error: $error');
    }
    return null;
  }

  Future<void> signOut() async {
    await FacebookAuth.instance.logOut();
  }
}
```

### 5. Tela de Login

```dart
// lib/screens/login_screen.dart
import 'package:flutter/material.dart';
import '../services/auth_service.dart';
import '../services/google_auth_service.dart';
import '../services/facebook_auth_service.dart';

class LoginScreen extends StatefulWidget {
  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _authService = AuthService();
  final _googleAuth = GoogleAuthService();
  final _facebookAuth = FacebookAuthService();

  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();

  bool _isLoading = false;

  // Login Normal
  Future<void> _handleLogin() async {
    setState(() => _isLoading = true);

    try {
      final result = await _authService.login(
        _emailController.text,
        _passwordController.text,
      );

      // Navigate to home
      Navigator.pushReplacementNamed(context, '/home');
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Login failed: $e')),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  // Login com Google
  Future<void> _handleGoogleLogin() async {
    setState(() => _isLoading = true);

    try {
      final result = await _googleAuth.signInWithGoogle();

      if (result != null) {
        Navigator.pushReplacementNamed(context, '/home');
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Google login failed: $e')),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  // Login com Facebook
  Future<void> _handleFacebookLogin() async {
    setState(() => _isLoading = true);

    try {
      final result = await _facebookAuth.signInWithFacebook();

      if (result != null) {
        Navigator.pushReplacementNamed(context, '/home');
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Facebook login failed: $e')),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: EdgeInsets.all(20),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            // Email
            TextField(
              controller: _emailController,
              decoration: InputDecoration(labelText: 'Email'),
            ),
            SizedBox(height: 10),

            // Password
            TextField(
              controller: _passwordController,
              obscureText: true,
              decoration: InputDecoration(labelText: 'Senha'),
            ),
            SizedBox(height: 20),

            // Login Button
            ElevatedButton(
              onPressed: _isLoading ? null : _handleLogin,
              child: _isLoading
                ? CircularProgressIndicator()
                : Text('Login'),
            ),
            SizedBox(height: 20),

            // Divider
            Text('OU'),
            SizedBox(height: 20),

            // Google Button
            ElevatedButton.icon(
              onPressed: _isLoading ? null : _handleGoogleLogin,
              icon: Icon(Icons.g_mobiledata),
              label: Text('Login com Google'),
            ),
            SizedBox(height: 10),

            // Facebook Button
            ElevatedButton.icon(
              onPressed: _isLoading ? null : _handleFacebookLogin,
              icon: Icon(Icons.facebook),
              label: Text('Login com Facebook'),
            ),
          ],
        ),
      ),
    );
  }
}
```

---

## 🍎 iOS / Swift

### 1. Service de Autenticação

```swift
// AuthService.swift
import Foundation

class AuthService {
    static let shared = AuthService()
    let baseURL = "https://api.foreverpet.com/v1/rest"

    // Login Normal
    func login(username: String, password: String, completion: @escaping (Result<AuthResponse, Error>) -> Void) {
        let url = URL(string: "\(baseURL)/token")!
        var request = URLRequest(url: url)
        request.httpMethod = "POST"
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")

        let body = ["username": username, "password": password]
        request.httpBody = try? JSONSerialization.data(withJSONObject: body)

        URLSession.shared.dataTask(with: request) { data, response, error in
            if let error = error {
                completion(.failure(error))
                return
            }

            guard let data = data else {
                completion(.failure(NSError(domain: "", code: -1, userInfo: nil)))
                return
            }

            do {
                let authResponse = try JSONDecoder().decode(AuthResponse.self, from: data)
                self.saveToken(authResponse.accessToken)
                completion(.success(authResponse))
            } catch {
                completion(.failure(error))
            }
        }.resume()
    }

    // Social Login
    func socialLogin(provider: String, providerId: String, email: String, name: String, picture: String?, completion: @escaping (Result<AuthResponse, Error>) -> Void) {
        let url = URL(string: "\(baseURL)/auth/social")!
        var request = URLRequest(url: url)
        request.httpMethod = "POST"
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")

        var body: [String: Any] = [
            "provider": provider,
            "provider_id": providerId,
            "email": email,
            "name": name
        ]
        if let picture = picture {
            body["picture"] = picture
        }

        request.httpBody = try? JSONSerialization.data(withJSONObject: body)

        URLSession.shared.dataTask(with: request) { data, response, error in
            if let error = error {
                completion(.failure(error))
                return
            }

            guard let data = data else {
                completion(.failure(NSError(domain: "", code: -1, userInfo: nil)))
                return
            }

            do {
                let authResponse = try JSONDecoder().decode(AuthResponse.self, from: data)
                self.saveToken(authResponse.accessToken)
                completion(.success(authResponse))
            } catch {
                completion(.failure(error))
            }
        }.resume()
    }

    // Save Token
    private func saveToken(_ token: String) {
        UserDefaults.standard.set(token, forKey: "jwt_token")
    }

    // Get Token
    func getToken() -> String? {
        return UserDefaults.standard.string(forKey: "jwt_token")
    }
}

// Models
struct AuthResponse: Codable {
    let accessToken: String
    let tokenType: String
    let user: User

    enum CodingKeys: String, CodingKey {
        case accessToken = "access_token"
        case tokenType = "token_type"
        case user
    }
}

struct User: Codable {
    let id: String
    let name: String
    let email: String
    let picture: String?
}
```

---

## 🤖 Android / Kotlin

### 1. Service de Autenticação

```kotlin
// AuthService.kt
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

interface ApiService {
    @POST("token")
    suspend fun login(@Body credentials: LoginRequest): AuthResponse

    @POST("auth/social")
    suspend fun socialLogin(@Body data: SocialLoginRequest): AuthResponse
}

data class LoginRequest(
    val username: String,
    val password: String
)

data class SocialLoginRequest(
    val provider: String,
    val provider_id: String,
    val email: String,
    val name: String,
    val picture: String? = null
)

data class AuthResponse(
    val access_token: String,
    val token_type: String,
    val user: User
)

data class User(
    val id: String,
    val name: String,
    val email: String,
    val picture: String?
)

class AuthService {
    private val api: ApiService = Retrofit.Builder()
        .baseUrl("https://api.foreverpet.com/v1/rest/")
        .addConverterFactory(GsonConverterFactory.create())
        .build()
        .create(ApiService::class.java)

    suspend fun login(username: String, password: String): AuthResponse {
        return api.login(LoginRequest(username, password))
    }

    suspend fun socialLogin(
        provider: String,
        providerId: String,
        email: String,
        name: String,
        picture: String? = null
    ): AuthResponse {
        return api.socialLogin(
            SocialLoginRequest(provider, providerId, email, name, picture)
        )
    }
}
```

---

## 🧪 Testes

### Teste de Login
```bash
curl -X POST http://localhost:8000/api/v1/rest/token \
  -H "Content-Type: application/json" \
  -d '{"username":"test@example.com","password":"senha123"}'
```

### Teste de Social Login
```bash
curl -X POST http://localhost:8000/api/v1/rest/auth/social \
  -H "Content-Type: application/json" \
  -d '{
    "provider":"google",
    "provider_id":"google_test_123",
    "email":"test@gmail.com",
    "name":"Test User",
    "picture":"https://via.placeholder.com/150"
  }'
```

---

## 📝 Checklist de Integração

### Configuração
- [ ] Configurar URL da API
- [ ] Adicionar dependências (http, auth packages)
- [ ] Configurar OAuth credentials (Google, Facebook, etc)

### Implementação
- [ ] AuthService criado
- [ ] Login normal implementado
- [ ] Social login implementado
- [ ] Token storage implementado
- [ ] Authenticated requests implementadas

### OAuth Providers
- [ ] Google Sign-In configurado
- [ ] Facebook Login configurado
- [ ] Apple Sign In configurado (iOS)
- [ ] Twitter (opcional)

### Testes
- [ ] Login funciona
- [ ] Social login funciona
- [ ] Token é salvo
- [ ] Requests autenticadas funcionam
- [ ] Logout funciona

---

## 🎉 Pronto!

Com esses exemplos, você pode integrar facilmente a autenticação JWT + OAuth no seu app mobile!

**Documentação Completa**: `AUTHENTICATION_GUIDE.md`

