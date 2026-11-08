@extends('public.layout')

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Insights', 'href' => '/insights'], ['label' => 'Blog']]"
        h1="The Credence<br/><em>blog.</em>"
        sub="Perspectives on Ghana real estate, property management and diaspora investment."/>
    <section class="sec bone2"><div class="container">
        {{-- Articles + comments arrive in Phase 4. Until then, the Signature Home Show fallback. --}}
        <div class="media-grid">
            <a class="media-card" href="{{ config('cream.show.channel') }}" target="_blank" rel="noopener"><div class="mc-top"><x-placeholder label="Signature Home Show"/></div><div class="mc-body"><div class="mc-k">Video</div><div class="mc-t">The Signature Home Show</div><div class="mc-d">Watch our episodes on YouTube.</div></div></a>
        </div>
        <p class="small" style="margin-top:18px">New articles are on the way.</p>
    </div></section>
@endsection
