<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .footer {
            background: #333;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
        }
        .features {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        .feature-item {
            margin: 10px 0;
            padding-left: 20px;
            position: relative;
        }
        .feature-item:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #667eea;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Bienvenue sur {{ config('app.name') }} !</h1>
        <p>Votre compte a été créé avec succès</p>
    </div>

    <div class="content">
        <h2>Bonjour {{ $client->name }},</h2>
        
        <p>Félicitations ! Votre compte <strong>{{ $tenant->name }}</strong> a été créé avec succès sur {{ config('app.name') }}.</p>
        
        <div class="features">
            <h3>🚀 Votre plan : {{ $plan->name }}</h3>
            <p><strong>Prix :</strong> 
                @if($plan->price > 0)
                    {{ number_format($plan->price, 2) }}€ / {{ $plan->billing_cycle === 'yearly' ? 'an' : 'mois' }}
                @else
                    Gratuit
                @endif
            </p>
            
            @if($plan->trial_days > 0)
                <p><strong>🎁 Période d'essai :</strong> {{ $plan->trial_days }} jours gratuits</p>
            @endif
            
            @if($plan->features)
                <h4>Fonctionnalités incluses :</h4>
                @foreach($plan->features as $feature)
                    <div class="feature-item">{{ $feature }}</div>
                @endforeach
            @endif
        </div>

        <h3>📋 Informations de votre compte :</h3>
        <ul>
            <li><strong>Email :</strong> {{ $client->email }}</li>
            <li><strong>Entreprise :</strong> {{ $tenant->name }}</li>
            <li><strong>Domaine :</strong> {{ $tenant->domain }}</li>
            <li><strong>Statut :</strong> 
                @if($tenant->subscription_status === 'trial')
                    Période d'essai
                @else
                    Actif
                @endif
            </li>
        </ul>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $loginUrl }}" class="btn">🔐 Accéder à mon espace client</a>
        </div>

        <h3>🔧 Prochaines étapes :</h3>
        <ol>
            <li>Connectez-vous à votre espace client</li>
            <li>Configurez votre premier projet</li>
            <li>Générez vos premières licences</li>
            <li>Explorez toutes les fonctionnalités</li>
        </ol>

        <div style="background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h4>💡 Besoin d'aide ?</h4>
            <p>Notre équipe support est là pour vous accompagner :</p>
            <ul>
                <li>📧 Email : support@{{ config('app.domain', 'adminlicence.com') }}</li>
                <li>📚 Documentation : <a href="{{ route('frontend.documentation') }}">Guide d'utilisation</a></li>
                <li>❓ FAQ : <a href="{{ route('frontend.faqs') }}">Questions fréquentes</a></li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
        <p>
            <a href="{{ route('frontend.terms') }}" style="color: #ccc;">Conditions d'utilisation</a> | 
            <a href="{{ route('frontend.privacy') }}" style="color: #ccc;">Politique de confidentialité</a>
        </p>
    </div>
</body>
</html> 