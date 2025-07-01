@php use Illuminate\Support\Facades\Auth; @endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Flag Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
        }
        .sidebar .nav-link:hover {
            color: rgba(255,255,255,1);
        }
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,.1);
        }
        .content {
            padding: 20px;
            padding-bottom: 80px; /* Espace pour le footer */
        }
        .main-content-wrapper {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-height: 100vh;
            position: relative;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 250px; /* Largeur du menu de gauche */
            right: 0;
            background-color: #fff;
            border-top: 1px solid #dee2e6;
            padding: 1rem 0;
            z-index: 1000;
        }
        /* Styles pour le sélecteur de langue */
        .navbar .nav-item.dropdown .nav-link {
            color: #333 !important;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            font-size: 0.875rem;
        }
        /* Ajout du décalage pour le sélecteur de langue */
        .navbar .nav-item:last-child {
            margin-left: 50px;
        }
        .navbar .dropdown-menu {
            min-width: 120px;
            max-width: 120px;
            padding: 0.25rem 0;
        }
        .navbar .dropdown-item {
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
        }
        .navbar .dropdown-item.active {
            background-color: #f8f9fa;
            color: #333;
        }
        .navbar .dropdown-item:hover {
            background-color: #e9ecef;
        }
        /* Styles pour les drapeaux */
        .flag-icon {
            display: inline-block;
            width: 1.2em;
            height: 0.9em;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            margin-right: 0.5em;
            vertical-align: middle;
            box-shadow: 0 0 1px rgba(0,0,0,0.2);
            border-radius: 2px;
        }
        .flag-icon-fr { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/fr.svg); }
        .flag-icon-gb { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/gb.svg); }
        .flag-icon-es { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/es.svg); }
        .flag-icon-de { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/de.svg); }
        .flag-icon-it { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/it.svg); }
        .flag-icon-pt { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/pt.svg); }
        .flag-icon-nl { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/nl.svg); }
        .flag-icon-ru { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/ru.svg); }
        .flag-icon-cn { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/cn.svg); }
        .flag-icon-jp { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/jp.svg); }
        .flag-icon-tr { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/tr.svg); }
        .flag-icon-sa { background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/flags/4x3/sa.svg); }
        /* Styles spécifiques pour le sélecteur de langue */
        .navbar .language-selector.dropdown .nav-link {
            color: #333 !important;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            font-size: 0.875rem;
        }
        .navbar .language-selector .dropdown-menu {
            min-width: 120px;
            max-width: 120px;
            padding: 0.25rem 0;
        }
        .navbar .language-selector .dropdown-item {
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            color: #333;
        }
        .navbar .language-selector .dropdown-item.active {
            background-color: #f8f9fa;
            color: #333;
        }
        .navbar .language-selector .dropdown-item:hover {
            background-color: #e9ecef;
        }

        /* Styles spécifiques pour les notifications */
        #notification-list.dropdown-menu {
            width: 400px !important;
            max-height: 600px !important;
            min-width: 400px !important;
        }

        /* Styles pour l'icône dark mode */
        #darkModeToggle {
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            color: #333;
        }
        #darkModeToggle:hover {
            color: #2563eb;
            transform: rotate(15deg);
        }
        .dark-mode #darkModeToggle {
            color: #fbbf24;
        }
        .dark-mode #darkModeToggle:hover {
            color: #fcd34d;
        }
    </style>
    @stack('styles')
