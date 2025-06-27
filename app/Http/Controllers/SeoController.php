<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\CmsFeature;
use App\Models\CmsFaq;
use App\Models\CmsTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class SeoController extends Controller
{
    /**
     * Génère le sitemap XML
     */
    public function sitemap()
    {
        $sitemap = Cache::remember('sitemap', 3600, function () {
            $urls = collect();
            
            // Pages principales
            $mainPages = [
                ['url' => route('frontend.home'), 'priority' => '1.0', 'freq' => 'weekly'],
                ['url' => route('frontend.about'), 'priority' => '0.8', 'freq' => 'monthly'],
                ['url' => route('frontend.contact'), 'priority' => '0.7', 'freq' => 'monthly'],
                ['url' => route('frontend.pricing'), 'priority' => '0.9', 'freq' => 'weekly'],
            ];
            
            foreach ($mainPages as $page) {
                $urls->push([
                    'url' => $page['url'],
                    'lastmod' => now()->format('Y-m-d'),
                    'priority' => $page['priority'],
                    'changefreq' => $page['freq']
                ]);
            }
            
            return $urls;
        });
        
        $xml = view('seo.sitemap', compact('sitemap'))->render();
        
        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    /**
     * Génère le fichier robots.txt
     */
    public function robots()
    {
        $robots = Cache::remember('robots', 3600, function () {
            $content = "User-agent: *\n";
            $content .= "Disallow: /admin/\n";
            $content .= "Disallow: /api/\n";
            $content .= "Allow: /\n\n";
            $content .= "Sitemap: " . route('sitemap') . "\n";
            
            return $content;
        });
        
        return Response::make($robots, 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
    
    /**
     * Méta données SEO pour une page
     */
    public static function getPageSeoData($page = 'home', $customData = [])
    {
        $defaultSeo = [
            'title' => config('app.name', 'AdminLicence'),
            'description' => 'Solution professionnelle de gestion de licences logicielles.',
            'keywords' => 'licences, logiciels, sécurité, API, AdminLicence',
            'image' => asset('images/og-image.jpg'),
            'url' => url()->current(),
            'type' => 'website'
        ];
        
        $pageSeo = [
            'home' => [
                'title' => 'AdminLicence - Gestion Professionnelle de Licences',
                'description' => 'Protégez et gérez vos licences logicielles avec AdminLicence.',
            ],
            'about' => [
                'title' => 'À propos - AdminLicence',
                'description' => 'Découvrez AdminLicence, leader en solutions de protection logicielle.',
            ],
            'contact' => [
                'title' => 'Contact - AdminLicence',
                'description' => 'Contactez notre équipe d\'experts AdminLicence.',
            ],
            'pricing' => [
                'title' => 'Tarifs - AdminLicence',
                'description' => 'Découvrez nos plans tarifaires transparents.',
            ]
        ];
        
        return array_merge($defaultSeo, $pageSeo[$page] ?? [], $customData);
    }
    
    /**
     * Génère les balises JSON-LD pour le SEO structuré
     */
    public static function generateJsonLd($type = 'organization', $data = [])
    {
        $jsonLd = [];
        
        switch ($type) {
            case 'organization':
                $jsonLd = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => 'AdminLicence',
                    'url' => url('/'),
                    'logo' => asset('images/logo.png'),
                    'description' => 'Solution professionnelle de gestion de licences logicielles',
                    'sameAs' => [
                        // Réseaux sociaux si disponibles
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'telephone' => '+33-1-23-45-67-89',
                        'contactType' => 'Support technique',
                        'email' => 'support@adminlicence.com'
                    ]
                ];
                break;
                
            case 'software':
                $jsonLd = [
                    '@context' => 'https://schema.org',
                    '@type' => 'SoftwareApplication',
                    'name' => 'AdminLicence',
                    'description' => 'Plateforme de gestion de licences logicielles',
                    'url' => url('/'),
                    'applicationCategory' => 'Business Software',
                    'operatingSystem' => 'Web-based',
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => '29',
                        'priceCurrency' => 'EUR',
                        'availability' => 'https://schema.org/InStock'
                    ]
                ];
                break;
                
            case 'faq':
                if (isset($data['faqs'])) {
                    $faqItems = [];
                    foreach ($data['faqs'] as $faq) {
                        $faqItems[] = [
                            '@type' => 'Question',
                            'name' => $faq->question,
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => $faq->answer
                            ]
                        ];
                    }
                    
                    $jsonLd = [
                        '@context' => 'https://schema.org',
                        '@type' => 'FAQPage',
                        'mainEntity' => $faqItems
                    ];
                }
                break;
                
            case 'review':
                if (isset($data['testimonials'])) {
                    $reviews = [];
                    foreach ($data['testimonials'] as $testimonial) {
                        $reviews[] = [
                            '@type' => 'Review',
                            'author' => [
                                '@type' => 'Person',
                                'name' => $testimonial->name
                            ],
                            'reviewRating' => [
                                '@type' => 'Rating',
                                'ratingValue' => $testimonial->rating,
                                'bestRating' => 5
                            ],
                            'reviewBody' => $testimonial->content
                        ];
                    }
                    
                    $jsonLd = [
                        '@context' => 'https://schema.org',
                        '@type' => 'Product',
                        'name' => 'AdminLicence',
                        'review' => $reviews,
                        'aggregateRating' => [
                            '@type' => 'AggregateRating',
                            'ratingValue' => '4.8',
                            'reviewCount' => count($reviews)
                        ]
                    ];
                }
                break;
        }
        
        return array_merge($jsonLd, $data);
    }
    
    /**
     * Analytics et tracking
     */
    public static function getAnalyticsCode()
    {
        $googleAnalyticsId = config('seo.google_analytics_id');
        $facebookPixelId = config('seo.facebook_pixel_id');
        
        $scripts = [];
        
        // Google Analytics
        if ($googleAnalyticsId) {
            $scripts[] = "
                <!-- Google Analytics -->
                <script async src=\"https://www.googletagmanager.com/gtag/js?id={$googleAnalyticsId}\"></script>
                <script>
                  window.dataLayer = window.dataLayer || [];
                  function gtag(){dataLayer.push(arguments);}
                  gtag('js', new Date());
                  gtag('config', '{$googleAnalyticsId}');
                </script>
            ";
        }
        
        // Facebook Pixel
        if ($facebookPixelId) {
            $scripts[] = "
                <!-- Facebook Pixel -->
                <script>
                  !function(f,b,e,v,n,t,s)
                  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                  n.queue=[];t=b.createElement(e);t.async=!0;
                  t.src=v;s=b.getElementsByTagName(e)[0];
                  s.parentNode.insertBefore(t,s)}(window, document,'script',
                  'https://connect.facebook.net/en_US/fbevents.js');
                  fbq('init', '{$facebookPixelId}');
                  fbq('track', 'PageView');
                </script>
            ";
        }
        
        return implode("\n", $scripts);
    }
} 