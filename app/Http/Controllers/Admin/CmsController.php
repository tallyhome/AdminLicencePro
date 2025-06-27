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
            'site_title' => Setting::get('cms_site_title', 'AdminLicence'),
            'site_tagline' => Setting::get('cms_site_tagline', 'Système de gestion de licences ultra-sécurisé'),
            'hero_title' => Setting::get('cms_hero_title', 'Sécurisez vos licences logicielles'),
            'hero_subtitle' => Setting::get('cms_hero_subtitle', 'Solution professionnelle de gestion de licences avec sécurité avancée'),
            'contact_email' => Setting::get('cms_contact_email', ''),
            'contact_phone' => Setting::get('cms_contact_phone', ''),
            'footer_text' => Setting::get('cms_footer_text', '© 2025 AdminLicence. Solution sécurisée de gestion de licences.'),
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
            'site_title' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:500',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'footer_text' => 'nullable|string|max:500',
        ]);

        // Sauvegarder les paramètres texte
        foreach ($validated as $key => $value) {
            Setting::set('cms_' . $key, $value);
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
        $templateId = $request->input('template_id');
        $template = CmsTemplate::findOrFail($templateId);
        
        // Désactiver tous les templates
        CmsTemplate::where('is_default', true)->update(['is_default' => false]);
        
        // Activer le nouveau template
        $template->update(['is_default' => true]);

        return redirect()->route('admin.cms.templates')
            ->with('success', 'Template changé avec succès.');
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
