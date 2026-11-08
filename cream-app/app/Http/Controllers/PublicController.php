<?php

namespace App\Http\Controllers;

use App\Enums\AssetCategory;
use App\Models\Asset;
use App\Models\AssetRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        $featured = Asset::query()->defaultListings()->with('media')
            ->featured()->orderBy('sort_order')->take(6)->get();

        if ($featured->count() < 6) {
            $fill = Asset::query()->defaultListings()->with('media')
                ->whereNotIn('id', $featured->pluck('id'))
                ->orderByDesc('published_at')->take(6 - $featured->count())->get();
            $featured = $featured->concat($fill);
        }

        return view('public.home', [
            'title' => 'Credence Real Estate & Asset Management — Building Value. Creating Legacy.',
            'description' => 'Premium real estate, construction and asset management in Accra for Ghanaians at home and in the diaspora. View title-verified properties or talk to our team.',
            'canonical' => '/',
            'active' => '',
            'featured' => $featured,
            'reels' => $featured->take(4),
        ]);
    }

    public function properties(): View
    {
        $assets = Asset::query()->public()->with('media')->orderBy('sort_order')->get();
        $default = $assets->filter(fn ($a) => $a->status->inDefaultListings());

        $count = fn (callable $f) => $default->filter($f)->count();
        $chips = [
            ['label' => 'All', 'f' => 'all', 'count' => $default->count()],
            ['label' => 'Buy', 'f' => 'sale', 'count' => $count(fn ($a) => $a->category === AssetCategory::Sale)],
            ['label' => 'Rent', 'f' => 'rent', 'count' => $count(fn ($a) => $a->category === AssetCategory::Rent)],
            ['label' => 'Land', 'f' => 'land', 'count' => $count(fn ($a) => $a->category === AssetCategory::Land)],
            ['label' => 'Under construction', 'f' => 'under-construction', 'count' => $count(fn ($a) => $a->is_off_plan)],
            ['label' => 'Short stay', 'f' => 'short_stay', 'count' => $count(fn ($a) => $a->category === AssetCategory::ShortStay)],
            ['label' => 'Recently sold / let', 'f' => 'recently-sold', 'count' => $assets->count() - $default->count()],
        ];

        return view('public.properties', [
            'title' => 'Properties — Homes, Land & New Projects | Credence Real Estate',
            'description' => 'Browse title-verified homes for sale and rent, land plots, and new off-plan projects across Greater Accra with Credence Real Estate.',
            'canonical' => '/properties', 'active' => 'assets',
            'assets' => $assets, 'chips' => $chips, 'defaultCount' => $default->count(),
        ]);
    }

    public function buy(): View
    {
        $items = Asset::query()->defaultListings()->category(AssetCategory::Sale)->with('media')->orderBy('sort_order')->get();

        return view('public.grid-simple', [
            'title' => 'Buy a Home in Accra | Credence Real Estate',
            'description' => 'Title-verified homes for sale in East Legon, Ashale Botwe and across Greater Accra. View prices, photos and references with Credence Real Estate.',
            'canonical' => '/properties/buy', 'active' => 'assets',
            'items' => $items,
            'crumbs' => [['label' => 'Credence', 'href' => '/'], ['label' => 'Properties', 'href' => '/properties'], ['label' => 'Buy']],
            'h1' => 'Homes <em>for sale.</em>',
            'sub' => 'Title-verified homes for sale across Greater Accra — each one walked by our team and documented before listing.',
            'eyebrow' => $items->count().' homes for sale',
            'offMarket' => true,
        ]);
    }

    public function rent(): View
    {
        $items = Asset::query()->defaultListings()->category(AssetCategory::Rent)->with('media')->orderBy('sort_order')->get();

        return view('public.grid-simple', [
            'title' => 'Long-term Rentals in Accra | Credence Real Estate',
            'description' => 'Long-term rental homes in Accra, priced per month. Fully furnished and managed options from Credence Real Estate.',
            'canonical' => '/properties/rent', 'active' => 'assets',
            'items' => $items,
            'crumbs' => [['label' => 'Credence', 'href' => '/'], ['label' => 'Properties', 'href' => '/properties'], ['label' => 'Rent']],
            'h1' => 'Homes <em>to rent.</em>',
            'sub' => 'Long-term rentals in secure, well-connected Accra neighbourhoods. Prices are shown per month.',
            'eyebrow' => $items->count().' rental'.($items->count() === 1 ? '' : 's').' · priced per month',
            'emptyHtml' => 'No rentals available right now. New long-term rentals are added regularly. <a href="/contact?reason=buy">Tell us what you are looking for</a> and we will be in touch.',
            'offMarket' => false,
        ]);
    }

    public function land(): View
    {
        $plots = Asset::query()->defaultListings()->category(AssetCategory::Land)->with('media')->orderBy('sort_order')->get();

        return view('public.land', [
            'title' => 'Title-verified Land Plots in Accra | Credence Real Estate',
            'description' => 'Title-verified land plots in East Legon, Trasacco Valley and Tse Addo with tenure, survey and access detail. Request documents from Credence Real Estate.',
            'canonical' => '/properties/land', 'active' => 'assets', 'plots' => $plots,
        ]);
    }

    public function exclusives(): View
    {
        $items = Asset::query()->defaultListings()->exclusive()->with('media')->orderBy('sort_order')->get();

        return view('public.exclusives', [
            'title' => 'Exclusive & Off-market Listings | Credence Real Estate',
            'description' => 'Homes Credence represents exclusively, plus access to our off-market book. Submit a brief and we will match you privately.',
            'canonical' => '/exclusives', 'active' => 'assets', 'items' => $items,
        ]);
    }

    public function newProjects(): View
    {
        $items = Asset::query()->defaultListings()
            ->where(fn ($q) => $q->where('category', AssetCategory::NewProject->value)->orWhere('is_off_plan', true))
            ->with('media')->orderBy('sort_order')->get();

        return view('public.new-projects', [
            'title' => 'New Projects & Off-plan Homes in Accra | Credence Real Estate',
            'description' => 'Off-plan and under-construction developments in Accra, including Cantonments Court (delivery Q4 2026). Register interest for off-plan pricing.',
            'canonical' => '/new-projects', 'active' => 'assets', 'items' => $items, 'includePopup' => false,
        ]);
    }

    public function shortStays(): View
    {
        $items = Asset::query()->defaultListings()->category(AssetCategory::ShortStay)->with('media')->orderBy('sort_order')->get();

        return view('public.short-stays', [
            'title' => 'Short-stay Homes in Accra | Credence Real Estate',
            'description' => 'Furnished short-stay homes in Accra. Short-stay listings are coming soon — send your dates and guest count and we will match you.',
            'canonical' => '/short-stays', 'active' => 'assets', 'items' => $items,
        ]);
    }

    public function listing(string $slug): View|RedirectResponse
    {
        $asset = Asset::query()->public()->where('slug', $slug)->with('media')->first();

        if (! $asset) {
            $redirect = AssetRedirect::query()->where('old_slug', $slug)->with('asset')->first();
            if ($redirect && $redirect->asset && $redirect->asset->status->isPublic()) {
                return redirect('/listings/'.$redirect->asset->slug, 301);
            }
            abort(404);
        }

        return view('public.listing', [
            'asset' => $asset,
            'active' => 'assets',
            'title' => $asset->title.' — '.trim(explode('·', (string) $asset->location)[0]).' | Credence Real Estate',
            'description' => \Illuminate\Support\Str::limit($asset->summary ?: "{$asset->title} in {$asset->location}. {$asset->formattedPrice()}. Enquire with Credence Real Estate.", 157, ''),
            'canonical' => '/listings/'.$asset->slug,
            'ogImage' => $asset->cover()?->url(),
        ]);
    }

    /* ---------------- Static content pages ---------------- */

    public function services(): View
    {
        return view('public.services', ['active' => 'services',
            'title' => 'Services — Development, Sales, Management & Land | Credence Real Estate',
            'description' => 'Six real estate disciplines under one roof: development, sales & investment, property management, construction, interiors, and land due diligence in Accra.',
            'canonical' => '/services']);
    }

    public function about(): View
    {
        return view('public.about', ['active' => 'about',
            'title' => 'About — Credence Real Estate & Asset Management',
            'description' => 'Credence Real Estate & Asset Management, part of Credence Holdings Global, serves diaspora and local clients in Ghana with integrity, governance and end-to-end service.',
            'canonical' => '/about', 'ogImage' => '/assets/adeline-quarshie.jpeg']);
    }

    public function contact(): View
    {
        return view('public.contact', ['active' => 'contact',
            'title' => 'Contact — Book a Consultation | Credence Real Estate',
            'description' => 'Contact Credence Real Estate in Accra by phone, WhatsApp, email or form. Diaspora consultations by Zoom across UK and US time zones.',
            'canonical' => '/contact', 'includePopup' => false]);
    }

    public function sell(): View
    {
        return view('public.sell', ['active' => 'assets',
            'title' => 'Sell or Let Your Property | Credence Real Estate',
            'description' => 'List your Accra property for sale or to let with Credence Real Estate. Honest valuations, verified documentation and a qualified buyer network.',
            'canonical' => '/sell', 'includePopup' => false]);
    }

    public function searchDocument(): View
    {
        return view('public.search-document', ['active' => '',
            'title' => 'Land Document Search at the Lands Commission | Credence Real Estate',
            'description' => 'Official land document search in Ghana: we verify title, survey and encumbrances at the Lands Commission and send you a clear written report.',
            'canonical' => '/search-document', 'includePopup' => false]);
    }

    public function insights(): View
    {
        $walkthroughs = Asset::query()->public()->with('media')
            ->whereNotNull('video_url')->orderByDesc('published_at')->take(4)->get();
        if ($walkthroughs->isEmpty()) {
            $walkthroughs = Asset::query()->defaultListings()->with('media')->orderByDesc('published_at')->take(4)->get();
        }

        return view('public.insights', ['active' => '',
            'title' => 'Insights & the Signature Home Show | Credence Real Estate',
            'description' => 'The Signature Home Show, property walkthroughs, Accra market notes and the Credence blog — insight on buying and building in Ghana.',
            'canonical' => '/insights', 'walkthroughs' => $walkthroughs]);
    }

    public function blog(): View
    {
        // Articles & comments arrive in Phase 4; until then the blog shows the
        // Signature Home Show fallback (as the static site does with no posts).
        return view('public.blog', ['active' => '',
            'title' => 'Blog — Ghana Real Estate & Property Insight | Credence Real Estate',
            'description' => 'The Credence blog: practical writing on buying, building, renting and managing property in Ghana for local and diaspora clients.',
            'canonical' => '/blog']);
    }

    public function privacy(): View
    {
        return view('public.privacy', ['active' => '', 'includePopup' => false,
            'title' => 'Privacy Notice | Credence Real Estate',
            'description' => 'How Credence Real Estate collects, uses and protects information submitted through this website, and how to request deletion.',
            'canonical' => '/privacy']);
    }

    public function terms(): View
    {
        return view('public.terms', ['active' => '', 'includePopup' => false,
            'title' => 'Website Terms | Credence Real Estate',
            'description' => 'Website terms for Credence Real Estate: listings are subject to availability and verification, and nothing on the site is a binding offer.',
            'canonical' => '/terms']);
    }
}
