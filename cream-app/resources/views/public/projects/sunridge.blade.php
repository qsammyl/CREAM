@extends('public.layout')

@php
    // All facts below are taken from the partner project decks (V1/V2) — nothing invented.
    // Prices and handover dates are intentionally "on request" so every lead reaches Credence.
    $base = '/assets/projects/sunridge';

    $stats = [
        ['n' => '80',  'suf' => '', 'label' => 'Acres, master-planned'],
        ['n' => '500', 'suf' => '', 'label' => 'Homes released to the public'],
        ['n' => '556', 'suf' => '', 'label' => 'Residences in the plan'],
        ['n' => '30',  'suf' => '%', 'label' => 'Green & open space'],
        ['n' => '4',   'suf' => '', 'label' => 'Home designs'],
        ['n' => '11',  'suf' => 'km', 'label' => 'To Kotoka Airport'],
    ];

    $units = [
        [
            'key' => 'a', 'tag' => 'Type A', 'kind' => 'Detached villa', 'img' => $base.'/villa-a.jpg',
            'gfa' => '183.57', 'ground' => '89.33', 'upper' => '94.24', 'balcony' => '16.53',
            'blurb' => 'A two-storey detached villa set in its own walled garden, with a private swimming pool, outdoor barbecue deck and lounge terrace.',
            'features' => ['Private courtyard & garden', 'Private swimming pool', 'Outdoor barbecue & lounge deck', 'Auxiliary / staff room', 'Double "parent-and-child" parking'],
        ],
        [
            'key' => 'c', 'tag' => 'Type C', 'kind' => 'Detached villa', 'img' => $base.'/villa-c.jpg',
            'gfa' => '230.82', 'ground' => '121.34', 'upper' => '109.47', 'balcony' => '24.81',
            'blurb' => 'The largest detached design — generous living floors, a wide master balcony and an extended courtyard with pool and entertaining deck.',
            'features' => ['Extended courtyard & garden', 'Private swimming pool', 'Outdoor barbecue & lounge deck', 'Auxiliary / staff room', 'Double "parent-and-child" parking'],
        ],
        [
            'key' => 'e', 'tag' => 'Type E', 'kind' => 'Townhouse', 'img' => $base.'/townhouse-e.jpg',
            'gfa' => '118.60', 'ground' => '59.67', 'upper' => '58.93', 'balcony' => '5.22',
            'blurb' => 'An efficient two-storey townhouse on a landscaped terrace, with a private courtyard and twin parking — the most accessible way into SUNRIDGE.',
            'features' => ['Private courtyard', 'Outdoor barbecue & lounge area', 'Twin parking bay', 'Street-front landscaped terrace'],
        ],
        [
            'key' => 'f', 'tag' => 'Type F', 'kind' => 'Townhouse', 'img' => $base.'/townhouse-f.jpg',
            'gfa' => '147.69', 'ground' => '69.70', 'upper' => '77.99', 'balcony' => '10.60',
            'blurb' => 'The larger townhouse design, with more upper-floor space and a deeper balcony — family living with a courtyard, barbecue deck and twin parking.',
            'features' => ['Private courtyard', 'Outdoor barbecue & lounge area', 'Deeper first-floor balcony', 'Twin parking bay'],
        ],
    ];

    $gallery = [
        ['img' => $base.'/int-living.jpg',   'cap' => 'Living room — Type A'],
        ['img' => $base.'/int-living-c.jpg', 'cap' => 'Living room — Type C'],
        ['img' => $base.'/int-dining.jpg',   'cap' => 'Dining & garden view'],
        ['img' => $base.'/int-kitchen.jpg',  'cap' => 'Fitted kitchen'],
        ['img' => $base.'/int-bedroom.jpg',  'cap' => 'Master bedroom'],
        ['img' => $base.'/int-study.jpg',    'cap' => 'Study / home office'],
        ['img' => $base.'/int-stair.jpg',    'cap' => 'Stair hall'],
        ['img' => $base.'/int-bathroom.jpg', 'cap' => 'Bathroom'],
        ['img' => $base.'/villa-pool.jpg',   'cap' => 'Private pool & terrace'],
        ['img' => $base.'/clubhouse.jpg',    'cap' => 'Central clubhouse & pool'],
        ['img' => $base.'/gate.jpg',         'cap' => 'Grand entrance & gatehouse'],
        ['img' => $base.'/masterplan.jpg',   'cap' => 'Coloured master plan'],
    ];

    $amenities = [
        ['t' => 'Central clubhouse', 'd' => 'A members\' clubhouse at the heart of the community for leisure, events and fitness.'],
        ['t' => 'Swimming & gym', 'd' => 'Communal pool, gym and wellness spaces alongside private villa pools.'],
        ['t' => 'Sports fields', 'd' => 'Open sports pitches and courts set within the central green.'],
        ['t' => 'Children\'s playground', 'd' => 'Safe, landscaped play areas inside the gated enclave.'],
        ['t' => 'Supermarket & retail', 'd' => 'An on-site supermarket, convenience stores and street-front boutiques.'],
        ['t' => '24-hour security', 'd' => 'Gated access with a staffed gatehouse and estate-wide security.'],
        ['t' => 'Reliable power', 'd' => 'Backup power infrastructure so the lights stay on year-round.'],
        ['t' => '30 m landscape avenue', 'd' => 'A tree-lined 30-metre boulevard forms the ceremonial approach home.'],
    ];

    $climate = [
        ['t' => 'Moisture-engineered', 'd' => 'Whole-house moisture-proofing built for the rainy season — base treatment, breathable construction and anti-mould materials.'],
        ['t' => 'Weather-resistant finishes', 'd' => 'Mould-proof, weather-resistant coatings instead of wallpaper, with enclosed dust-proof lighting for the dry-season harmattan.'],
        ['t' => 'Sun-fast colours', 'd' => 'A warm off-white and light-coffee palette chosen to resist strong UV and stay fade-free.'],
        ['t' => 'Local, durable materials', 'd' => 'Anti-slip tiles, West African hardwood and rattan — easy to clean, corrosion-resistant and repairable locally.'],
    ];

    $faqs = [
        ['q' => 'How much do the homes cost?', 'a' => 'Prices are released as each phase opens. Register your interest and a Credence advisor will send you the current price list and payment options for the design you prefer.'],
        ['q' => 'Can I buy from abroad?', 'a' => 'Yes. Credence runs diaspora desks in the UK and USA and handles viewings, documentation and transfer end to end, in your time zone.'],
        ['q' => 'How many homes are available?', 'a' => '500 homes are being released to the public across the master plan, in phases. Registering early gives you first pick of designs and plots.'],
        ['q' => 'When will homes be ready?', 'a' => 'Phase 1 is now released. Register and we will share the current construction and handover timeline for your chosen design.'],
        ['q' => 'How do I reserve a home?', 'a' => 'Register below, or call or WhatsApp us. Credence guides you through selection, due-diligence, documentation and payment — all with one point of contact.'],
    ];
