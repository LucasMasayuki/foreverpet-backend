<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ForeverPet')</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
        }
        .content {
            padding: 40px 30px;
        }
        .title {
            font-size: 24px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 20px;
        }
        .text {
            font-size: 16px;
            line-height: 1.6;
            color: #555555;
            margin-bottom: 20px;
        }
        .code-box {
            background-color: #f8f9fa;
            border: 2px dashed #667eea;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 8px;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            font-size: 14px;
            color: #888888;
        }
        .divider {
            border-top: 1px solid #eeeeee;
            margin: 30px 0;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #856404;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <a href="{{ config('app.url') }}" class="logo">
                🐾 ForeverPet
            </a>
        </div>

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="social-links">
                <a href="#">Facebook</a> •
                <a href="#">Instagram</a> •
                <a href="#">Twitter</a>
            </div>
            <p>
                © {{ date('Y') }} ForeverPet. Todos os direitos reservados.
            </p>
            <p style="font-size: 12px; color: #aaa;">
                Este é um email automático, por favor não responda.<br>
                Se você não solicitou este email, ignore-o com segurança.
            </p>
        </div>
    </div>
</body>
</html>

