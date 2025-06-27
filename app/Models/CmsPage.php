<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'meta_description',
        'slug',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Une page peut avoir plusieurs sections
     */
    public function sections(): HasMany
    {
        return $this->hasMany(CmsSection::class, 'page_id')->orderBy('sort_order');
    }

    /**
     * Sections actives seulement
     */
    public function activeSections(): HasMany
    {
        return $this->sections()->where('is_active', true);
    }

    /**
     * Scope pour les pages actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour ordonner par sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Récupérer une page par son nom
     */
    public static function getByName(string $name): ?self
    {
        return static::where('name', $name)->active()->first();
    }
}
