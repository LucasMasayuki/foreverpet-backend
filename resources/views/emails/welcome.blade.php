@extends('emails.layout')

@section('title', 'Bem-vindo ao ForeverPet!')

@section('content')
    <div class="title">
        Bem-vindo ao ForeverPet! 🎉🐾
    </div>

    <p class="text">
        Olá <strong>{{ $userName }}</strong>,
    </p>

    <p class="text">
        Sua conta foi verificada com sucesso! Agora você tem acesso completo a todos os recursos do ForeverPet.
    </p>

    <div style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #667eea;">✨ O que você pode fazer agora:</h3>
        <ul style="line-height: 1.8; color: #555;">
            <li>📝 Cadastrar seus pets</li>
            <li>💉 Gerenciar vacinas e medicamentos</li>
            <li>📅 Agendar consultas veterinárias</li>
            <li>📊 Acompanhar o crescimento e peso</li>
            <li>🏥 Encontrar clínicas e pet shops</li>
            <li>📸 Compartilhar fotos dos seus pets</li>
        </ul>
    </div>

    <div style="text-align: center;">
        <a href="{{ config('app.url') }}" class="button">
            Começar Agora
        </a>
    </div>

    <div class="divider"></div>

    <p class="text">
        Precisa de ajuda? Nossa equipe está sempre disponível para você:
    </p>

    <div style="background-color: #e7f3ff; border-left: 4px solid #2196f3; padding: 15px; margin: 20px 0;">
        <p style="margin: 5px 0; font-size: 14px;">
            📧 Email: suporte@foreverpet.com<br>
            💬 Chat: Disponível no app<br>
            📞 WhatsApp: (11) 99999-9999
        </p>
    </div>

    <p class="text">
        Obrigado por escolher o ForeverPet para cuidar do seu melhor amigo! 💜
    </p>

    <p class="text">
        Atenciosamente,<br>
        <strong>Equipe ForeverPet</strong>
    </p>
@endsection

