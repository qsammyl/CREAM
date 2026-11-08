@php($active = $active ?? '')
@php($s = config('cream.social'))
<a class="skip-link" href="#main" style="position:absolute;left:-9999px">Skip to content</a>
<header class="site" id="siteHeader" data-overlay>
    <div class="util-bar">
        <div class="util-inner">
            <div class="util-left">
                <a href="/blog">Blog</a>
                <a href="/insights">Insights</a>
                <a href="/search-document">Search Document</a>
            </div>
            <div class="util-right">
                <a class="util-phone" href="tel:{{ config('cream.phone.tel') }}">{{ config('cream.phone.display') }}</a>
                <a href="https://wa.me/{{ config('cream.whatsapp.number') }}" target="_blank" rel="noopener">WhatsApp</a>
                <span class="util-social">
                    <a href="{{ $s['instagram']['url'] }}" target="_blank" rel="noopener" aria-label="Instagram"><x-icon name="ig"/></a>
                    <a href="{{ $s['tiktok']['url'] }}" target="_blank" rel="noopener" aria-label="TikTok"><x-icon name="tt"/></a>
                    <a href="{{ $s['youtube']['url'] }}" target="_blank" rel="noopener" aria-label="YouTube"><x-icon name="yt"/></a>
                    <a href="{{ $s['facebook']['url'] }}" target="_blank" rel="noopener" aria-label="Facebook"><x-icon name="fb"/></a>
                </span>
            </div>
        </div>
    </div>
    <div class="main-bar">
        <div class="row">
            <a class="brand" href="/"><span class="mark">C</span><span class="word"><span class="a">Credence</span><span class="b">Real Estate</span></span></a>
            <nav class="mainnav" aria-label="Primary">
                <div class="has-mega">
                    <button class="navitem{{ $active === 'assets' ? ' active' : '' }}" aria-expanded="false" aria-controls="mega-assets" aria-haspopup="true">Assets <svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg></button>
                    <div class="mega" id="mega-assets" role="menu">
                        <div class="col"><h4>Properties</h4>
                            <a role="menuitem" href="/properties/buy">Buy</a>
                            <a role="menuitem" href="/properties/rent">Rent</a>
                            <a role="menuitem" href="/sell">Sell with us</a>
                            <a role="menuitem" href="/properties/land">Land</a>
                        </div>
                        <div class="col"><h4>Exclusives</h4>
                            <a role="menuitem" href="/exclusives">Exclusive listings</a>
                            <a role="menuitem" href="/new-projects">New projects &amp; off-plan</a>
                            <a role="menuitem" href="/exclusives#off-market">Off-market brief</a>
                        </div>
                        <div class="col"><h4>Short stays</h4>
                            <a role="menuitem" href="/short-stays">Short-stay homes</a>
                            <a role="menuitem" href="/short-stays#request">Request dates</a>
                            <a role="menuitem" href="/properties">All properties</a>
                        </div>
                    </div>
                </div>
                <div class="has-drop">
                    <button class="navitem{{ $active === 'services' ? ' active' : '' }}" aria-expanded="false" aria-controls="drop-services" aria-haspopup="true">Services <svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg></button>
                    <div class="drop" id="drop-services" role="menu">
                        <a role="menuitem" href="/services#development">Development</a>
                        <a role="menuitem" href="/services#sales">Sales &amp; Investment</a>
                        <a role="menuitem" href="/services#management">Property Management</a>
                        <a role="menuitem" href="/services#construction">Construction &amp; Project Management</a>
                        <a role="menuitem" href="/services#interiors">Interiors</a>
                        <a role="menuitem" href="/services#land">Land Acquisition &amp; Due Diligence</a>
                    </div>
                </div>
                <a class="navitem{{ $active === 'about' ? ' active' : '' }}" href="/about">About</a>
                <a class="navitem{{ $active === 'contact' ? ' active' : '' }}" href="/contact">Contact</a>
            </nav>
            <div class="tools">
                <a class="cta" href="/contact">Talk to us</a>
                <button class="nav-toggle" aria-label="Menu" aria-expanded="false" aria-controls="mobileDrawer"><span></span><span></span><span></span></button>
            </div>
        </div>
    </div>
</header>
@include('public.partials.drawer')
