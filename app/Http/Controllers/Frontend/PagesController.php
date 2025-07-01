<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CmsAboutSection;
use App\Models\CmsPage;
use App\Models\CmsFeature;
use App\Models\CmsFaq;
use App\Models\CmsTestimonial;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PagesController extends Controller
{
    /**
     * Récupérer les paramètres du site
     */
    private function getSiteSettings(): array
    {
        return [
            'site_title' => 'AdminLicence',
            'site_tagline' => 'Système de gestion de licences ultra-sécurisé',
            'hero_title' => 'Sécurisez vos licences logicielles',
            'hero_subtitle' => 'Solution professionnelle de gestion de licences avec sécurité avancée',
            'contact_email' => 'contact@adminlicence.com',
            'contact_phone' => '+33 1 23 45 67 89',
            'footer_text' => '© 2025 AdminLicence. Solution sécurisée de gestion de licences.',
            'show_hero' => true,
            'show_features' => true,
            'show_testimonials' => true,
            'show_faqs' => true,
            'show_about' => true,
        ];
    }

    /**
     * Récupérer le template par défaut avec configuration
     */
    private function getDefaultTemplate()
    {
        // Créer un objet template simple avec méthode getConfigWithDefaults
        return new class {
            public function getConfigWithDefaults() {
                return [
                    'primary_color' => '#2563eb',
                    'secondary_color' => '#64748b', 
                    'accent_color' => '#059669',
                    'font_family' => 'Inter, sans-serif'
                ];
            }
        };
    }

    public function about()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        $page = CmsPage::where('slug', 'about')->first();
        $aboutSections = CmsAboutSection::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $features = CmsFeature::where('is_active', true)->take(6)->get();
        
        // Déterminer le template et la vue à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        $view = "frontend.templates.{$currentTemplate}.about";
        
        // Fallback vers template modern si la vue n'existe pas
        if (!view()->exists($view)) {
            $view = 'frontend.pages.about';
        }
        
        return view($view, compact('settings', 'template', 'page', 'aboutSections', 'features', 'layout'));
    }

    public function contact()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        $page = CmsPage::where('slug', 'contact')->first();
        $faqs = CmsFaq::where('is_active', true)->featured()->take(5)->get();
        
        // Déterminer le template et la vue à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        $view = "frontend.templates.{$currentTemplate}.contact";
        
        // Fallback vers template modern si la vue n'existe pas
        if (!view()->exists($view)) {
            $view = 'frontend.pages.contact';
        }
        
        return view($view, compact('settings', 'template', 'page', 'faqs', 'layout'));
    }

    public function pricing()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        $page = CmsPage::where('slug', 'pricing')->first();
        $features = CmsFeature::where('is_active', true)->take(6)->get();
        $testimonials = CmsTestimonial::where('is_active', true)->featured()->take(3)->get();
        
        // Déterminer le layout à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        
        return view('frontend.pages.pricing', compact('settings', 'template', 'page', 'features', 'testimonials', 'layout'));
    }

    public function privacy()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        $page = CmsPage::where('slug', 'privacy-policy')->first();
        
        // Déterminer le layout à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        
        return view('frontend.pages.privacy', compact('settings', 'template', 'page', 'layout'));
    }

    public function terms()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        $page = CmsPage::where('slug', 'terms-of-service')->first();
        
        // Déterminer le layout à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        
        return view('frontend.pages.terms', compact('settings', 'template', 'page', 'layout'));
    }

    public function documentation()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        $page = CmsPage::where('slug', 'documentation')->first();
        $faqs = CmsFaq::where('is_active', true)->orderBy('sort_order')->get();
        
        // Déterminer le layout à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        
        return view('documentation.index', compact('settings', 'template', 'page', 'faqs', 'layout'));
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000'
        ]);

        try {
            $contactData = $request->all();
            
            if (config('mail.default') !== 'array') {
                Mail::send('emails.contact', $contactData, function ($message) use ($contactData) {
                    $message->to(config('app.contact_email', 'admin@example.com'))
                            ->subject('Nouveau message : ' . $contactData['subject'])
                            ->replyTo($contactData['email'], $contactData['name']);
                });
            }

            return redirect()->route('frontend.contact')
                ->with('success', 'Votre message a été envoyé avec succès.');
                
        } catch (\Exception $e) {
            return redirect()->route('frontend.contact')
                ->with('error', 'Une erreur est survenue.');
        }
    }

    public function blog()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        // Page blog basique (peut être étendue avec un système de blog complet)
        $page = CmsPage::where('slug', 'blog')->first();
        
        // Articles de blog factices pour la démo
        $articles = [
            [
                'title' => 'Comment Protéger Votre Logiciel avec des Licences',
                'excerpt' => 'Guide complet sur les meilleures pratiques de protection logicielle...',
                'image' => asset('images/blog-1.jpg'),
                'date' => '2024-01-15',
                'author' => 'AdminLicence Team',
                'slug' => 'protection-logiciel-licences'
            ],
            [
                'title' => 'API de Validation : Intégration Facile',
                'excerpt' => 'Découvrez comment intégrer rapidement notre API de validation...',
                'image' => asset('images/blog-2.jpg'),
                'date' => '2024-01-10',
                'author' => 'AdminLicence Team',
                'slug' => 'api-validation-integration'
            ],
            [
                'title' => 'Sécurité des Licences : Tendances 2024',
                'excerpt' => 'Les dernières tendances en matière de sécurité des licences logicielles...',
                'image' => asset('images/blog-3.jpg'),
                'date' => '2024-01-05',
                'author' => 'AdminLicence Team',
                'slug' => 'securite-licences-tendances-2024'
            ]
        ];
        
        return view('frontend.pages.blog', compact('settings', 'template', 'page', 'articles'));
    }

    public function features()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        $page = CmsPage::where('slug', 'features')->first();
        $features = CmsFeature::where('is_active', true)->orderBy('sort_order')->get();
        $testimonials = CmsTestimonial::where('is_active', true)->featured()->take(3)->get();
        
        // Déterminer le template et la vue à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        $view = "frontend.templates.{$currentTemplate}.features";
        
        // Fallback vers template modern si la vue n'existe pas
        if (!view()->exists($view)) {
            $view = 'frontend.pages.features';
        }
        
        return view($view, compact('settings', 'template', 'page', 'features', 'testimonials', 'layout'));
    }

    public function demo()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        $page = CmsPage::where('slug', 'demo')->first();
        return view('frontend.pages.demo', compact('settings', 'template', 'page'));
    }

    public function submitDemo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string|max:1000'
        ]);

        try {
            $demoData = $request->all();
            
            // Envoi d'email pour demande de démo
            if (config('mail.default') !== 'array') {
                Mail::send('emails.demo-request', $demoData, function ($message) use ($demoData) {
                    $message->to(config('app.demo_email', 'demo@example.com'))
                            ->subject('Nouvelle demande de démo : ' . $demoData['company'])
                            ->replyTo($demoData['email'], $demoData['name']);
                });
            }

            return redirect()->route('frontend.demo')
                ->with('success', 'Votre demande de démo a été envoyée. Notre équipe vous contactera dans les 24h.');
                
        } catch (\Exception $e) {
            return redirect()->route('frontend.demo')
                ->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    public function support()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        $page = CmsPage::where('slug', 'support')->first();
        $faqs = CmsFaq::where('is_active', true)->featured()->take(5)->get();
        
        // Déterminer le layout à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        
        return view('frontend.pages.support', compact('settings', 'template', 'page', 'faqs', 'layout'));
    }

    public function faqs()
    {
        $settings = $this->getSiteSettings();
        $template = $this->getDefaultTemplate();
        $faqs = CmsFaq::where('is_active', true)->orderBy('sort_order')->get();
        $categories = CmsFaq::where('is_active', true)->distinct('category')->pluck('category')->filter();
        
        // Déterminer le layout à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        
        // Utiliser la vue faqs du template sélectionné
        $templateName = Setting::get('cms_current_template', 'modern');
        $view = "frontend.templates.{$templateName}.faqs";
        
        // Fallback vers template modern si la vue n'existe pas
        if (!view()->exists($view)) {
            $view = 'frontend.templates.modern.faqs';
        }
        
        return view($view, compact('settings', 'template', 'faqs', 'categories', 'layout'));
    }
} 