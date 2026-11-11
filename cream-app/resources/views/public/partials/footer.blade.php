@php($s = config('cream.social'))
<footer class="site">
    <div class="container">
        <div class="top">
            <div class="brandblock">
                <h4>Credence<br/><em>Real Estate</em></h4>
                <p>A subsidiary of Credence Holdings Global — owning, managing, building, selling, and renting property in Accra and across Ghana. By appointment.</p>
                <div class="socials">
                    <a href="{{ $s['instagram']['url'] }}" target="_blank" rel="noopener" aria-label="Instagram"><x-icon name="ig"/></a>
                    <a href="{{ $s['tiktok']['url'] }}" target="_blank" rel="noopener" aria-label="TikTok"><x-icon name="tt"/></a>
                    <a href="{{ $s['youtube']['url'] }}" target="_blank" rel="noopener" aria-label="YouTube"><x-icon name="yt"/></a>
                    <a href="{{ $s['facebook']['url'] }}" target="_blank" rel="noopener" aria-label="Facebook"><x-icon name="fb"/></a>
                    <a href="https://wa.me/{{ config('cream.whatsapp.number') }}" target="_blank" rel="noopener" aria-label="WhatsApp"><x-icon name="wa"/></a>
                </div>
            </div>
            <div><h5>Assets</h5>
                <ul>
                    <li><a href="/properties/buy">Buy</a></li>
                    <li><a href="/properties/rent">Rent</a></li>
                    <li><a href="/properties/land">Land</a></li>
                    <li><a href="/sell">Sell with us</a></li>
                    <li><a href="/exclusives">Exclusives</a></li>
                    <li><a href="/new-projects">New projects</a></li>
                    <li><a href="/short-stays">Short stays</a></li>
                </ul></div>
            <div><h5>Services</h5>
                <ul>
                    <li><a href="/services#development">Development</a></li>
                    <li><a href="/services#sales">Sales &amp; Investment</a></li>
                    <li><a href="/services#management">Property Management</a></li>
                    <li><a href="/services#construction">Construction</a></li>
                    <li><a href="/services#interiors">Interiors</a></li>
                    <li><a href="/services#land">Land &amp; Due Diligence</a></li>
                </ul></div>
            <div class="offices">
                <h5>Insights</h5>
                <ul style="margin-bottom:22px">
                    <li><a href="/insights">Insights hub</a></li>
                    <li><a href="/blog">Blog</a></li>
                    <li><a href="{{ config('cream.show.channel') }}" target="_blank" rel="noopener">Signature Home Show</a></li>
                    <li><a href="/search-document">Search Document</a></li>
                </ul>
                {{-- <h5>Company</h5>
                <div class="of"><b>Accra HQ</b>East Legon, Greater Accra · Ghana<br/><a href="tel:{{ config('cream.phone.tel') }}">{{ config('cream.phone.display') }}</a></div>
                <div class="of"><a href="mailto:{{ config('cream.email') }}">{{ config('cream.email') }}</a><br/><a href="https://wa.me/{{ config('cream.whatsapp.number') }}" target="_blank" rel="noopener">WhatsApp {{ config('cream.whatsapp.display') }}</a></div>
                <ul>
                    <li><a href="/about">About</a></li>
                    <li><a href="/contact">Contact</a></li>
                    <li><a href="/privacy">Privacy</a></li>
                    <li><a href="/terms">Terms</a></li>
                </ul> --}}
            </div>
        </div>
        <div class="bot">
            <div>© {{ date('Y') }} CREDENCE REAL ESTATE &amp; ASSET MGT. · SUBSIDIARY OF CREDENCE HOLDINGS GLOBAL · REGISTERED IN GHANA 2022</div>
            <div><a href="/privacy">PRIVACY</a> · <a href="/terms">TERMS</a></div>
        </div>
    </div>
</footer>
{{-- TODO(sam): add company registration number and Real Estate Agency Council (REAC) licence number to the footer bottom line. --}}
