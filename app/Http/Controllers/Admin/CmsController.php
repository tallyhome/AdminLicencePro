<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\CmsTemplate;
use App\Models\CmsFeature;
use App\Models\CmsFaq;
use App\Models\CmsTestimonial;
use App\Models\CmsAboutSection;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Database\Seeders\CmsDataSeeder;

class CmsController extends Controller
{
    /**
     * Dashboard principal du CMS
     */
    public function index()
    {
        $stats = [
            'features' => CmsFeature::count(),
            'faqs' => CmsFaq::count(),
            'testimonials' => CmsTestimonial::count(),
            'about_sections' => CmsAboutSection::count(),
        ];

        $currentTemplate = CmsTemplate::getDefault();
        $templates = CmsTemplate::active()->get();

        return view('admin.cms.index', compact('stats', 'currentTemplate', 'templates'));
    }

    /**
     * Gestion des paramètres généraux du site
     */
    public function settings()
    {
        $settings = [
            // Paramètres généraux
            'site_title' => Setting::get('cms_site_title', 'AdminLicence'),
            'site_tagline' => Setting::get('cms_site_tagline', 'Système de gestion de licences ultra-sécurisé'),
            'hero_title' => Setting::get('cms_hero_title', 'Sécurisez vos licences logicielles'),
            'hero_subtitle' => Setting::get('cms_hero_subtitle', 'Solution professionnelle de gestion de licences avec sécurité avancée'),
            'contact_email' => Setting::get('cms_contact_email', ''),
            'contact_phone' => Setting::get('cms_contact_phone', ''),
            
            // Navigation
            'nav_home_text' => Setting::get('cms_nav_home_text', 'Accueil'),
            'nav_features_text' => Setting::get('cms_nav_features_text', 'Fonctionnalités'),
            'nav_pricing_text' => Setting::get('cms_nav_pricing_text', 'Tarifs'),
            'nav_about_text' => Setting::get('cms_nav_about_text', 'À propos'),
            'nav_faq_text' => Setting::get('cms_nav_faq_text', 'FAQ'),
            'nav_contact_text' => Setting::get('cms_nav_contact_text', 'Contact'),
            'nav_support_text' => Setting::get('cms_nav_support_text', 'Support'),
            'nav_admin_text' => Setting::get('cms_nav_admin_text', 'Admin'),
            
            // Textes des sections
            'features_section_title' => Setting::get('cms_features_section_title', 'Fonctionnalités Avancées'),
            'features_section_subtitle' => Setting::get('cms_features_section_subtitle', 'Découvrez pourquoi AdminLicence est la solution de référence pour la sécurisation de vos licences'),
            'testimonials_section_title' => Setting::get('cms_testimonials_section_title', 'Ce que disent nos clients'),
            'testimonials_section_subtitle' => Setting::get('cms_testimonials_section_subtitle', 'Découvrez les témoignages de nos utilisateurs satisfaits'),
            'faqs_section_title' => Setting::get('cms_faqs_section_title', 'Questions Fréquentes'),
            'faqs_section_subtitle' => Setting::get('cms_faqs_section_subtitle', 'Trouvez rapidement les réponses à vos questions'),
            
            // Call-to-Action
            'cta_title' => Setting::get('cms_cta_title', 'Prêt à sécuriser vos licences ?'),
            'cta_subtitle' => Setting::get('cms_cta_subtitle', 'Rejoignez des milliers d\'entreprises qui font confiance à AdminLicence pour protéger leurs logiciels.'),
            'cta_button_text' => Setting::get('cms_cta_button_text', 'Commencer maintenant'),
            'cta_button_url' => Setting::get('cms_cta_button_url', '/admin'),
            'hero_button_text' => Setting::get('cms_hero_button_text', 'Commencer maintenant'),
            'hero_button_secondary_text' => Setting::get('cms_hero_button_secondary_text', 'En savoir plus'),
            
            // Footer
            'footer_text' => Setting::get('cms_footer_text', '© 2025 AdminLicence. Solution sécurisée de gestion de licences.'),
            'footer_security_badge_text' => Setting::get('cms_footer_security_badge_text', 'Chiffrement AES-256'),
            'footer_secure_text' => Setting::get('cms_footer_secure_text', 'Site sécurisé par AdminLicence'),
            'footer_product_title' => Setting::get('cms_footer_product_title', 'Produit'),
            'footer_product_features' => Setting::get('cms_footer_product_features', 'Fonctionnalités'),
            'footer_product_about' => Setting::get('cms_footer_product_about', 'À propos'),
            'footer_support_title' => Setting::get('cms_footer_support_title', 'Support'),
            'footer_support_contact' => Setting::get('cms_footer_support_contact', 'Contact'),
            'footer_support_documentation' => Setting::get('cms_footer_support_documentation', 'Documentation'),
            'footer_contact_title' => Setting::get('cms_footer_contact_title', 'Contact'),
            'footer_terms_text' => Setting::get('cms_footer_terms_text', 'Termes et Conditions'),
            'footer_privacy_text' => Setting::get('cms_footer_privacy_text', 'Politique de confidentialité'),
            
            // Contenu des pages légales
            'terms_content' => Setting::get('cms_terms_content', $this->getDefaultTermsContent()),
            'privacy_content' => Setting::get('cms_privacy_content', $this->getDefaultPrivacyContent()),
            
            // Options d'affichage
            'show_hero' => Setting::get('cms_show_hero', true),
            'show_features' => Setting::get('cms_show_features', true),
            'show_testimonials' => Setting::get('cms_show_testimonials', true),
            'show_faqs' => Setting::get('cms_show_faqs', true),
            'show_about' => Setting::get('cms_show_about', true),
        ];

        return view('admin.cms.settings.index', compact('settings'));
    }

