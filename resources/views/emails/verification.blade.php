@extends('emails.layout')

@section('title', 'Verificar sua conta - ForeverPet')

@section('content')
    <div class="title">
        Bem-vindo ao ForeverPet! 🎉
    </div>

    <p class="text">
        Olá <strong>{{ $userName }}</strong>,
    </p>

    <p class="text">
        Obrigado por se cadastrar no ForeverPet! Estamos muito felizes em ter você e seu pet conosco.
    </p>

    <p class="text">
        Para começar a usar todos os recursos da plataforma, precisamos verificar seu endereço de email.
        Clique no botão abaixo para confirmar sua conta:
    </p>

    <div style="text-align: center;">
        <a href="{{ $verificationUrl }}" class="button">
            Verificar Minha Conta
        </a>
    </div>

    <div class="divider"></div>

    <p class="text" style="font-size: 14px; color: #888;">
        Se o botão não funcionar, copie e cole o link abaixo no seu navegador:
    </p>
    <p style="font-size: 12px; color: #667eea; word-break: break-all;">
        {{ $verificationUrl }}
    </p>

    <div class="warning">
        <strong>⚠️ Atenção:</strong> Este link expira em 24 horas por questões de segurança.
    </div>

    <div class="divider"></div>

    <p class="text">
        Caso você não tenha criado esta conta, por favor ignore este email.
    </p>

    <p class="text">
        Atenciosamente,<br>
        <strong>Equipe ForeverPet</strong>
    </p>
@endsection

