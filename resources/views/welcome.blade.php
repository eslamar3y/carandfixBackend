<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Click & Fix') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .container {
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
        }
        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #f8fafc;
        }
        p {
            font-size: 1rem;
            color: #94a3b8;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            color: #fff;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }
        .links {
            margin-top: 2rem;
            display: flex;
            gap: 1.5rem;
            justify-content: center;
        }
        .links a {
            color: #64748b;
            font-size: 0.875rem;
            text-decoration: none;
            transition: color 0.2s;
        }
        .links a:hover {
            color: #f59e0b;
        }
        .footer {
            margin-top: 3rem;
            font-size: 0.75rem;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">C</div>
        <h1>{{ config('app.name', 'Click & Fix') }}</h1>
        <p>Car repair & roadside assistance management system</p>
        <a href="/admin/login" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            Login
        </a>
        <div class="footer">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
    </div>
</body>
</html>
