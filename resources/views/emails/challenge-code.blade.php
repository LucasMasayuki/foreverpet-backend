@extends('emails.layout')

@section('title', 'Seu código de verificação - ForeverPet')

@section('content')
    <div class="title">
        Código de Verificação 🔐
    </div>

    <p class="text">
        Olá <strong>{{ $userName }}</strong>,
    </p>

    <p class="text">
        Aqui está o código de verificação que você solicitou para {{ $purpose ?? 'autenticar sua conta' }}:
    </p>

    <div class="code-box">
        <div class="code">{{ $code }}</div>
        <p style="margin: 10px 0 0 0; font-size: 14px; color: #888;">
            Digite este código no aplicativo
        </p>
    </div>

    <p class="text">
        Este código é válido por <strong>10 minutos</strong>.
    </p>

    <div class="divider"></div>

    <div class="warning">
        <strong>⚠️ Segurança:</strong> Nunca compartilhe este código com ninguém.
        A equipe ForeverPet nunca solicitará este código por telefone ou email.
    </div>

    <div class="divider"></div>

    <p class="text">
        Se você não solicitou este código, por favor ignore este email ou entre em contato conosco.
    </p>

    <p class="text">
        Atenciosamente,<br>
        <strong>Equipe ForeverPet</strong>
    </p>
@endsection

