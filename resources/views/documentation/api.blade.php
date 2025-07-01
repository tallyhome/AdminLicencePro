@extends($layout ?? 'frontend.templates.modern.layout')

@section('title', 'Guide d\'intégration API - AdminLicence')

@section('content')
<div class="bg-light min-vh-100">
    <!-- Hero Section Compact -->
    <div class="hero-section-compact text-center py-4">
        <div class="container">
            <h1 class="h3 fw-bold mb-3">Guide d'intégration API</h1>
            <a href="{{ route('documentation.index') }}" class="btn btn-light btn-sm fw-semibold">
                <i class="fas fa-arrow-left me-2"></i>Retour à la documentation
            </a>
        </div>
    </div>

    <!-- Documentation Layout -->
    <div class="documentation-layout">
        <!-- Sidebar Navigation -->
        <nav class="doc-sidebar">
            <div class="sidebar-content">
                <h6 class="sidebar-title">Table des matières</h6>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="#guide-d-integration-de-l-api-adminlicence">
                            <i class="fas fa-play-circle me-2"></i>Introduction
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#authentification-avec-les-cles-api">
                            <i class="fas fa-key me-2"></i>Authentification avec les clés API
                        </a>
                        <ul class="nav nav-pills flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link nav-link-sub" href="#obtention-des-cles-api">
                                    <i class="fas fa-plus-circle me-2"></i>Obtention des clés
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-sub" href="#utilisation-des-cles-api">
                                    <i class="fas fa-lock me-2"></i>Utilisation des clés
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#integration-des-cles-de-licence">
                            <i class="fas fa-cogs me-2"></i>Intégration des clés de licence
                        </a>
                        <ul class="nav nav-pills flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link nav-link-sub" href="#endpoints-de-verification-de-licence">
                                    <i class="fas fa-link me-2"></i>Endpoints
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-sub" href="#format-de-requete">
                                    <i class="fas fa-upload me-2"></i>Format requête
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-sub" href="#format-de-reponse">
                                    <i class="fas fa-download me-2"></i>Format réponse
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#bonnes-pratiques-pour-les-licences-single-multi">
                            <i class="fas fa-star me-2"></i>Bonnes pratiques
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#exemples-d-integration">
                            <i class="fas fa-code me-2"></i>Exemples d'intégration
                        </a>
                        <ul class="nav nav-pills flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link nav-link-sub" href="#php-standard">
                                    <i class="fab fa-php me-2"></i>PHP Standard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-sub" href="#laravel">
                                    <i class="fab fa-laravel me-2"></i>Laravel
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-sub" href="#flutter">
                                    <i class="fas fa-mobile-alt me-2"></i>Flutter
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reference-de-l-api">
                            <i class="fas fa-book me-2"></i>Référence de l'API
                        </a>
                    </li>
                </ul>

                <!-- Actions rapides -->
                <div class="sidebar-actions mt-4">
                    <h6 class="sidebar-title">Actions rapides</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('documentation.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Documentation principale
                        </a>
                        <a href="/admin/login" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard Admin
                        </a>
                        <a href="/support" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-question-circle me-1"></i>Obtenir de l'aide
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="doc-content">
            <div class="content-wrapper">
                <div class="api-docs">
                    {!! $content !!}
                </div>

                <!-- Navigation en bas de page -->
                <div class="content-navigation mt-5 pt-4 border-top bg-light rounded p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <a href="{{ route('documentation.index') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-arrow-left me-2"></i>Documentation principale
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="/support" class="btn btn-success btn-lg w-100">
                                Besoin d'aide ?<i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
/* Layout principal */
.hero-section-compact {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: 1px solid #e9ecef;
}

.documentation-layout {
    display: flex;
    min-height: calc(100vh - 200px);
}

/* Sidebar */
.doc-sidebar {
    width: 280px;
    min-width: 280px;
    background: white;
    border-right: 1px solid #e9ecef;
    position: sticky;
    top: 0;
    height: calc(100vh - 200px);
    overflow-y: auto;
    z-index: 100;
}

.sidebar-content {
    padding: 1.5rem;
}

.sidebar-title {
    color: #2563eb;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e9ecef;
}

.doc-sidebar .nav-link {
    color: #6b7280;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    margin-bottom: 0.25rem;
    transition: all 0.2s ease;
    border: none;
}

.doc-sidebar .nav-link:hover {
    background-color: #f3f4f6;
    color: #2563eb;
}

.doc-sidebar .nav-link.active {
    background-color: #2563eb;
    color: white;
}

.doc-sidebar .nav-link-sub {
    font-size: 0.8rem;
    padding-left: 1rem;
    color: #9ca3af;
}

.sidebar-actions {
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

/* Contenu principal */
.doc-content {
    flex: 1;
    background: white;
    overflow-y: auto;
    height: calc(100vh - 200px);
}

.content-wrapper {
    padding: 2rem;
    max-width: 900px;
    margin: 0 auto;
}

/* Styles du contenu API */
.api-docs {
    line-height: 1.7;
}

.api-docs h1, .api-docs h2, .api-docs h3, .api-docs h4, .api-docs h5, .api-docs h6 {
    color: #1f2937;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 1rem;
    scroll-margin-top: 2rem;
}

.api-docs h1 { 
    font-size: 2rem; 
    color: #2563eb;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 0.5rem;
}
.api-docs h2 { 
    font-size: 1.5rem; 
    color: #2563eb;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 0.25rem;
}
.api-docs h3 { font-size: 1.25rem; }
.api-docs h4 { font-size: 1.1rem; }

.api-docs p {
    margin-bottom: 1rem;
    color: #374151;
}

.api-docs ul, .api-docs ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.api-docs li {
    margin-bottom: 0.5rem;
    color: #374151;
}

.api-docs pre {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin: 1.5rem 0;
    overflow-x: auto;
    font-size: 0.875rem;
    position: relative;
}

.api-docs pre::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #2563eb, #7c3aed);
    border-radius: 0.5rem 0.5rem 0 0;
}

.api-docs code {
    background-color: #f3f4f6;
    color: #dc2626;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
}

.api-docs pre code {
    background-color: transparent;
    color: #374151;
    padding: 0;
    font-size: inherit;
}

.api-docs table {
    width: 100%;
    margin: 1.5rem 0;
    border-collapse: collapse;
    font-size: 0.875rem;
}

.api-docs table, .api-docs th, .api-docs td {
    border: 1px solid #e5e7eb;
}

.api-docs th, .api-docs td {
    padding: 0.75rem;
    text-align: left;
}

.api-docs th {
    background-color: #f9fafb;
    font-weight: 600;
    color: #374151;
}

.api-docs tr:nth-child(even) {
    background-color: #f9fafb;
}

.api-docs blockquote {
    border-left: 4px solid #2563eb;
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    background: #eff6ff;
    border-radius: 0 0.5rem 0.5rem 0;
    color: #1e40af;
}

.api-docs a {
    color: #2563eb;
    text-decoration: none;
}

.api-docs a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .documentation-layout {
        flex-direction: column;
    }
    
    .doc-sidebar {
        width: 100%;
        height: auto;
        position: relative;
        border-right: none;
        border-bottom: 1px solid #e9ecef;
    }
    
    .doc-content {
        height: auto;
    }
    
    .content-wrapper {
        padding: 1rem;
    }
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Scroll spy effect */
.nav-link.active {
    background-color: #2563eb !important;
    color: white !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.doc-sidebar .nav-link');
    const docContent = document.querySelector('.doc-content');
    
    // Fonction pour le smooth scrolling
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                // Calculer la position avec offset pour le header
                const offsetTop = targetElement.offsetTop - 20;
                
                // Scroll dans le conteneur principal
                docContent.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
                
                // Mettre à jour l'état actif
                updateActiveNavItem(this);
            }
        });
    });
    
    // Fonction pour mettre à jour le lien actif
    function updateActiveNavItem(activeLink) {
        navLinks.forEach(link => {
            link.classList.remove('active');
        });
        activeLink.classList.add('active');
    }
    
    // Scroll spy amélioré
    function handleScrollSpy() {
        const scrollTop = docContent.scrollTop;
        const sections = document.querySelectorAll('.api-docs h1[id], .api-docs h2[id], .api-docs h3[id]');
        
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 50;
            const sectionHeight = section.offsetHeight;
            
            if (scrollTop >= sectionTop && scrollTop < sectionTop + sectionHeight) {
                current = section.id;
            }
        });
        
        // Si aucune section n'est trouvée, utiliser la première visible
        if (!current && sections.length > 0) {
            for (let section of sections) {
                if (section.offsetTop - 50 <= scrollTop + 100) {
                    current = section.id;
                }
            }
        }
        
        if (current) {
            const activeNavLink = document.querySelector(`.doc-sidebar .nav-link[href="#${current}"]`);
            if (activeNavLink && !activeNavLink.classList.contains('active')) {
                updateActiveNavItem(activeNavLink);
            }
        }
    }
    
    // Écouter le scroll dans le conteneur de contenu
    docContent.addEventListener('scroll', handleScrollSpy);
    
    // Initialiser avec le premier élément
    setTimeout(() => {
        handleScrollSpy();
        
        // Si aucun élément n'est actif, activer le premier
        if (!document.querySelector('.doc-sidebar .nav-link.active')) {
            const firstLink = document.querySelector('.doc-sidebar .nav-link');
            if (firstLink) {
                updateActiveNavItem(firstLink);
            }
        }
    }, 100);
    
    // Debug: afficher les IDs disponibles
    console.log('IDs disponibles:', Array.from(document.querySelectorAll('[id]')).map(el => el.id));
    console.log('Liens du menu:', Array.from(navLinks).map(link => link.getAttribute('href')));
});
</script>

@endsection
