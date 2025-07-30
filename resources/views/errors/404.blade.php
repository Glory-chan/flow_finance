<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée - FlowFinance</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #6b7280;
            color: #1a202c;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            text-align: center;
            background: white;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            margin: 2rem;
        }

        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 1rem;
            line-height: 1;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1a202c;
        }

        .error-message {
            font-size: 1.1rem;
            color: #718096;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="logo">
            <div class="logo-icon">FL</div>
            <span>FlowFinance</span>
        </div>
        
        <div class="error-code">404</div>
        <h1 class="error-title">Page non trouvée</h1>
        <p class="error-message">
            Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
        </p>
        
        <a href="{{ route('home') }}" class="btn">
            ← Retour à l'accueil
        </a>
    </div>
</body>
</html>