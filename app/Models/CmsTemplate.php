<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'preview_image',
        'config',
        'is_active',
        'is_default'
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean'
    ];

    /**
     * Scope pour les templates actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Récupérer le template par défaut
     */
    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->active()->first() 
            ?? static::active()->first();
    }

    /**
     * Définir comme template par défaut
     */
    public function setAsDefault(): void
    {
        // Retirer le défaut des autres templates
        static::where('is_default', true)->update(['is_default' => false]);
        
        // Définir celui-ci comme défaut
        $this->update(['is_default' => true]);
    }

    /**
     * Obtenir la configuration avec les valeurs par défaut
     */
    public function getConfigWithDefaults(): array
    {
        $defaults = [
            'primary_color' => '#007bff',
            'secondary_color' => '#6c757d',
            'accent_color' => '#28a745',
            'font_family' => 'Inter, sans-serif',
            'border_radius' => '8px',
            'spacing' => 'normal'
        ];

        return array_merge($defaults, $this->config ?? []);
    }
}
