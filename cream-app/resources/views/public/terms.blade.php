@extends('public.layout')

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Terms']]"
        h1="Website<br/><em>terms.</em>" sub="The terms on which you use this website."/>
    <section class="sec bone"><div class="container"><div class="prose">
        <p>By using this website you accept these terms. Please read them alongside our <a href="/privacy">privacy notice</a>.</p>
        <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">Listings &amp; prices</h3>
        <p>All listings are subject to availability and to verification. Prices, specifications and availability can change without notice, and photographs and descriptions are for guidance only.</p>
        <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">No binding offer</h3>
        <p>Nothing on this website constitutes a binding offer, a valuation, or professional, legal or financial advice. Any transaction is subject to a separate written agreement and to satisfactory due diligence.</p>
        <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">Your use of the site</h3>
        <p>You agree to use the site lawfully and not to misuse our forms or content. Links to third-party sites are provided for convenience and are outside our control.</p>
        <p class="small">These terms are provided in good faith and are not legal advice. {{-- TODO(sam): have these terms reviewed by counsel, and add governing law / company details. --}}</p>
    </div></div></section>
@endsection
