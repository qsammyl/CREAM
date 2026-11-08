<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticPaths = [
            '/', '/about', '/services', '/properties', '/contact',
            '/properties/buy', '/properties/rent', '/properties/land',
            '/sell', '/exclusives', '/new-projects', '/short-stays',
            '/search-document', '/insights', '/blog', '/privacy', '/terms',
        ];

        $today = now()->toDateString();
        $urls = [];

        foreach ($staticPaths as $path) {
            $urls[] = ['loc' => url($path), 'lastmod' => $today];
        }

        foreach (Asset::query()->public()->orderBy('slug')->get(['slug', 'updated_at']) as $asset) {
            $urls[] = [
                'loc' => url('/listings/'.$asset->slug),
                'lastmod' => $asset->updated_at?->toDateString() ?? $today,
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $u) {
            $xml .= '  <url><loc>'.e($u['loc']).'</loc><lastmod>'.$u['lastmod'].'</lastmod></url>'."\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $txt = "User-agent: *\nAllow: /\nDisallow: /staff\n\nSitemap: ".url('/sitemap.xml')."\n";

        return response($txt, 200)->header('Content-Type', 'text/plain');
    }
}
