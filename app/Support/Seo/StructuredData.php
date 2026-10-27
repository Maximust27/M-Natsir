<?php

namespace App\Support\Seo;

use Illuminate\Http\Request;

final class StructuredData
{
    /**
     * Build one consistent JSON-LD graph for a public page.
     *
     * @return array<string, mixed>
     */
    public static function forPage(Request $request, string $title, string $description): array
    {
        $routeName = $request->route()?->getName();
        $canonical = PageMetadata::canonical($request);
        $websiteId = route('home').'#website';
        $personId = route('about').'#person';

        $page = [
            '@type' => $routeName === 'about' ? 'ProfilePage' : 'WebPage',
            '@id' => $canonical.'#webpage',
            'url' => $canonical,
            'name' => $title,
            'description' => $description,
            'inLanguage' => 'id-ID',
            'isPartOf' => ['@id' => $websiteId],
        ];

        $graph = [];

        if ($routeName === 'home') {
            // Google uses WebSite markup on the homepage to understand site names.
            $graph[] = [
                '@type' => 'WebSite',
                '@id' => $websiteId,
                'url' => route('home'),
                'name' => config('seo.site_name'),
                'description' => config('seo.default_description'),
                'inLanguage' => 'id-ID',
                'publisher' => ['@id' => $personId],
            ];

            $graph[] = self::person();
            $page['about'] = ['@id' => $personId];
        }

        if ($routeName === 'about') {
            // An "About Me" page can be a ProfilePage. Include the person
            // directly to make mainEntity's name and identity self-contained.
            $page['mainEntity'] = self::person();
        }

        $graph[] = $page;

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function person(): array
    {
        return [
            '@type' => 'Person',
            '@id' => route('about').'#person',
            'name' => config('seo.site_name'),
            'url' => route('about'),
            'description' => config('seo.person.description'),
            'image' => asset(config('seo.person.image')),
            'sameAs' => config('seo.person.same_as', []),
            'knowsAbout' => config('seo.person.knows_about', []),
        ];
    }
}
