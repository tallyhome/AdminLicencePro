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
        // Récupérer les paramètres du site
        $settings = $this->getSiteSettings();
        
        // Récupérer le contenu selon les sections activées
        $content = $this->getPageContent($settings);
        
        // Mode prévisualisation
        $isPreview = $request->has('preview');
        
        // Déterminer le template à utiliser
        $templateName = $request->get('template', Setting::get('cms_current_template', 'modern'));
        
        // Templates disponibles
        $availableTemplates = [
            'modern' => 'frontend.templates.modern.home',
            'professional' => 'frontend.templates.professional.home'
        ];
        
        // Choisir la vue selon le template
        $view = $availableTemplates[$templateName] ?? $availableTemplates['modern'];
        
        // Fallback vers template par défaut si la vue n'existe pas
        if (!view()->exists($view)) {
            $view = 'frontend.templates.modern.home';
        }
        
        // Créer un objet template simple
        $template = (object) [
            'name' => $templateName,
            'display_name' => ucfirst($templateName)
        ];
        
        return view($view, compact('template', 'settings', 'content', 'isPreview'));
    }

    /**
     * Récupérer les paramètres du site
     */
    private function getSiteSettings(): array
    {
        return [
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
            'terms_content' => Setting::get('cms_terms_content', ''),
            'privacy_content' => Setting::get('cms_privacy_content', ''),
            
            // Options d'affichage
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
        $templateName = Setting::get('cms_current_template', 'modern');
        $settings = $this->getSiteSettings();
        $aboutSections = CmsAboutSection::active()->ordered()->get();

        $view = "frontend.templates.{$templateName}.about";
        if (!view()->exists($view)) {
            $view = 'frontend.templates.modern.about';
        }

        $template = (object) ['name' => $templateName];

        return view($view, compact('template', 'settings', 'aboutSections'));
    }

    /**
     * Page FAQs complète
     */
    public function faqs()
    {
        $templateName = Setting::get('cms_current_template', 'modern');
        $settings = $this->getSiteSettings();
        $faqs = CmsFaq::active()->ordered()->get();
        $categories = CmsFaq::active()->distinct('category')->pluck('category')->filter();

        $view = "frontend.templates.{$templateName}.faqs";
        if (!view()->exists($view)) {
            $view = 'frontend.templates.modern.faqs';
        }

        $template = (object) ['name' => $templateName];

        return view($view, compact('template', 'settings', 'faqs', 'categories'));
    }

    /**
     * Page de contact
     */
    public function contact()
    {
        $templateName = Setting::get('cms_current_template', 'modern');
        $settings = $this->getSiteSettings();

        $view = "frontend.templates.{$templateName}.contact";
        if (!view()->exists($view)) {
            $view = 'frontend.templates.modern.contact';
        }

        $template = (object) ['name' => $templateName];

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
