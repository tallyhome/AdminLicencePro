<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DocumentationController extends Controller
{
    public function index()
    {
        // Récupérer les paramètres du site
        $settings = $this->getSiteSettings();
        
        // Déterminer le layout à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        
        return view('documentation.index', compact('settings', 'layout'));
    }

    /**
     * Récupérer les paramètres du site
     */
    private function getSiteSettings(): array
    {
        return [
            'site_title' => Setting::get('site_title', 'AdminLicence'),
            'site_tagline' => Setting::get('site_tagline', 'Système de gestion de licences ultra-sécurisé'),
            'contact_email' => Setting::get('contact_email', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'footer_text' => Setting::get('footer_text', '© 2025 AdminLicence. Solution sécurisée de gestion de licences.'),
            'nav_home_text' => Setting::get('nav_home_text', 'Accueil'),
            'nav_features_text' => Setting::get('nav_features_text', 'Fonctionnalités'),
            'nav_pricing_text' => Setting::get('nav_pricing_text', 'Tarifs'),
            'nav_about_text' => Setting::get('nav_about_text', 'À propos'),
            'nav_faq_text' => Setting::get('nav_faq_text', 'FAQ'),
            'nav_contact_text' => Setting::get('nav_contact_text', 'Contact'),
            'nav_support_text' => Setting::get('nav_support_text', 'Support'),
            'nav_admin_text' => Setting::get('nav_admin_text', 'Admin'),
        ];
    }

    public function apiIntegration()
    {
        $markdownPath = base_path('docs/API_INTEGRATION.md');
        
        if (!File::exists($markdownPath)) {
            abort(404, 'Documentation not found');
        }
        
        $markdown = File::get($markdownPath);
        
        // Conversion améliorée du Markdown en HTML avec ancres
        $html = $this->convertMarkdownToHtml($markdown);
        
        // Récupérer les paramètres du site
        $settings = $this->getSiteSettings();
        
        // Déterminer le layout à utiliser
        $currentTemplate = Setting::get('cms_current_template', 'modern');
        $layout = "frontend.templates.{$currentTemplate}.layout";
        
        return view('documentation.api', [
            'content' => $html,
            'settings' => $settings,
            'layout' => $layout
        ]);
    }

    private function convertMarkdownToHtml($markdown)
    {
        // Fonction pour créer un ID d'ancre à partir d'un titre
        $createAnchor = function($text) {
            // Normaliser le texte français avec accents
            $text = str_replace([
                'é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'ç', 'î', 'ï', 'ô', 'ö', 'ù', 'û', 'ü', 'ÿ',
                'É', 'È', 'Ê', 'Ë', 'À', 'Â', 'Ä', 'Ç', 'Î', 'Ï', 'Ô', 'Ö', 'Ù', 'Û', 'Ü', 'Ÿ'
            ], [
                'e', 'e', 'e', 'e', 'a', 'a', 'a', 'c', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'y',
                'e', 'e', 'e', 'e', 'a', 'a', 'a', 'c', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'y'
            ], $text);
            
            return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
        };

        // Titres avec ancres
        $html = preg_replace_callback('/^# (.+)$/m', function($matches) use ($createAnchor) {
            $id = $createAnchor($matches[1]);
            return '<h1 id="' . $id . '">' . $matches[1] . '</h1>';
        }, $markdown);

        $html = preg_replace_callback('/^## (.+)$/m', function($matches) use ($createAnchor) {
            $id = $createAnchor($matches[1]);
            return '<h2 id="' . $id . '">' . $matches[1] . '</h2>';
        }, $html);

        $html = preg_replace_callback('/^### (.+)$/m', function($matches) use ($createAnchor) {
            $id = $createAnchor($matches[1]);
            return '<h3 id="' . $id . '">' . $matches[1] . '</h3>';
        }, $html);

        $html = preg_replace_callback('/^#### (.+)$/m', function($matches) use ($createAnchor) {
            $id = $createAnchor($matches[1]);
            return '<h4 id="' . $id . '">' . $matches[1] . '</h4>';
        }, $html);

        // Code blocks avec gestion des langages
        $html = preg_replace('/```(\w+)\n([\s\S]*?)```/m', '<pre><code class="language-$1">$2</code></pre>', $html);
        $html = preg_replace('/```\n([\s\S]*?)```/m', '<pre><code>$1</code></pre>', $html);
        $html = preg_replace('/```([\s\S]*?)```/m', '<pre><code>$1</code></pre>', $html);

        // Inline code
        $html = preg_replace('/`([^`]+)`/', '<code>$1</code>', $html);

        // Liens (éviter de modifier les liens d'ancrage internes)
        $html = preg_replace('/\[([^\]]+)\]\(([^#\)][^\)]*)\)/', '<a href="$2" target="_blank">$1</a>', $html);

        // Gras et italique
        $html = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*([^*]+)\*/', '<em>$1</em>', $html);

        // Listes numérotées
        $html = preg_replace('/^(\d+)\. (.+)$/m', '<li>$2</li>', $html);
        
        // Listes à puces
        $html = preg_replace('/^- (.+)$/m', '<li>$1</li>', $html);
        
        // Grouper les listes
        $html = preg_replace('/(<li>.+<\/li>\n?)+/m', '<ul>$0</ul>', $html);

        // Tableaux améliorés
        $html = preg_replace_callback('/^\|(.+)\|$/m', function($matches) {
            $cells = explode('|', trim($matches[1], '|'));
            $row = '<tr>';
            foreach($cells as $cell) {
                $row .= '<td>' . trim($cell) . '</td>';
            }
            $row .= '</tr>';
            return $row;
        }, $html);

        // Grouper les tableaux
        $html = preg_replace('/(<tr>.+<\/tr>\n?)+/m', '<table class="table table-bordered table-striped">$0</table>', $html);

        // Citations
        $html = preg_replace('/^> (.+)$/m', '<blockquote>$1</blockquote>', $html);

        // Lignes horizontales
        $html = preg_replace('/^---$/m', '<hr>', $html);

        // Paragraphes (en dernier pour éviter de casser les autres éléments)
        $lines = explode("\n", $html);
        $result = [];
        $inParagraph = false;
        
        foreach($lines as $line) {
            $trimmed = trim($line);
            
            // Ignorer les lignes vides
            if (empty($trimmed)) {
                if ($inParagraph) {
                    $result[] = '</p>';
                    $inParagraph = false;
                }
                $result[] = $line;
                continue;
            }
            
            // Vérifier si c'est un élément de bloc
            if (preg_match('/^<(h[1-6]|ul|ol|li|table|tr|td|th|pre|code|blockquote|hr)/', $trimmed)) {
                if ($inParagraph) {
                    $result[] = '</p>';
                    $inParagraph = false;
                }
                $result[] = $line;
            } else {
                // C'est du texte normal
                if (!$inParagraph) {
                    $result[] = '<p>' . $line;
                    $inParagraph = true;
                } else {
                    $result[] = $line;
                }
            }
        }
        
        // Fermer le dernier paragraphe si nécessaire
        if ($inParagraph) {
            $result[] = '</p>';
        }

        return implode("\n", $result);
    }
}
