<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CmsTemplate;
use App\Models\CmsPage;
use App\Models\CmsFeature;
use App\Models\CmsFaq;
use App\Models\CmsTestimonial;
use App\Models\CmsAboutSection;
use App\Models\Setting;

class CmsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les templates par défaut
        $this->createTemplates();
        
        // Créer les pages
        $this->createPages();
        
        // Créer les fonctionnalités
        $this->createFeatures();
        
        // Créer les FAQs
        $this->createFaqs();
        
        // Créer les témoignages
        $this->createTestimonials();
        
        // Créer les sections À propos
        $this->createAboutSections();
        
        // Créer les paramètres
        $this->createSettings();
        
        $this->command->info('Données CMS initialisées avec succès !');
    }

    private function createTemplates()
    {
        CmsTemplate::updateOrCreate(['name' => 'modern'], [
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

        CmsTemplate::updateOrCreate(['name' => 'professional'], [
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

    private function createPages()
    {
        CmsPage::updateOrCreate(['name' => 'home'], [
            'title' => 'Accueil - AdminLicence',
            'slug' => 'accueil',
            'meta_description' => 'Solution sécurisée de gestion de licences logicielles',
            'is_active' => true,
            'sort_order' => 1
        ]);
    }

    private function createFeatures()
    {
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
            ],
            [
                'title' => 'Monitoring en Temps Réel',
                'description' => 'Surveillez l\'utilisation de vos licences en temps réel avec des tableaux de bord détaillés.',
                'icon' => 'fas fa-chart-line',
                'sort_order' => 4
            ],
            [
                'title' => 'Protection Anti-Piratage',
                'description' => 'Système de détection avancé contre le piratage et l\'utilisation frauduleuse.',
                'icon' => 'fas fa-user-shield',
                'sort_order' => 5
            ],
            [
                'title' => 'Support 24/7',
                'description' => 'Équipe de support disponible 24h/24 et 7j/7 pour vous accompagner.',
                'icon' => 'fas fa-headset',
                'sort_order' => 6
            ]
        ];

        foreach ($features as $feature) {
            CmsFeature::updateOrCreate(
                ['title' => $feature['title']],
                $feature
            );
        }
    }

    private function createFaqs()
    {
        $faqs = [
            [
                'question' => 'Comment AdminLicence sécurise-t-il mes licences ?',
                'answer' => 'AdminLicence utilise un chiffrement AES-256 et des signatures cryptographiques pour garantir l\'intégrité et la sécurité de vos licences. Chaque licence est vérifiée en temps réel avec notre infrastructure sécurisée.',
                'category' => 'Sécurité',
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'question' => 'Puis-je intégrer AdminLicence dans mon application existante ?',
                'answer' => 'Oui, notre API REST permet une intégration simple et rapide dans n\'importe quelle application. Nous fournissons des SDK pour les langages les plus populaires.',
                'category' => 'Intégration',
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'question' => 'Combien de licences puis-je gérer ?',
                'answer' => 'AdminLicence peut gérer un nombre illimité de licences. Notre infrastructure est conçue pour supporter des milliers de validations simultanées.',
                'category' => 'Capacité',
                'is_featured' => true,
                'sort_order' => 3
            ],
            [
                'question' => 'Quel est le délai de réponse de l\'API ?',
                'answer' => 'Notre API répond en moins de 100ms dans 99% des cas. Nous avons des serveurs distribués géographiquement pour garantir une latence minimale.',
                'category' => 'Performance',
                'sort_order' => 4
            ],
            [
                'question' => 'Proposez-vous un support technique ?',
                'answer' => 'Oui, nous proposons un support technique 24/7 par email et chat. Nous offrons également des sessions de formation pour optimiser l\'utilisation de votre système.',
                'category' => 'Support',
                'sort_order' => 5
            ]
        ];

        foreach ($faqs as $faq) {
            CmsFaq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }

    private function createTestimonials()
    {
        $testimonials = [
            [
                'name' => 'Marie Dupont',
                'position' => 'CTO',
                'company' => 'TechSoft Solutions',
                'content' => 'AdminLicence a révolutionné notre gestion de licences. La sécurité est exceptionnelle et l\'intégration était très simple.',
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Jean Martin',
                'position' => 'Développeur Senior',
                'company' => 'InnovateLab',
                'content' => 'L\'API est remarquablement bien documentée et performante. Nous avons réduit les fraudes de 95% depuis l\'implémentation.',
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Sophie Bernard',
                'position' => 'Product Manager',
                'company' => 'SecureApps',
                'content' => 'Le support client est fantastique. L\'équipe AdminLicence nous a accompagnés tout au long de l\'intégration.',
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($testimonials as $testimonial) {
            CmsTestimonial::updateOrCreate(
                ['name' => $testimonial['name'], 'company' => $testimonial['company']],
                $testimonial
            );
        }
    }

    private function createAboutSections()
    {
        $sections = [
            [
                'title' => 'Notre Mission',
                'content' => 'AdminLicence a pour mission de fournir la solution de gestion de licences la plus sécurisée et la plus fiable du marché. Nous croyons que chaque développeur mérite de protéger efficacement son travail.',
                'section_type' => 'text',
                'sort_order' => 1
            ],
            [
                'title' => 'Innovation et Sécurité',
                'content' => 'Depuis notre création, nous innovons constamment pour rester à la pointe de la sécurité. Notre équipe de chercheurs en sécurité travaille en permanence pour anticiper les nouvelles menaces.',
                'section_type' => 'text',
                'sort_order' => 2
            ]
        ];

        foreach ($sections as $section) {
            CmsAboutSection::updateOrCreate(
                ['title' => $section['title']],
                $section
            );
        }
    }

    private function createSettings()
    {
        $settings = [
            'cms_site_title' => 'AdminLicence',
            'cms_site_tagline' => 'Système de gestion de licences ultra-sécurisé',
            'cms_hero_title' => 'Sécurisez vos licences logicielles',
            'cms_hero_subtitle' => 'Solution professionnelle de gestion de licences avec sécurité avancée',
            'cms_contact_email' => 'contact@adminlicence.com',
            'cms_contact_phone' => '+33 1 23 45 67 89',
            'cms_footer_text' => '© 2025 AdminLicence. Solution sécurisée de gestion de licences.',
            'cms_show_hero' => true,
            'cms_show_features' => true,
            'cms_show_testimonials' => true,
            'cms_show_faqs' => true,
            'cms_show_about' => true,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : $value]
            );
        }
    }
}
