@if (config('turnstile.sitekey'))
    @push('scripts')
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endpush
    <div class="field">
        <div class="cf-turnstile" data-sitekey="{{ config('turnstile.sitekey') }}" data-theme="light"></div>
        @error('cf-turnstile-response')
            <div class="field-err">{{ $message }}</div>
        @enderror
    </div>
@endif