</head>
<body class="{{ session('dark_mode') ? 'dark-mode' : '' }}">
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3" style="width: 250px;">
            <div class="mb-4">
                <h4>{{ config('app.name', 'Laravel') }}</h4>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> {{ t('common.dashboard') }}
                    </a>
                </li>

                <!-- Gestion des licences -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
                        <i class="fas fa-project-diagram me-2"></i> {{ t('common.projects') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.serial-keys.*') ? 'active' : '' }}" href="{{ route('admin.serial-keys.index') }}">
                        <i class="fas fa-key me-2"></i> {{ t('common.serial_keys') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.api-keys.*') ? 'active' : '' }}" href="{{ route('admin.api-keys.index') }}">
                        <i class="fas fa-code me-2"></i> {{ t('common.api_keys') }}
                    </a>
                </li>

                <!-- Gestion des emails -->
                <li class="nav-item">
                    <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#emailSubmenu">
                        <i class="fas fa-envelope me-2"></i>{{ t('common.email') }}
                    </a>
                    <div class="collapse" id="emailSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.mail.settings') }}">
                                    <i class="fas fa-cog me-2"></i>{{ t('common.settings') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.mail.providers.phpmail.index') }}">
                                    <i class="fas fa-mail-bulk me-2"></i>PHPMail
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.mail.providers.mailgun.index') }}">
                                    <i class="fas fa-mail-bulk me-2"></i>Mailgun
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.mail.providers.mailchimp.index') }}">
                                    <i class="fas fa-mail-bulk me-2"></i>Mailchimp
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.email.templates.index') }}">
                                    <i class="fas fa-file-alt me-2"></i>Templates
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Documentation -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#documentationSubmenu" role="button">
                        <i class="fas fa-book me-2"></i> {{ t('layout.documentation') }}
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.api.documentation') || request()->routeIs('admin.licence.documentation') ? 'show' : '' }}" id="documentationSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.api.documentation') ? 'active' : '' }}" href="{{ route('admin.api.documentation') }}">
                                    <i class="fas fa-code me-2"></i> Documentation API
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.licence.documentation') ? 'active' : '' }}" href="{{ route('admin.licence.documentation') }}">
                                    <i class="fas fa-key me-2"></i> Documentation des clés
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.email.documentation') ? 'active' : '' }}" href="{{ route('admin.email.documentation') }}">
                                    <i class="fas fa-envelope me-2"></i> Documentation des fournisseurs d'email
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.version') ? 'active' : '' }}" href="{{ route('admin.version') }}">
                        <i class="fas fa-code-branch me-2"></i> {{ t('layout.version_info') }}
                    </a>
                </li>

                <!-- CMS -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.cms.*') ? 'active' : '' }}" href="{{ route('admin.cms.index') }}">
                        <i class="fas fa-palette me-2"></i> CMS
                    </a>
                </li>

                <!-- Paramètres -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#settingsSubmenu" role="button">
                        <i class="fas fa-cog me-2"></i> {{ t('common.settings') }}
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.settings.*') ? 'show' : '' }}" id="settingsSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                                    <i class="fas fa-sliders-h me-2"></i> Général
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.settings.two-factor') ? 'active' : '' }}" href="{{ route('admin.settings.two-factor') }}">
                                    <i class="fas fa-shield-alt me-2"></i> 2FA
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.settings.translations.*') ? 'active' : '' }}" href="{{ route('admin.settings.translations.index') }}">
                                    <i class="fas fa-language me-2"></i> Langues
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="flex-grow-1 main-content-wrapper">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <!-- Dark Mode Toggle -->
                            <li class="nav-item">
                                <a class="nav-link" href="#" id="darkModeToggle" title="{{ session('dark_mode') ? t('layout.light_mode') : t('layout.dark_mode') }}" style="font-size: 1.2rem;">
                                    <i class="fas {{ session('dark_mode') ? 'fa-sun' : 'fa-moon' }}"></i>
                                </a>
                            </li>
                            <!-- Language Selector -->
                            <li class="nav-item">
                                @include('admin.layouts.partials.language-selector')
                            </li>
                            <!-- Composant de notifications -->
                            <li class="nav-item">
                                @include('admin.layouts.partials.notifications')
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::guard('admin')->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <form method="POST" action="{{ route('admin.logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
            
            <!-- Footer -->
            @include('admin.layouts.partials.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    <!-- TinyMCE CDN -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss pour les alertes après 5 secondes
            const alerts = document.querySelectorAll('.alert-dismissible');
            if (alerts.length > 0) {
                setTimeout(function() {
                    alerts.forEach(function(alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    });
                }, 5000); // 5000ms = 5 secondes
            }

            // Dark Mode Toggle
            const darkModeToggle = document.getElementById('darkModeToggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Basculer immédiatement la classe pour un retour visuel instantané
                    const isDarkMode = document.body.classList.toggle('dark-mode');
                    
                    // Changer l'icône
                    const icon = this.querySelector('i');
                    if (isDarkMode) {
                        icon.classList.remove('fa-moon');
                        icon.classList.add('fa-sun');
                        this.title = '{{ t("layout.light_mode") }}';
                    } else {
                        icon.classList.remove('fa-sun');
                        icon.classList.add('fa-moon');
                        this.title = '{{ t("layout.dark_mode") }}';
                    }
                    
                    // Envoyer la requête AJAX pour sauvegarder l'état
                    fetch('{{ route("admin.settings.toggle-dark-mode") }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            dark_mode: isDarkMode
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Dark mode toggled:', data);
                    })
                    .catch(error => {
                        console.error('Error toggling dark mode:', error);
                        // En cas d'erreur, revenir à l'état précédent
                        document.body.classList.toggle('dark-mode');
                        if (isDarkMode) {
                            icon.classList.remove('fa-sun');
                            icon.classList.add('fa-moon');
                        } else {
                            icon.classList.remove('fa-moon');
                            icon.classList.add('fa-sun');
                        }
                    });
                });
            }
        });

        // Configuration TinyMCE globale
        function initTinyMCE(selector = '.wysiwyg', height = 400) {
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: selector,
                    height: height,
                    language: 'fr_FR',
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
                    ],
                    toolbar: 'undo redo | blocks fontfamily fontsize | ' +
                    'bold italic underline strikethrough | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | link image media table | code preview | help',
                    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
                    images_upload_url: '{{ route("admin.cms.upload-image") }}',
                    automatic_uploads: true,
                    file_picker_types: 'image',
                    file_picker_callback: function(cb, value, meta) {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        input.onchange = function() {
                            var file = this.files[0];
                            var reader = new FileReader();
                            reader.onload = function () {
                                var id = 'blobid' + (new Date()).getTime();
                                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                                var base64 = reader.result.split(',')[1];
                                var blobInfo = blobCache.create(id, file, base64);
                                blobCache.add(blobInfo);
                                cb(blobInfo.blobUri(), { title: file.name });
                            };
                            reader.readAsDataURL(file);
                        };
                        input.click();
                    },
                    images_upload_handler: function (blobInfo, success, failure) {
                        var xhr, formData;
                        xhr = new XMLHttpRequest();
                        xhr.withCredentials = false;
                        xhr.open('POST', '{{ route("admin.cms.upload-image") }}');
                        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                        
                        xhr.onload = function() {
                            var json;
                            if (xhr.status != 200) {
                                failure('HTTP Error: ' + xhr.status);
                                return;
                            }
                            json = JSON.parse(xhr.responseText);
                            if (!json || typeof json.location != 'string') {
                                failure('Invalid JSON: ' + xhr.responseText);
                                return;
                            }
                            success(json.location);
                        };
                        
                        formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        xhr.send(formData);
                    }
                });
            }
        }

        // Auto-init TinyMCE
        document.addEventListener('DOMContentLoaded', function() {
            // Attendre que TinyMCE soit chargé
            const interval = setInterval(function() {
                if (typeof tinymce !== 'undefined') {
                    clearInterval(interval);
                    initTinyMCE();
                }
            }, 100);
        });
    </script>
    @stack('scripts')
</body>
</html>