    /**
     * Mettre à jour les paramètres généraux
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            // Paramètres généraux
            'site_title' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:500',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'nullable|string|max:1000',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            
            // Navigation
            'nav_home_text' => 'nullable|string|max:100',
            'nav_features_text' => 'nullable|string|max:100',
            'nav_pricing_text' => 'nullable|string|max:100',
            'nav_about_text' => 'nullable|string|max:100',
            'nav_faq_text' => 'nullable|string|max:100',
            'nav_contact_text' => 'nullable|string|max:100',
            'nav_support_text' => 'nullable|string|max:100',
            'nav_admin_text' => 'nullable|string|max:100',
            
            // Textes des sections
            'features_section_title' => 'nullable|string|max:255',
            'features_section_subtitle' => 'nullable|string|max:500',
            'testimonials_section_title' => 'nullable|string|max:255',
            'testimonials_section_subtitle' => 'nullable|string|max:500',
            'faqs_section_title' => 'nullable|string|max:255',
            'faqs_section_subtitle' => 'nullable|string|max:500',
            
            // Call-to-Action
            'cta_title' => 'nullable|string|max:255',
            'cta_subtitle' => 'nullable|string|max:1000',
            'cta_button_text' => 'nullable|string|max:100',
            'cta_button_url' => 'nullable|string|max:255',
            'hero_button_text' => 'nullable|string|max:100',
            'hero_button_secondary_text' => 'nullable|string|max:100',
            
            // Footer
            'footer_text' => 'nullable|string|max:500',
            'footer_security_badge_text' => 'nullable|string|max:100',
            'footer_secure_text' => 'nullable|string|max:100',
            'footer_product_title' => 'nullable|string|max:100',
            'footer_product_features' => 'nullable|string|max:100',
            'footer_product_about' => 'nullable|string|max:100',
            'footer_support_title' => 'nullable|string|max:100',
            'footer_support_contact' => 'nullable|string|max:100',
            'footer_support_documentation' => 'nullable|string|max:100',
            'footer_contact_title' => 'nullable|string|max:100',
            'footer_terms_text' => 'nullable|string|max:100',
            'footer_privacy_text' => 'nullable|string|max:100',
            
            // Contenu des pages légales
            'terms_content' => 'nullable|string',
            'privacy_content' => 'nullable|string',
        ]);

        // Sauvegarder les paramètres texte
        foreach ($validated as $key => $value) {
            Setting::set('cms_' . $key, $value ?? '');
        }

        // Sauvegarder les options d'affichage
        $displayOptions = [
            'show_hero',
            'show_features', 
            'show_testimonials',
            'show_faqs',
            'show_about'
        ];

        foreach ($displayOptions as $option) {
            Setting::set('cms_' . $option, $request->has($option));
        }

        return redirect()->route('admin.cms.settings')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }

    /**
     * Gestion des templates
     */
    public function templates()
    {
        $currentTemplate = CmsTemplate::getDefault();
        $templates = CmsTemplate::active()->get();

        return view('admin.cms.templates.index', compact('currentTemplate', 'templates'));
    }