@endphp

@push('head')
<style>
/* ===== SUNRIDGE landing page (scoped .sr-*) ===== */
.sr{--sr-dark:#141A17;--sr-header:108px;color:var(--ink)}
.sr .reveal{opacity:0;transform:translateY(18px);transition:opacity .7s ease,transform .7s cubic-bezier(.2,.8,.2,1)}
.sr .reveal.in{opacity:1;transform:none}
/* Never hide content if JS or IntersectionObserver is unavailable, or motion is reduced */
@media (prefers-reduced-motion: reduce){.sr .reveal{opacity:1;transform:none;transition:none}}
.sr .sr-eyebrow{font-family:var(--mono);font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:var(--gold-2);display:flex;align-items:center;gap:10px}
.sr .sr-eyebrow .dot{width:5px;height:5px;border-radius:50%;background:var(--gold)}
.sr h2.sr-h{font-family:var(--serif);font-weight:400;font-size:clamp(30px,4vw,52px);line-height:1.04;letter-spacing:-0.01em;margin:12px 0 16px}
.sr h2.sr-h em{font-style:italic;color:var(--gold)}
.sr .sr-lead{font-size:18px;color:var(--ink-2);line-height:1.7;max-width:64ch}

/* Hero */
.sr-hero{position:relative;min-height:92vh;display:flex;align-items:flex-end;color:#fff;background:#0d120f center/cover no-repeat;}
.sr-hero::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(13,18,15,.35) 0%,rgba(13,18,15,.15) 40%,rgba(13,18,15,.85) 100%)}
.sr-hero .sr-hero-in{position:relative;z-index:2;width:100%;padding:0 var(--gutter) clamp(44px,7vw,96px);max-width:var(--container);margin:0 auto}
.sr-hero .sr-eyebrow{color:#E7D6B4}
.sr-hero .sr-eyebrow .dot{background:var(--gold)}
.sr-hero h1{font-family:var(--serif);font-weight:400;font-size:clamp(56px,11vw,150px);line-height:.92;letter-spacing:-0.02em;margin:16px 0 6px}
.sr-hero .sr-sub{font-size:clamp(17px,2.2vw,23px);color:rgba(255,255,255,.9);max-width:30ch;line-height:1.4;font-family:var(--serif);font-style:italic}
.sr-hero .sr-place{margin:18px 0 26px;font-size:15px;color:rgba(255,255,255,.82);display:flex;flex-wrap:wrap;gap:8px 20px}
.sr-hero .sr-cta{display:flex;flex-wrap:wrap;gap:12px}
.sr-hero .btn-ghost{border-color:rgba(255,255,255,.5);color:#fff}
.sr-hero .btn-ghost:hover{background:#fff;color:var(--ink);border-color:#fff}
.sr-scroll{position:absolute;right:var(--gutter);bottom:34px;z-index:2;font-family:var(--mono);font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.7);writing-mode:vertical-rl}

/* Sticky section nav */
.sr-subnav{position:sticky;top:var(--sr-header);z-index:35;background:rgba(246,242,236,.92);backdrop-filter:blur(10px);border-top:1px solid var(--line);border-bottom:1px solid var(--line);transition:box-shadow .3s ease;}
.sr-subnav.stuck{box-shadow:var(--shadow-1)}
.sr-subnav .in{max-width:var(--container);margin:0 auto;padding:0 var(--gutter);display:flex;align-items:center;gap:6px;height:58px;overflow-x:auto;scrollbar-width:none}
.sr-subnav .in::-webkit-scrollbar{display:none}
.sr-subnav .sr-brand{font-family:var(--serif);font-size:19px;margin-right:auto;white-space:nowrap}
.sr-subnav a{font-size:13px;color:var(--ink-3);padding:8px 13px;border-radius:999px;white-space:nowrap;transition:all .2s}
.sr-subnav a:hover{color:var(--ink);background:var(--bone-2)}
.sr-subnav a.active{color:#fff;background:var(--emerald)}
.sr-subnav a.sr-reg{color:#fff;background:var(--gold);}
.sr-subnav a.sr-reg:hover{background:var(--gold-2)}

.sr-sec{padding:clamp(56px,8vw,110px) 0;scroll-margin-top:calc(var(--sr-header) + 60px)}
.sr-sec .container{max-width:var(--container);margin:0 auto;padding:0 var(--gutter)}
.sr-dark{background:var(--sr-dark);color:#Eee}
.sr-dark .sr-lead{color:rgba(255,255,255,.72)}
.sr-dark h2.sr-h{color:#fff}
.sr-bone{background:var(--bone)}
.sr-paper{background:var(--paper)}

/* Intro + stats */
.sr-intro{display:grid;grid-template-columns:1.1fr .9fr;gap:48px;align-items:end}
.sr-stats{display:grid;grid-template-columns:repeat(6,1fr);gap:20px;margin-top:56px;border-top:1px solid rgba(255,255,255,.14);padding-top:40px}
.sr-stat .n{font-family:var(--serif);font-size:clamp(34px,4vw,52px);line-height:1;color:#fff}
.sr-stat .n .suf{font-size:.5em;color:var(--gold);margin-left:2px}
.sr-stat .l{font-size:12.5px;color:rgba(255,255,255,.6);margin-top:8px;line-height:1.4}

/* Master plan */
.sr-split{display:grid;grid-template-columns:1fr 1fr;gap:clamp(28px,4vw,56px);align-items:center}
.sr-figure{border-radius:var(--r-l);overflow:hidden;box-shadow:var(--shadow-2);background:var(--bone-2)}
.sr-figure img{display:block;width:100%;height:100%;object-fit:cover}
.sr-chips{list-style:none;margin:22px 0 0;padding:0;display:grid;gap:10px}
.sr-chips li{display:flex;gap:12px;align-items:flex-start;font-size:15.5px;color:var(--ink-2);line-height:1.5}
.sr-chips li::before{content:"";flex:none;width:9px;height:9px;margin-top:7px;border-radius:50%;background:var(--gold)}

/* Units */
.sr-tabs{display:flex;flex-wrap:wrap;gap:10px;margin:28px 0 30px}
.sr-tab{display:flex;flex-direction:column;gap:2px;padding:12px 20px;border-radius:14px;border:1px solid var(--line);background:var(--paper);cursor:pointer;transition:all .2s;min-width:132px;text-align:left}
.sr-tab b{font-family:var(--mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-2)}
.sr-tab span{font-size:15px;color:var(--ink)}
.sr-tab:hover{border-color:var(--line-2)}
.sr-tab.active{background:var(--emerald);border-color:var(--emerald)}
.sr-tab.active b{color:var(--gold)}
.sr-tab.active span{color:#fff}
.sr-unit{display:grid;grid-template-columns:1.25fr 1fr;gap:clamp(24px,3.5vw,48px);align-items:center}
.sr-unit[hidden]{display:none}
.sr-unit .sr-figure{aspect-ratio:16/10}
.sr-unit h3{font-family:var(--serif);font-weight:400;font-size:clamp(26px,3vw,38px);margin:0 0 6px}
.sr-unit .sr-kind{font-family:var(--mono);font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-2)}
.sr-unit p{color:var(--ink-2);line-height:1.7;margin:14px 0 20px}
.sr-spec{display:grid;grid-template-columns:repeat(2,1fr);gap:14px 18px;margin:0 0 22px;padding:20px 0;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.sr-spec div b{display:block;font-family:var(--mono);font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-3);margin-bottom:3px}
.sr-spec div span{font-size:17px;color:var(--ink)}
.sr-spec .u{font-size:12px;color:var(--ink-3)}
.sr-feat{list-style:none;margin:0 0 24px;padding:0;display:grid;gap:9px}
.sr-feat li{display:flex;gap:10px;font-size:14.5px;color:var(--ink-2)}
.sr-feat li svg{flex:none;margin-top:3px;color:var(--emerald-3)}
.sr-note{font-size:13px;color:var(--ink-3);margin-top:14px}

/* Gallery */
.sr-grid{display:grid;grid-template-columns:repeat(4,1fr);grid-auto-rows:220px;gap:12px;margin-top:40px}
.sr-grid figure{position:relative;margin:0;border-radius:var(--r-m);overflow:hidden;background:var(--bone-2)}
.sr-grid img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease}
.sr-grid figure:hover img{transform:scale(1.05)}
.sr-grid figure::after{content:attr(data-cap);position:absolute;left:0;right:0;bottom:0;padding:26px 14px 12px;font-size:12.5px;color:#fff;background:linear-gradient(transparent,rgba(13,18,15,.8));opacity:0;transition:opacity .3s}
.sr-grid figure:hover::after{opacity:1}
.sr-grid .big{grid-column:span 2;grid-row:span 2}
.sr-grid .tall{grid-row:span 2}

/* Amenity / climate cards */
.sr-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:40px}
.sr-card{background:var(--paper);border:1px solid var(--line);border-radius:var(--r-m);padding:24px 22px}
.sr-dark .sr-card{background:rgba(255,255,255,.04);border-color:rgba(255,255,255,.1)}
.sr-card .ic{width:40px;height:40px;border-radius:10px;display:grid;place-items:center;background:var(--emerald);color:#fff;margin-bottom:16px}
.sr-card h4{font-family:var(--serif);font-weight:400;font-size:21px;margin:0 0 8px}
.sr-dark .sr-card h4{color:#fff}
.sr-card p{font-size:14px;color:var(--ink-3);line-height:1.6;margin:0}
.sr-dark .sr-card p{color:rgba(255,255,255,.62)}

/* Location */
.sr-map{aspect-ratio:16/9;border:0;width:100%;border-radius:var(--r-l);box-shadow:var(--shadow-2)}
.sr-loc-list{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:30px}
.sr-loc-list .i b{font-family:var(--serif);font-size:30px;display:block;color:var(--emerald)}
.sr-loc-list .i span{font-size:14px;color:var(--ink-3)}

/* Why Credence */
.sr-why{display:grid;grid-template-columns:1fr 1fr;gap:clamp(28px,4vw,60px);align-items:center}
.sr-why .sr-points{display:grid;gap:18px;margin-top:8px}
.sr-why .sr-points .p{display:flex;gap:14px}
.sr-why .sr-points .p svg{flex:none;margin-top:2px;color:var(--gold)}
.sr-why .sr-points b{display:block;font-size:16px;margin-bottom:3px}
.sr-why .sr-points p{font-size:14.5px;color:rgba(255,255,255,.66);line-height:1.6;margin:0}

/* FAQ */
.sr-faq{max-width:860px;margin:36px auto 0}
.sr-faq-item{border-bottom:1px solid var(--line)}
.sr-faq-q{width:100%;text-align:left;background:none;border:0;padding:22px 40px 22px 0;position:relative;cursor:pointer;font-family:var(--serif);font-size:clamp(19px,2.2vw,24px);color:var(--ink)}
.sr-faq-q::after{content:"+";position:absolute;right:6px;top:50%;transform:translateY(-50%);font-family:var(--sans);font-size:26px;color:var(--gold-2);transition:transform .3s}
.sr-faq-item.open .sr-faq-q::after{content:"–"}
.sr-faq-a{max-height:0;overflow:hidden;transition:max-height .35s ease}
.sr-faq-item.open .sr-faq-a{max-height:320px}
.sr-faq-a p{color:var(--ink-2);line-height:1.7;margin:0 0 22px;max-width:70ch}

/* Register */
.sr-reg-wrap{display:grid;grid-template-columns:.85fr 1.15fr;gap:clamp(32px,5vw,72px)}
.sr-reg-wrap .sr-reg-aside h2.sr-h{color:#fff}
.sr-form{background:var(--paper);border-radius:var(--r-l);padding:clamp(26px,3vw,40px);box-shadow:var(--shadow-3)}
.sr-form .row-2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.sr-form label{display:block;font-size:12px;font-family:var(--mono);letter-spacing:.08em;text-transform:uppercase;color:var(--ink-3);margin:0 0 7px}
.sr-form input,.sr-form select,.sr-form textarea{width:100%;padding:13px 15px;border:1px solid var(--line-2);border-radius:10px;background:#fff;font-family:inherit;font-size:15px;color:var(--ink);margin-bottom:16px}
.sr-form textarea{min-height:110px;resize:vertical}
.sr-form input:focus,.sr-form select:focus,.sr-form textarea:focus{outline:2px solid var(--gold);outline-offset:1px;border-color:var(--gold)}
.sr-form .submit{width:100%;justify-content:center;border:0;cursor:pointer;margin-top:4px}
.sr-reg-aside .sr-contact{margin-top:28px;display:grid;gap:12px}
.sr-reg-aside .sr-contact a{display:flex;align-items:center;gap:12px;color:#fff;font-size:16px}
.sr-reg-aside .sr-contact a span{color:rgba(255,255,255,.55);font-size:13px}

.sr-credit{font-size:12.5px;color:var(--ink-4);text-align:center;padding:26px var(--gutter);background:var(--bone);border-top:1px solid var(--line)}

/* Lightbox */
.srlb{position:fixed;inset:0;z-index:300;background:rgba(10,12,10,.92);display:none;place-items:center;padding:20px}
.srlb.open{display:grid}
.srlb figure{margin:0;max-width:94vw;max-height:90vh;display:flex;flex-direction:column;align-items:center;gap:12px}
.srlb .srlb-img{max-width:94vw;max-height:80vh;object-fit:contain;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5)}
.srlb .srlb-bar{display:flex;align-items:center;gap:20px;color:rgba(255,255,255,.8);font-size:13px}
.srlb .srlb-count{font-family:var(--mono);letter-spacing:.1em}
.srlb button{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.25);color:#fff;width:44px;height:44px;border-radius:50%;cursor:pointer;font-size:20px;display:grid;place-items:center;transition:background .2s}
.srlb button:hover{background:rgba(255,255,255,.25)}
.srlb .srlb-close{position:absolute;top:20px;right:20px}

@media(max-width:1000px){
  .sr-intro,.sr-split,.sr-unit,.sr-why,.sr-reg-wrap{grid-template-columns:1fr}
  .sr-stats{grid-template-columns:repeat(3,1fr);gap:24px 16px}
  .sr-cards{grid-template-columns:repeat(2,1fr)}
  .sr-grid{grid-template-columns:repeat(2,1fr);grid-auto-rows:180px}
  .sr-unit .sr-figure{order:-1}
}
@media(max-width:560px){
  .sr-stats{grid-template-columns:repeat(2,1fr)}
  .sr-loc-list{grid-template-columns:1fr}
  .sr-form .row-2{grid-template-columns:1fr}
  .sr-grid .big{grid-column:span 2;grid-row:span 1}
}
</style>
@endpush

@section('content')
<noscript><style>.sr .reveal{opacity:1 !important;transform:none !important}</style></noscript>
<div class="sr" data-sunridge>

    {{-- ============ HERO ============ --}}
    <section class="sr-hero" data-hero style="background-image:url('{{ $base }}/hero-aerial.jpg')">
        <div class="sr-hero-in">
            <div class="sr-eyebrow"><span class="dot"></span> In partnership with Credence Real Estate</div>
            <h1>SUNRIDGE</h1>
            <p class="sr-sub">University City of Ghana</p>
            <div class="sr-place">
                <span>80-acre gated villa city · University of Ghana enclave, Accra</span>
                <span>500 homes now released to the public</span>
            </div>
            <div class="sr-cta">
                <a class="btn-gold" href="#register">Register your interest <x-icon name="arrow"/></a>
                <a class="btn-ghost" href="#homes">Explore the homes</a>
            </div>
        </div>
        <div class="sr-scroll">Scroll</div>
    </section>

    {{-- ============ STICKY SECTION NAV ============ --}}
    <nav class="sr-subnav" data-subnav aria-label="On this page">
        <div class="in">
            <span class="sr-brand">SUNRIDGE</span>
            <a href="#overview">Overview</a>
            <a href="#masterplan">Master plan</a>
            <a href="#homes">The homes</a>
            <a href="#interiors">Interiors</a>
            <a href="#amenities">Amenities</a>
            <a href="#location">Location</a>
            <a class="sr-reg" href="#register">Register</a>
        </div>
    </nav>

    {{-- ============ OVERVIEW + STATS ============ --}}
    <section class="sr-sec sr-dark" id="overview">
        <div class="container">
            <div class="sr-intro">
                <div class="reveal">
                    <div class="sr-eyebrow"><span class="dot"></span> The project</div>
                    <h2 class="sr-h">A whole <em>villa city</em>, beside the University of Ghana.</h2>
                </div>
                <p class="sr-lead reveal">SUNRIDGE is an 80-acre master-planned community in the University of Ghana enclave — detached villas and townhouses arranged around a central clubhouse, landscaped avenues, sports grounds and its own retail. Credence represents SUNRIDGE in Ghana, and <strong style="color:#fff">500 homes are being released to the public</strong>. Reserve early, directly through us.</p>
            </div>
            <div class="sr-stats">
                @foreach ($stats as $s)
                    <div class="sr-stat reveal">
                        <div class="n"><span data-count="{{ $s['n'] }}">0</span><span class="suf">{{ $s['suf'] }}</span></div>
                        <div class="l">{{ $s['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ MASTER PLAN ============ --}}
    <section class="sr-sec sr-bone" id="masterplan">
        <div class="container">
            <div class="sr-split">
                <div class="sr-figure reveal"><img src="{{ $base }}/masterplan.jpg" alt="SUNRIDGE coloured master plan" loading="lazy"/></div>
                <div class="reveal">
                    <div class="sr-eyebrow"><span class="dot"></span> The master plan</div>
                    <h2 class="sr-h">Designed as one <em>connected</em> community.</h2>
                    <p class="sr-lead">A 30-metre tree-lined avenue draws you in to a central green of clubhouse, pool and sports fields, with villa clusters arranged along a calm radial street network — a clear "gateway → landscape → private home" rhythm across the whole estate.</p>
                    <ul class="sr-chips">
                        <li>556 residences in the plan — detached villas, semi-detached, townhouses &amp; stacked villas</li>
                        <li>Central clubhouse, supermarket, street-front retail &amp; a gated entrance</li>
                        <li>30% green &amp; open space, with ecological planting throughout</li>
                        <li>A graded road hierarchy — 30 m main avenue down to quiet 9 m lanes</li>
                        <li>Phase 1 now under way: 227 homes with 468 parking bays</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ THE HOMES (interactive) ============ --}}
    <section class="sr-sec sr-paper" id="homes">
        <div class="container">
            <div class="reveal" style="max-width:720px">
                <div class="sr-eyebrow"><span class="dot"></span> The homes · 4 designs</div>
                <h2 class="sr-h">Choose your <em>home</em>.</h2>
                <p class="sr-lead">Four two-storey designs, from efficient townhouses to generous detached villas with private pools. Select a design to see its layout and features — then register for floor plans and pricing.</p>
            </div>

            <div class="sr-tabs" role="tablist" aria-label="Home designs">
                @foreach ($units as $i => $u)
                    <button class="sr-tab{{ $i === 0 ? ' active' : '' }}" data-unit-tab="{{ $u['key'] }}" role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}">
                        <b>{{ $u['tag'] }}</b><span>{{ $u['kind'] }}</span>
                    </button>
                @endforeach
            </div>

            @foreach ($units as $i => $u)
                <div class="sr-unit" data-unit-panel="{{ $u['key'] }}" @if($i !== 0) hidden @endif>
                    <div class="sr-figure"><img src="{{ $u['img'] }}" alt="SUNRIDGE {{ $u['tag'] }} {{ $u['kind'] }} — exterior" loading="lazy"/></div>
                    <div>
                        <span class="sr-kind">{{ $u['tag'] }} · {{ $u['kind'] }}</span>
                        <h3>{{ $u['gfa'] }} m² home</h3>
                        <p>{{ $u['blurb'] }}</p>
                        <div class="sr-spec">
                            <div><b>Total floor area</b><span>{{ $u['gfa'] }} <span class="u">m²</span></span></div>
                            <div><b>Layout</b><span>2 storeys</span></div>
                            <div><b>Ground floor</b><span>{{ $u['ground'] }} <span class="u">m²</span></span></div>
                            <div><b>First floor</b><span>{{ $u['upper'] }} <span class="u">m²</span></span></div>
                            <div><b>Balcony</b><span>{{ $u['balcony'] }} <span class="u">m²</span></span></div>
                            <div><b>Parking</b><span>Private, on-plot</span></div>
                        </div>
                        <ul class="sr-feat">
                            @foreach ($u['features'] as $f)
                                <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12l5 5 9-12"/></svg>{{ $f }}</li>
                            @endforeach
                        </ul>
                        <a class="btn-solid" href="#register">Register for floor plans &amp; price <x-icon name="arrow"/></a>
                        <p class="sr-note">Finished to SUNRIDGE's signature warm, tropical-ready interior specification.</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ INTERIORS GALLERY ============ --}}
    <section class="sr-sec sr-dark" id="interiors">
        <div class="container">
            <div class="reveal" style="max-width:720px">
                <div class="sr-eyebrow"><span class="dot"></span> Interiors</div>
                <h2 class="sr-h">Warm, modern, <em>built for the climate</em>.</h2>
                <p class="sr-lead">A relaxed modern palette of warm off-whites, soft coffee tones and natural timber — with Chinese and Western kitchens, walk-in wardrobes and zoned, diffused lighting. Tap any image to view.</p>
            </div>
            <div class="sr-grid reveal">
                @foreach ($gallery as $i => $g)
                    <figure data-cap="{{ $g['cap'] }}" class="{{ $i === 0 ? 'big' : ($i === 4 ? 'tall' : '') }}">
                        <img src="{{ $g['img'] }}" alt="{{ $g['cap'] }}" loading="lazy"
                             data-lightbox data-full="{{ $g['img'] }}" data-caption="{{ $g['cap'] }}"/>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ CLIMATE ENGINEERING ============ --}}
    <section class="sr-sec sr-bone">
        <div class="container">
            <div class="sr-split">
                <div class="reveal">
                    <div class="sr-eyebrow"><span class="dot"></span> Engineered for Ghana</div>
                    <h2 class="sr-h">Built for the <em>heat, rain &amp; sun</em>.</h2>
                    <p class="sr-lead">Every specification answers the tropical climate — so your home stays healthy, cool and easy to maintain through the rainy season and the harmattan alike.</p>
                </div>
                <div class="sr-figure reveal" style="aspect-ratio:4/3"><img src="{{ $base }}/villa-pool.jpg" alt="SUNRIDGE villa pool terrace" loading="lazy"/></div>
            </div>
            <div class="sr-cards">
                @foreach ($climate as $c)
                    <div class="sr-card reveal">
                        <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l7 7a7 7 0 1 1-14 0z"/></svg></div>
                        <h4>{{ $c['t'] }}</h4>
                        <p>{{ $c['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ AMENITIES ============ --}}
    <section class="sr-sec sr-paper" id="amenities">
        <div class="container">
            <div class="sr-split">
                <div class="sr-figure reveal"><img src="{{ $base }}/clubhouse.jpg" alt="SUNRIDGE central clubhouse and pool" loading="lazy"/></div>
                <div class="reveal">
                    <div class="sr-eyebrow"><span class="dot"></span> Life at SUNRIDGE</div>
                    <h2 class="sr-h">A <em>5-minute</em> living circle.</h2>
                    <p class="sr-lead">Everything day-to-day sits inside the gates — a clubhouse at the centre, shops at the entrance and green space between, all within a short, safe walk.</p>
                </div>
            </div>
            <div class="sr-cards">
                @foreach ($amenities as $a)
                    <div class="sr-card reveal">
                        <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 21V9l9-6 9 6v12"/><path d="M9 21v-7h6v7"/></svg></div>
                        <h4>{{ $a['t'] }}</h4>
                        <p>{{ $a['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ LOCATION ============ --}}
    <section class="sr-sec sr-bone" id="location">
        <div class="container">
            <div class="reveal" style="max-width:720px;margin-bottom:34px">
                <div class="sr-eyebrow"><span class="dot"></span> The address</div>
                <h2 class="sr-h">In the <em>University City</em>, minutes from everything.</h2>
                <p class="sr-lead">Set in the University of Ghana enclave in Accra — on the municipal road, beside a sports park — with the airport and the port both a short drive away.</p>
            </div>
            <div class="reveal">
                <iframe class="sr-map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="SUNRIDGE location — University of Ghana, Accra"
                        src="https://www.google.com/maps?q=University%20of%20Ghana%2C%20Legon%2C%20Accra&output=embed"></iframe>
                <div class="sr-loc-list">
                    <div class="i"><b>11 km</b><span>Straight-line to Kotoka International Airport</span></div>
                    <div class="i"><b>11 km</b><span>Straight-line to Tema Port</span></div>
                    <div class="i"><b>University</b><span>In the University of Ghana enclave, Accra</span></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ WHY CREDENCE ============ --}}
    <section class="sr-sec sr-dark">
        <div class="container">
            <div class="sr-why">
                <div class="reveal">
                    <div class="sr-eyebrow"><span class="dot"></span> Buy through Credence</div>
                    <h2 class="sr-h">One trusted team, from <em>enquiry to keys</em>.</h2>
                    <p class="sr-lead">SUNRIDGE is represented in Ghana by Credence Real Estate &amp; Asset Management. Deal with us directly — not a middle layer — for honest guidance, verified documentation and a single point of contact throughout.</p>
                </div>
                <div class="sr-points reveal">
                    <div class="p"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 6 9 17l-5-5"/></svg><div><b>Your direct line to SUNRIDGE</b><p>Reserve, choose your plot and design, and get pricing straight from Credence.</p></div></div>
                    <div class="p"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg><div><b>Verified documentation</b><p>Title, survey and paperwork checked and explained before you commit.</p></div></div>
                    <div class="p"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M2 12h20M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></svg><div><b>Built for the diaspora</b><p>UK and US desks handle viewings, video tours and transfer in your time zone.</p></div></div>
                    <div class="p"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 10h18M7 3v4M17 3v4M5 7h14v12H5z"/></svg><div><b>Flexible payment</b><p>Ask us about staged payment options as each phase is released.</p></div></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FAQ ============ --}}
    <section class="sr-sec sr-paper">
        <div class="container">
            <div class="reveal" style="text-align:center;max-width:640px;margin:0 auto">
                <div class="sr-eyebrow" style="justify-content:center"><span class="dot"></span> Good to know</div>
                <h2 class="sr-h">Your <em>questions</em>, answered.</h2>
            </div>
            <div class="sr-faq reveal">
                @foreach ($faqs as $i => $f)
                    <div class="sr-faq-item{{ $i === 0 ? ' open' : '' }}">
                        <button class="sr-faq-q" data-faq-q aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">{{ $f['q'] }}</button>
                        <div class="sr-faq-a"><p>{{ $f['a'] }}</p></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ REGISTER ============ --}}
    <section class="sr-sec sr-dark" id="register">
        <div class="container">
            <div class="sr-reg-wrap">
                <div class="sr-reg-aside reveal">
                    <div class="sr-eyebrow"><span class="dot"></span> Register interest</div>
                    <h2 class="sr-h">Get the price list &amp; <em>first pick</em>.</h2>
                    <p class="sr-lead">Tell us which home interests you and we'll send floor plans, current pricing and payment options — and hold your place as Phase 1 sells. No obligation.</p>
                    <div class="sr-contact">
                        <a href="tel:{{ config('cream.phone.tel') }}"><x-icon name="phone"/> {{ config('cream.phone.display') }} <span>Call us</span></a>
                        <a href="https://wa.me/{{ config('cream.whatsapp.number') }}" target="_blank" rel="noopener"><x-icon name="wa"/> {{ config('cream.whatsapp.display') }} <span>WhatsApp</span></a>
                    </div>
                </div>
                <div class="reveal">
                    @include('public.partials.form-status')
                    <form class="sr-form" action="{{ route('enquiries.store') }}" method="POST" data-enquiry>
                        @csrf
                        <input type="hidden" name="type" value="new_project"/>
                        <input type="hidden" name="subject" value="SUNRIDGE — register interest"/>
                        <input type="hidden" name="project" value="SUNRIDGE — University City of Ghana"/>
                        <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
                        <div class="row-2">
                            <div><label>Full name</label><input name="name" required/></div>
                            <div><label>Country</label><input name="country" placeholder="e.g. Ghana, UK, USA"/></div>
                        </div>
                        <div class="row-2">
                            <div><label>Email</label><input type="email" name="email"/></div>
                            <div><label>Phone / WhatsApp</label><input type="tel" name="phone"/></div>
                        </div>
                        <div>
                            <label>Home of interest</label>
                            <select name="unit_preference">
                                <option>Type A — Detached villa (183.57 m²)</option>
                                <option>Type C — Detached villa (230.82 m²)</option>
                                <option>Type E — Townhouse (118.60 m²)</option>
                                <option>Type F — Townhouse (147.69 m²)</option>
                                <option>Not sure yet — advise me</option>
                            </select>
                        </div>
                        <div><label>Message <span style="text-transform:none;letter-spacing:0;color:var(--ink-4)">(optional)</span></label><textarea name="message" placeholder="Budget, timeline, number of bedrooms, or any questions…"></textarea></div>
                        <label style="display:flex;gap:10px;align-items:center;text-transform:none;letter-spacing:0;font-family:var(--sans);font-size:14px;color:var(--ink-2);margin-bottom:16px">
                            <input type="checkbox" name="newsletter" value="1" style="width:auto;margin:0"/> Keep me updated on SUNRIDGE &amp; Credence projects
                        </label>
                        @include('partials.turnstile')
                        <button type="submit" class="submit btn-gold">Register my interest <x-icon name="arrow"/></button>
                        <div class="form-msg" style="display:none"></div>
                        <p class="sr-note" style="text-align:center">We reply within one business day. Give us an email or a phone number so we can reach you.</p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <p class="sr-credit">SUNRIDGE — University City of Ghana. Master plan &amp; architecture by an international design institute. Marketed in Ghana by Credence Real Estate &amp; Asset Management. Renders are indicative; areas are approximate. {{-- TODO(sam): confirm handover dates & pricing before publishing externally. --}}</p>

    {{-- Lightbox --}}
    <div class="srlb" id="srLightbox" aria-hidden="true" role="dialog" aria-label="Image viewer">
        <button class="srlb-close" data-lb="close" aria-label="Close">&times;</button>
        <figure>
            <img class="srlb-img" src="" alt=""/>
            <div class="srlb-bar">
                <button data-lb="prev" aria-label="Previous">&#8249;</button>
                <span class="srlb-count"></span>
                <button data-lb="next" aria-label="Next">&#8250;</button>
            </div>
            <div class="srlb-cap" style="color:rgba(255,255,255,.8);font-size:14px"></div>
        </figure>
    </div>
</div>
@endsection

@push('scripts')
<script src="/assets/sunridge.js?v={{ @filemtime(public_path('assets/sunridge.js')) ?: '1' }}"></script>
@endpush
