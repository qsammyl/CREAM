<nav class="mobilebar" aria-label="Quick actions">
    <div class="mb-inner">
        <a href="/properties"><x-icon name="pin"/>Properties</a>
        <a class="wa" href="https://wa.me/{{ config('cream.whatsapp.number') }}" target="_blank" rel="noopener"><x-icon name="wa"/>WhatsApp</a>
        <a href="tel:{{ config('cream.phone.tel') }}"><x-icon name="phone"/>Call</a>
    </div>
</nav>