    /**
     * Activer un template
     */
    public function activateTemplate(Request $request, CmsTemplate $template)
    {
        // Désactiver tous les templates
        CmsTemplate::where('is_default', true)->update(['is_default' => false]);
        
        // Activer le template sélectionné
        $template->update(['is_default' => true]);

        return redirect()->route('admin.cms.templates')
            ->with('success', 'Template activé avec succès.');
    }

    /**
     * Changer de template
     */
    public function switchTemplate(Request $request)
    {
        $templateName = $request->input('template_name');
        
        if (!$templateName) {
            return response()->json(['success' => false, 'message' => 'Nom du template requis'], 400);
        }

        // Vérifier si le template existe
        $templateViews = [
            'modern' => 'frontend.templates.modern.home',
            'professional' => 'frontend.templates.professional.home'
        ];

        if (!isset($templateViews[$templateName])) {
            return response()->json(['success' => false, 'message' => 'Template non trouvé'], 404);
        }

        // Vérifier si la vue existe
        if (!view()->exists($templateViews[$templateName])) {
            return response()->json(['success' => false, 'message' => 'Vue du template non trouvée'], 404);
        }

        // Sauvegarder le template actuel
        Setting::set('cms_current_template', $templateName);

        return response()->json(['success' => true, 'message' => 'Template activé avec succès']);
    }

    /**
     * Initialiser les données par défaut du CMS
     */
    public function initialize()
    {
        try {
            $seeder = new CmsDataSeeder();
            $seeder->run();
            
            return redirect()->route('admin.cms.index')->with('success', 'CMS initialisé avec succès !');
        } catch (\Exception $e) {
            return redirect()->route('admin.cms.index')->with('error', 'Erreur lors de l\'initialisation : ' . $e->getMessage());
        }
    }

    /**
     * Upload d'image pour TinyMCE
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB max
        ]);

        try {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('cms/images', $filename, 'public');
            
            // Créer l'enregistrement en base si nécessaire
            $media = new \stdClass();
            $media->filename = $filename;
            $media->path = $path;
            $media->size = $file->getSize();
            $media->mime_type = $file->getMimeType();
            
            return response()->json([
                'location' => asset('storage/' . $path),
                'media' => $media
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Gestion des médias
     */
    public function mediaManager()
    {
        $mediaPath = storage_path('app/public/cms/images');
        $medias = [];
        
        if (is_dir($mediaPath)) {
            $files = scandir($mediaPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $mediaPath . '/' . $file;
                    if (is_file($filePath)) {
                        $medias[] = [
                            'name' => $file,
                            'url' => asset('storage/cms/images/' . $file),
                            'size' => filesize($filePath),
                            'created_at' => date('Y-m-d H:i:s', filemtime($filePath))
                        ];
                    }
                }
            }
        }
        
