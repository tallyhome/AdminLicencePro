<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            padding: 20px 0;
            overflow-y: auto;
        }
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
        .card-stats {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 20px;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar text-white">
        <div class="p-3 mb-4">
            <h4 class="mb-0">{{ config('app.name') }}</h4>
            <small class="opacity-75">Espace Client</small>
        </div>
        
        <nav>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('client.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('client.projects.index') }}">
                        <i class="fas fa-folder me-2"></i>
                        Projets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('client.licenses.index') }}">
                        <i class="fas fa-key me-2"></i>
                        Licences
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('client.support.index') }}">
                        <i class="fas fa-life-ring me-2"></i>
                        Support
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('client.settings.index') }}">
                        <i class="fas fa-cog me-2"></i>
                        Paramètres
                    </a>
                </li>
            </ul>
            
            <div class="mt-5 p-3">
                @if($client)
                <div class="mb-3">
                    <small>Connecté en tant que:</small><br>
                    <strong>{{ $client->email }}</strong>
                </div>
                @endif
                
                <form method="POST" action="{{ route('client.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-card card">
                        <div class="card-body p-4">
                            <h2 class="mb-2">Bienvenue{{ $client ? ', ' . $client->first_name : '' }} ! 👋</h2>
                            <p class="mb-0 opacity-90">
                                @if($tenant)
                                    Voici un aperçu de votre activité pour {{ $tenant->name }}
                                @else
                                    Configurez votre espace pour commencer
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($error))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ $error }}
            </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-stats card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-2">Projets</h6>
                                    <h3 class="mb-0">{{ $usageStats['projects']['count'] ?? 0 }}</h3>
                                    <small class="text-muted">
                                        @if(($usageStats['projects']['limit'] ?? 0) > 0)
                                            sur {{ $usageStats['projects']['limit'] }}
                                        @else
                                            Illimité
                                        @endif
                                    </small>
                                </div>
                                <div class="stat-icon" style="background: #4e73df;">
                                    <i class="fas fa-folder"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-stats card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-2">Licences</h6>
                                    <h3 class="mb-0">{{ $usageStats['licenses']['count'] ?? 0 }}</h3>
                                    <small class="text-muted">
                                        @if(($usageStats['licenses']['limit'] ?? 0) > 0)
                                            sur {{ $usageStats['licenses']['limit'] }}
                                        @else
                                            Illimité
                                        @endif
                                    </small>
                                </div>
                                <div class="stat-icon" style="background: #1cc88a;">
                                    <i class="fas fa-key"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-stats card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-2">Licences Actives</h6>
                                    <h3 class="mb-0">{{ $usageStats['active_licenses'] ?? 0 }}</h3>
                                    <small class="text-success">Opérationnelles</small>
                                </div>
                                <div class="stat-icon" style="background: #36b9cc;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-stats card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-2">Activations</h6>
                                    <h3 class="mb-0">{{ $usageStats['total_activations'] ?? 0 }}</h3>
                                    <small class="text-info">Total</small>
                                </div>
                                <div class="stat-icon" style="background: #f6c23e;">
                                    <i class="fas fa-rocket"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Items -->
            <div class="row">
                <div class="col-xl-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 text-primary">
                                <i class="fas fa-folder me-2"></i>
                                Projets Récents
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($recentProjects->count() > 0)
                                <div class="list-group">
                                    @foreach($recentProjects as $project)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $project->name }}</h6>
                                                <small class="text-muted">
                                                    Créé {{ $project->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <span class="badge bg-{{ $project->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($project->status ?? 'actif') }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-folder-open fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun projet pour le moment</p>
                                    <a href="{{ route('client.projects.create') }}" class="btn btn-primary btn-sm">
                                        Créer un projet
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 text-primary">
                                <i class="fas fa-key me-2"></i>
                                Licences Récentes
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($recentLicenses->count() > 0)
                                <div class="list-group">
                                    @foreach($recentLicenses as $license)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ substr($license->serial_key, 0, 20) }}...</h6>
                                                <small class="text-muted">
                                                    Créée {{ $license->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <span class="badge bg-{{ $license->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($license->status ?? 'active') }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-key fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune licence générée</p>
                                    <a href="{{ route('client.licenses.create') }}" class="btn btn-primary btn-sm">
                                        Générer une licence
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Info -->
            @if($subscription)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-crown me-2"></i>
                                Abonnement
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">Plan actuel</small>
                                    <h5>{{ $subscription->plan->name ?? 'Inconnu' }}</h5>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Statut</small>
                                    <h5>
                                        <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </h5>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Expire le</small>
                                    <h5>{{ $subscription->ends_at ? $subscription->ends_at->format('d/m/Y') : 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 