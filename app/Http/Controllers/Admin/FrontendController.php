<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class FrontendController extends Controller
{
    /**
     * Afficher la page de gestion du frontend
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer tous les paramètres du frontend
        $frontendSettings = [
            // Textes principaux
            'app_name' => Setting::get('frontend_app_name', config('app.name')),
            'app_tagline' => Setting::get('frontend_app_tagline', 'Système de gestion de licences'),
            'hero_title' => Setting::get('frontend_hero_title', 'Gérez vos licences facilement'),
            'hero_subtitle' => Setting::get('frontend_hero_subtitle', 'Une solution complète pour la gestion de vos licences logicielles'),
            'footer_text' => Setting::get('frontend_footer_text', '© 2025 AdminLicence. Tous droits réservés.'),
            
            // Liens sociaux
            'social_facebook' => Setting::get('frontend_social_facebook', ''),
            'social_twitter' => Setting::get('frontend_social_twitter', ''),
            'social_linkedin' => Setting::get('frontend_social_linkedin', ''),
            'social_github' => Setting::get('frontend_social_github', ''),
            
            // Contact
            'contact_email' => Setting::get('frontend_contact_email', ''),
            'contact_phone' => Setting::get('frontend_contact_phone', ''),
            'contact_address' => Setting::get('frontend_contact_address', ''),
            
            // Couleurs et thème
            'primary_color' => Setting::get('frontend_primary_color', '#007bff'),
            'secondary_color' => Setting::get('frontend_secondary_color', '#6c757d'),
            'success_color' => Setting::get('frontend_success_color', '#28a745'),
            'danger_color' => Setting::get('frontend_danger_color', '#dc3545'),
            
            // Images actuelles
            'logo_url' => Setting::get('frontend_logo_url', asset('images/logo.png')),
            'hero_image_url' => Setting::get('frontend_hero_image_url', asset('images/dashboard-hero-2.png')),
            'favicon_url' => Setting::get('frontend_favicon_url', asset('favicon.ico')),
            
            // Fonctionnalités
            'show_hero_section' => Setting::get('frontend_show_hero_section', true),
            'show_features_section' => Setting::get('frontend_show_features_section', true),
            'show_contact_section' => Setting::get('frontend_show_contact_section', true),
            'maintenance_mode' => Setting::get('frontend_maintenance_mode', false),
            'maintenance_message' => Setting::get('frontend_maintenance_message', 'Site en maintenance, revenez bientôt !'),
        ];
        
        return view('admin.settings.frontend', compact('frontendSettings'));
    }

    /**
     * Mettre à jour les paramètres textuels du frontend
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTexts(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_tagline' => 'nullable|string|max:500',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'footer_text' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:500',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set('frontend_' . $key, $value);
        }

        return redirect()->route('admin.settings.frontend')
            ->with('success', 'Les textes du frontend ont été mis à jour avec succès.');
    }

    /**
     * Mettre à jour les liens sociaux
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSocial(Request $request)
    {
        $validated = $request->validate([
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_github' => 'nullable|url|max:255',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set('frontend_' . $key, $value);
        }

        return redirect()->route('admin.settings.frontend')
            ->with('success', 'Les liens sociaux ont été mis à jour avec succès.');
    }

    /**
     * Mettre à jour les couleurs du thème
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateColors(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'required|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'secondary_color' => 'required|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'success_color' => 'required|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'danger_color' => 'required|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set('frontend_' . $key, $value);
        }

        // Générer le CSS personnalisé
        $this->generateCustomCSS($validated);

        return redirect()->route('admin.settings.frontend')
            ->with('success', 'Les couleurs du thème ont été mises à jour avec succès.');
    }

    /**
     * Mettre à jour une image (logo, hero, favicon)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateImage(Request $request)
    {
        $request->validate([
            'image_type' => 'required|in:logo,hero_image,favicon',
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,ico|max:2048',
        ]);

        $imageType = $request->image_type;
        $file = $request->file('image_file');
        
        try {
            // Définir le nom et le chemin du fichier
            $fileName = $imageType . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = 'frontend/' . $fileName;
            
            // Sauvegarder le fichier
            Storage::disk('public')->put($path, File::get($file));
            
            // Mettre à jour l'URL dans les settings
            $imageUrl = Storage::disk('public')->url($path);
            Setting::set('frontend_' . $imageType . '_url', $imageUrl);
            
            return redirect()->route('admin.settings.frontend')
                ->with('success', 'L\'image a été mise à jour avec succès.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.frontend')
                ->with('error', 'Erreur lors de la mise à jour de l\'image : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour les fonctionnalités d'affichage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFeatures(Request $request)
    {
        $features = [
            'show_hero_section' => $request->has('show_hero_section'),
            'show_features_section' => $request->has('show_features_section'),
            'show_contact_section' => $request->has('show_contact_section'),
            'maintenance_mode' => $request->has('maintenance_mode'),
        ];

        if ($request->filled('maintenance_message')) {
            Setting::set('frontend_maintenance_message', $request->maintenance_message);
        }

        foreach ($features as $key => $value) {
            Setting::set('frontend_' . $key, $value);
        }

        return redirect()->route('admin.settings.frontend')
            ->with('success', 'Les fonctionnalités d\'affichage ont été mises à jour avec succès.');
    }

    /**
     * Réinitialiser les paramètres du frontend aux valeurs par défaut
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset()
    {
        // Supprimer tous les paramètres frontend
        Setting::where('key', 'LIKE', 'frontend_%')->delete();

        // Supprimer le CSS personnalisé
        Storage::disk('public')->delete('frontend/custom.css');

        return redirect()->route('admin.settings.frontend')
            ->with('success', 'Les paramètres du frontend ont été réinitialisés aux valeurs par défaut.');
    }

    /**
     * Prévisualiser les changements
     *
     * @return \Illuminate\View\View
     */
    public function preview()
    {
        return view('frontend.preview');
    }

    /**
     * Générer le fichier CSS personnalisé avec les couleurs
     *
     * @param array $colors
     * @return void
     */
    private function generateCustomCSS(array $colors)
    {
        $css = ":root {\n";
        foreach ($colors as $key => $value) {
            $cssVar = '--' . str_replace('_', '-', $key);
            $css .= "    {$cssVar}: {$value};\n";
        }
        $css .= "}\n\n";
        
        $css .= "/* Couleurs primaires */\n";
        $css .= ".btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }\n";
        $css .= ".bg-primary { background-color: var(--primary-color) !important; }\n";
        $css .= ".text-primary { color: var(--primary-color) !important; }\n";
        $css .= ".border-primary { border-color: var(--primary-color) !important; }\n\n";
        
        $css .= "/* Couleurs secondaires */\n";
        $css .= ".btn-secondary { background-color: var(--secondary-color); border-color: var(--secondary-color); }\n";
        $css .= ".bg-secondary { background-color: var(--secondary-color) !important; }\n";
        $css .= ".text-secondary { color: var(--secondary-color) !important; }\n\n";
        
        $css .= "/* Couleurs de succès */\n";
        $css .= ".btn-success { background-color: var(--success-color); border-color: var(--success-color); }\n";
        $css .= ".bg-success { background-color: var(--success-color) !important; }\n";
        $css .= ".text-success { color: var(--success-color) !important; }\n\n";
        
        $css .= "/* Couleurs de danger */\n";
        $css .= ".btn-danger { background-color: var(--danger-color); border-color: var(--danger-color); }\n";
        $css .= ".bg-danger { background-color: var(--danger-color) !important; }\n";
        $css .= ".text-danger { color: var(--danger-color) !important; }\n";

        // Sauvegarder le fichier CSS
        Storage::disk('public')->put('frontend/custom.css', $css);
    }
} 