        return view('admin.cms.media.index', compact('medias'));
    }

    /**
     * Supprimer un média
     */
    public function deleteMedia(Request $request)
    {
        $filename = $request->get('filename');
        $path = storage_path('app/public/cms/images/' . $filename);
        
        if (file_exists($path)) {
            unlink($path);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['error' => 'Fichier non trouvé'], 404);
    }

    /**
     * Prévisualisation du site
     */
    public function preview()
    {
        return redirect()->route('frontend.home', ['preview' => 1]);
    }

    /**
     * Contenu par défaut pour les Termes et Conditions
     */
    private function getDefaultTermsContent()
    {
        return '<h3>1. Acceptation des Conditions</h3>
<p>En accédant et en utilisant AdminLicence, vous acceptez d\'être lié par ces termes et conditions d\'utilisation. Si vous n\'acceptez pas tous les termes et conditions de cet accord, vous ne devez pas utiliser ce service.</p>

<h3>2. Description du Service</h3>
<p>AdminLicence est une plateforme de gestion de licences logicielles qui permet aux développeurs et aux entreprises de sécuriser, distribuer et gérer leurs licences de logiciels.</p>
<p>Le service comprend :</p>
<ul>
<li>Génération et validation de clés de licence</li>
<li>Dashboard d\'administration</li>
<li>API de validation</li>
<li>Système de gestion des utilisateurs</li>
</ul>

<h3>3. Conditions d\'Utilisation</h3>
<p>Vous vous engagez à :</p>
<ul>
<li>Utiliser le service de manière légale et appropriée</li>
<li>Ne pas tenter de contourner les mesures de sécurité</li>
<li>Maintenir la confidentialité de vos identifiants de connexion</li>
<li>Ne pas utiliser le service pour des activités illégales</li>
<li>Respecter les droits de propriété intellectuelle</li>
</ul>

<h3>4. Propriété Intellectuelle</h3>
<p>AdminLicence et tous ses contenus, fonctionnalités et fonctions sont la propriété exclusive de notre société. Tous les droits d\'auteur, marques commerciales et autres droits de propriété intellectuelle sont réservés.</p>

<h3>5. Limitation de Responsabilité</h3>
<p>AdminLicence est fourni "en l\'état" sans garantie d\'aucune sorte. Nous ne garantissons pas que le service sera ininterrompu, sécurisé ou exempt d\'erreurs. En aucun cas, nous ne serons responsables des dommages directs, indirects, incidents ou consécutifs.</p>

<h3>6. Protection des Données</h3>
<p>Nous nous engageons à protéger vos données personnelles conformément à notre <a href="/privacy">Politique de Confidentialité</a>. Vos données sont traitées selon les réglementations en vigueur, notamment le RGPD.</p>

<h3>7. Modifications des Conditions</h3>
<p>Nous nous réservons le droit de modifier ces termes et conditions à tout moment. Les modifications prennent effet immédiatement après leur publication sur cette page. Il est de votre responsabilité de consulter régulièrement ces conditions.</p>

<h3>8. Résiliation</h3>
<p>Nous nous réservons le droit de suspendre ou de résilier votre accès au service à tout moment, sans préavis, pour non-respect de ces conditions d\'utilisation.</p>

<h3>9. Droit Applicable</h3>
<p>Ces conditions sont régies par le droit français. Tout litige sera soumis à la compétence exclusive des tribunaux français.</p>

<h3>10. Contact</h3>
<p>Pour toute question concernant ces termes et conditions, vous pouvez nous contacter à :</p>
<p><strong>Email :</strong> legal@adminlicence.com</p>
<p><strong>Adresse :</strong> AdminLicence Legal Department</p>

<p><em>Ces termes et conditions sont effectifs depuis le ' . date('d/m/Y') . '.</em></p>';
    }

        /**
     * Contenu par défaut pour la Politique de confidentialité
     */
    private function getDefaultPrivacyContent()
    {
        return '<h3>1. Introduction</h3>
<p>AdminLicence s\'engage à protéger et respecter votre vie privée. Cette politique explique comment nous collectons, utilisons et protégeons vos informations personnelles lorsque vous utilisez notre service.</p>

<h3>2. Responsable du Traitement</h3>
<p><strong>Société :</strong> AdminLicence</p>
<p><strong>Email :</strong> privacy@adminlicence.com</p>
<p><strong>Adresse :</strong> AdminLicence Data Protection Office</p>

<h3>3. Données Collectées</h3>
<p>Nous collectons les types de données suivants :</p>

<h4>3.1 Données d\'identification</h4>
<ul>
<li>Nom et prénom</li>
<li>Adresse email</li>
<li>Nom d\'utilisateur</li>
<li>Mot de passe (crypté)</li>
</ul>

<h4>3.2 Données techniques</h4>
<ul>
<li>Adresse IP</li>
<li>Informations sur le navigateur</li>
<li>Logs de connexion</li>
<li>Données d\'utilisation de l\'API</li>
</ul>

<h4>3.3 Données de licence</h4>
<ul>
<li>Clés de licence générées</li>
<li>Informations sur les produits</li>
<li>Données de validation</li>
</ul>

<h3>4. Finalités du Traitement</h3>
<p>Vos données sont utilisées pour :</p>
<ul>
<li><strong>Fourniture du service :</strong> Gestion des comptes, génération de licences</li>
<li><strong>Sécurité :</strong> Authentification, prévention de la fraude</li>
<li><strong>Support technique :</strong> Assistance et résolution des problèmes</li>
<li><strong>Amélioration du service :</strong> Analyse des performances et des usages</li>
<li><strong>Communication :</strong> Notifications importantes sur le service</li>
</ul>

<h3>5. Base Légale du Traitement</h3>
<p>Le traitement de vos données repose sur :</p>
<ul>
<li><strong>Exécution du contrat :</strong> Fourniture du service AdminLicence</li>
<li><strong>Intérêt légitime :</strong> Sécurité et amélioration du service</li>
<li><strong>Consentement :</strong> Communications marketing (optionnelles)</li>
<li><strong>Obligation légale :</strong> Conservation des logs pour la sécurité</li>
</ul>

<h3>6. Vos Droits (RGPD)</h3>
<p>Vous disposez des droits suivants :</p>
<ul>
<li><strong>Droit d\'accès :</strong> Consulter vos données personnelles</li>
<li><strong>Droit de rectification :</strong> Corriger vos données inexactes</li>
<li><strong>Droit à l\'effacement :</strong> Supprimer vos données</li>
<li><strong>Droit à la portabilité :</strong> Récupérer vos données</li>
<li><strong>Droit d\'opposition :</strong> Vous opposer au traitement</li>
<li><strong>Droit à la limitation :</strong> Limiter le traitement</li>
</ul>

<p><strong>Comment exercer vos droits :</strong> Contactez-nous à privacy@adminlicence.com avec une pièce d\'identité.</p>

<h3>7. Sécurité des Données</h3>
<p>Nous mettons en place des mesures de sécurité appropriées :</p>
<ul>
<li><strong>Chiffrement :</strong> HTTPS/TLS pour toutes les communications</li>
<li><strong>Authentification :</strong> Mots de passe cryptés, 2FA disponible</li>
<li><strong>Accès restreint :</strong> Seul le personnel autorisé peut accéder aux données</li>
<li><strong>Surveillance :</strong> Logs de sécurité et monitoring</li>
<li><strong>Sauvegardes :</strong> Sauvegardes sécurisées et régulières</li>
</ul>

<h3>8. Conservation des Données</h3>
<p>Nous conservons vos données selon les durées suivantes :</p>
<ul>
<li><strong>Données de compte :</strong> Tant que votre compte est actif</li>
<li><strong>Logs de sécurité :</strong> 12 mois maximum</li>
<li><strong>Données de licence :</strong> Durée de vie de la licence + 3 ans</li>
<li><strong>Données de support :</strong> 2 ans après résolution</li>
</ul>

<h3>9. Contact et Réclamations</h3>
<p><strong>Délégué à la Protection des Données :</strong></p>
<p><strong>Email :</strong> dpo@adminlicence.com</p>
<p><strong>Téléphone :</strong> +33 1 23 45 67 89</p>

<p><strong>Autorité de Contrôle :</strong> CNIL (Commission Nationale de l\'Informatique et des Libertés) - www.cnil.fr</p>

<p><em>Cette politique peut être modifiée. Les modifications importantes vous seront notifiées par email.</em></p>';
    }

    /**
     * Initialiser les données par défaut du CMS
     */
    public function initializeData()
    {
        try {
            // Créer les templates par défaut s'ils n'existent pas
            $this->createDefaultTemplates();
            
            // Créer les pages par défaut
            $this->createDefaultPages();
            
            // Créer du contenu de démonstration
            $this->createDemoContent();
            
            return redirect()->route('admin.cms.index')
                ->with('success', 'Données CMS initialisées avec succès.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.cms.index')
                ->with('error', 'Erreur lors de l\'initialisation: ' . $e->getMessage());
        }
    }

    /**
     * Créer les templates par défaut
     */
    private function createDefaultTemplates()
    {
        if (CmsTemplate::count() == 0) {
            CmsTemplate::create([
                'name' => 'modern',
                'display_name' => 'Template Moderne',
                'description' => 'Design moderne et épuré avec focus sur la sécurité',
                'config' => [
                    'primary_color' => '#2563eb',
                    'secondary_color' => '#64748b',
                    'accent_color' => '#059669',
                    'font_family' => 'Inter, sans-serif'
                ],
                'is_active' => true,
                'is_default' => true
            ]);

            CmsTemplate::create([
                'name' => 'professional',
                'display_name' => 'Template Professionnel',
                'description' => 'Design professionnel pour entreprises',
                'config' => [
                    'primary_color' => '#1e40af',
                    'secondary_color' => '#374151',
                    'accent_color' => '#dc2626',
                    'font_family' => 'Roboto, sans-serif'
                ],
                'is_active' => true,
                'is_default' => false
            ]);
        }
    }

    /**
     * Créer les pages par défaut
     */
    private function createDefaultPages()
    {
        if (CmsPage::count() == 0) {
            CmsPage::create([
                'name' => 'home',
                'title' => 'Accueil - AdminLicence',
                'slug' => 'accueil',
                'meta_description' => 'Solution sécurisée de gestion de licences logicielles',
                'is_active' => true,
                'sort_order' => 1
            ]);
        }
    }

    /**
     * Créer du contenu de démonstration
     */
    private function createDemoContent()
    {
        // Fonctionnalités de démonstration
        if (CmsFeature::count() == 0) {
            $features = [
                [
                    'title' => 'Sécurité Ultra-Avancée',
                    'description' => 'Chiffrement de bout en bout et validation cryptographique pour une sécurité maximale de vos licences.',
                    'icon' => 'fas fa-shield-alt',
                    'sort_order' => 1
                ],
                [
                    'title' => 'Gestion Centralisée',
                    'description' => 'Contrôlez toutes vos licences depuis une interface unique et intuitive.',
                    'icon' => 'fas fa-cogs',
                    'sort_order' => 2
                ],
                [
                    'title' => 'API Puissante',
                    'description' => 'Intégrez facilement AdminLicence dans vos applications avec notre API REST complète.',
                    'icon' => 'fas fa-code',
                    'sort_order' => 3
                ]
            ];

            foreach ($features as $feature) {
                CmsFeature::create($feature);
            }
        }

        // FAQ de démonstration
        if (CmsFaq::count() == 0) {
            $faqs = [
                [
                    'question' => 'Comment AdminLicence sécurise-t-il mes licences ?',
                    'answer' => 'AdminLicence utilise un chiffrement AES-256 et des signatures cryptographiques pour garantir l\'intégrité et la sécurité de vos licences.',
                    'category' => 'Sécurité',
                    'is_featured' => true,
                    'sort_order' => 1
                ],
                [
                    'question' => 'Puis-je intégrer AdminLicence dans mon application existante ?',
                    'answer' => 'Oui, notre API REST permet une intégration simple et rapide dans n\'importe quelle application.',
                    'category' => 'Intégration',
                    'sort_order' => 2
                ]
            ];

            foreach ($faqs as $faq) {
                CmsFaq::create($faq);
            }
        }
    }
}
