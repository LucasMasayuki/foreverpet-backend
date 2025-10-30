@extends('emails.layout')

@section('title', 'Redefinir sua senha - ForeverPet')

@section('content')
    <div class="title">
        Redefinir Senha 🔐
    </div>

    <p class="text">
        Olá <strong>{{ $userName }}</strong>,
    </p>

    <p class="text">
        Recebemos uma solicitação para redefinir a senha da sua conta ForeverPet.
    </p>

    <p class="text">
        Clique no botão abaixo para criar uma nova senha:
    </p>

    <div style="text-align: center;">
        <a href="{{ $resetUrl }}" class="button">
            Redefinir Minha Senha
        </a>
    </div>

    <div class="divider"></div>

    <p class="text" style="font-size: 14px; color: #888;">
        Se o botão não funcionar, copie e cole o link abaixo no seu navegador:
    </p>
    <p style="font-size: 12px; color: #667eea; word-break: break-all;">
        {{ $resetUrl }}
    </p>

    <div class="warning">
        <strong>⚠️ Atenção:</strong> Este link expira em 24 horas por questões de segurança.
    </div>

    <div class="divider"></div>

    <p class="text">
        <strong>Você não solicitou esta alteração?</strong><br>
        Se você não pediu para redefinir sua senha, ignore este email com segurança.
        Sua senha permanecerá inalterada.
    </p>

    <p class="text">
        Atenciosamente,<br>
        <strong>Equipe ForeverPet</strong>
    </p>
@endsection

