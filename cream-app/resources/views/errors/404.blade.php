@php($title = 'Page Not Found | Credence Real Estate')
@php($description = 'The page you were looking for could not be found. Browse our properties and services or contact Credence Real Estate.')
@php($includePopup = false)
@extends('public.layout')

@push('head')
<style>
.nf{min-height:72vh;display:flex;align-items:center;justify-content:center;text-align:center;background:#0F0E0C;color:#fff;padding:160px 0 90px}
.nf h1{font-family:var(--serif);font-weight:400;font-size:clamp(60px,12vw,140px);line-height:.9;margin:0 0 10px}
.nf h1 em{font-style:italic;color:var(--gold)}
.nf p{color:rgba(255,255,255,.78);font-size:18px;margin:0 auto 30px;max-width:46ch}
.nf .actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.nf .btn-ghost{color:#fff;border-color:rgba(255,255,255,.35)}
.nf .btn-ghost:hover{background:#fff;color:var(--ink);border-color:#fff}
</style>
@endpush

@section('content')
<section class="nf"><div class="container">
    <div class="eyebrow" style="color:rgba(255,255,255,.6);justify-content:center"><span class="dot"></span>Error 404</div>
    <h1>Page <em>not found.</em></h1>
    <p>The page you were looking for has moved or no longer exists. Try one of these instead.</p>
    <div class="actions">
        <a class="btn-gold" href="/properties">View properties</a>
        <a class="btn-ghost" href="/services">Our services</a>
        <a class="btn-ghost" href="/contact">Contact</a>
        <a class="btn-ghost" href="https://wa.me/{{ config('cream.whatsapp.number') }}" target="_blank" rel="noopener"><x-icon name="wa"/> WhatsApp</a>
    </div>
</div></section>
@endsection
