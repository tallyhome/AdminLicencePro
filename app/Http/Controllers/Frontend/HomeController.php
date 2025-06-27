<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CmsTemplate;
use App\Models\CmsFeature;
use App\Models\CmsFaq;
use App\Models\CmsTestimonial;
use App\Models\CmsAboutSection;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index(Request $request)
    {
        // Récupérer le template actuel
        $template = CmsTemplate::getDefault();
        
        if (!$template) {
            return view('frontend.no-template');
        }

        // Récupérer les paramètres du site
        $settings = $this->getSiteSettings();
        
        // Récupérer le contenu selon les sections activées
        $content = $this->getPageContent($settings);
        
        // Mode prévisualisation
        $isPreview = $request->has('preview') && auth('admin')->check();
        
        // Choisir la vue selon le template
        $view = "frontend.templates.{$template->name}.home";
        
        // Fallback vers template par défaut si la vue n'existe pas
        if (!view()->exists($view)) {
            $view = 'frontend.templates.modern.home';
        }
        
        return view($view, compact('template', 'settings', 'content', 'isPreview'));
    }

    /**
     * Récupérer les paramètres du site
     */
    private function getSiteSettings(): array
    {
        return [
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
    }

    /**
     * Récupérer le contenu des sections
     */
    private function getPageContent(array $settings): array
    {
        $content = [];

        // Fonctionnalités
        if ($settings['show_features']) {
            $content['features'] = CmsFeature::active()->ordered()->get();
        }

        // Témoignages
        if ($settings['show_testimonials']) {
            $content['testimonials'] = CmsTestimonial::active()->featured()->ordered()->limit(6)->get();
        }

        // FAQs
        if ($settings['show_faqs']) {
            $content['faqs'] = CmsFaq::active()->featured()->ordered()->limit(8)->get();
        }

        // Sections À propos
        if ($settings['show_about']) {
            $content['about_sections'] = CmsAboutSection::active()->ordered()->get();
        }

        return $content;
    }

    /**
     * Page À propos complète
     */
    public function about()
    {
        $template = CmsTemplate::getDefault();
        $settings = $this->getSiteSettings();
        $aboutSections = CmsAboutSection::active()->ordered()->get();

        $view = "frontend.templates.{$template->name}.about";
        if (!view()->exists($view)) {
            $view = 'frontend.templates.modern.about';
        }

        return view($view, compact('template', 'settings', 'aboutSections'));
    }

    /**
     * Page FAQs complète
     */
    public function faqs()
    {
        $template = CmsTemplate::getDefault();
        $settings = $this->getSiteSettings();
        $faqs = CmsFaq::active()->ordered()->get();
        $categories = CmsFaq::active()->distinct('category')->pluck('category')->filter();

        $view = "frontend.templates.{$template->name}.faqs";
        if (!view()->exists($view)) {
            $view = 'frontend.templates.modern.faqs';
        }

        return view($view, compact('template', 'settings', 'faqs', 'categories'));
    }

    /**
     * Page de contact
     */
    public function contact()
    {
        $template = CmsTemplate::getDefault();
        $settings = $this->getSiteSettings();

        $view = "frontend.templates.{$template->name}.contact";
        if (!view()->exists($view)) {
            $view = 'frontend.templates.modern.contact';
        }

        return view($view, compact('template', 'settings'));
    }

    /**
     * Soumettre le formulaire de contact
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Ici vous pouvez ajouter la logique d'envoi d'email
        // Pour l'instant, on simule juste la soumission

        return back()->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
    }
}
