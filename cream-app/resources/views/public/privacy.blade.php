@extends('public.layout')

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Privacy']]"
        h1="Privacy<br/><em>notice.</em>"
        sub="How we collect, use and protect the information you share through this website."/>
    <section class="sec bone"><div class="container"><div class="prose">
        <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">What we collect</h3>
        <p>When you use a form on this site we collect the details you provide: your name, email address, phone or WhatsApp number, country, budget and timeline (where asked), your message, and any property or land details you send us. We do not currently collect uploaded files through the website — where documents are needed, we ask you to send them by WhatsApp or email.</p>
        <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">How your information is handled</h3>
        <p>Form submissions are delivered to us by email through <strong>Web3Forms</strong>, a third-party form service, which processes the submission in order to deliver it. We use your information only to respond to your enquiry and to provide the services you ask about.</p>
        <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">Comments on articles</h3>
        <p>If you comment on an article we collect your name, email address and comment. Your <strong>email is never shown publicly</strong> — it is used only so we can contact you if needed. Comments are <strong>moderated</strong> and appear only once approved; we may decline or remove any comment. You can ask us to remove a comment you posted by contacting us. We keep a hashed form of your IP address for abuse control, never the raw address.</p>
        <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">How long we keep it</h3>
        <p>We keep enquiry information for as long as needed to serve you and to meet our record-keeping obligations. {{-- TODO(sam): confirm the retention period. --}} Retention period: to be confirmed.</p>
        <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">Your choices</h3>
        <p>You can ask us to show you what we hold, correct it, or delete it. To do so, contact us at <a href="mailto:{{ config('cream.email') }}">{{ config('cream.email') }}</a> or on WhatsApp at {{ config('cream.whatsapp.display') }}.</p>
        <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">Data controller</h3>
        <p>{{ config('cream.legal_name') }}, a subsidiary of {{ config('cream.parent') }}. {{-- TODO(sam): confirm the data-controller legal entity, registered address and a contact for data requests. --}}</p>
        <p class="small">This notice is provided in good faith and is not legal advice. {{-- TODO(sam): have this reviewed by counsel. --}}</p>
    </div></div></section>
@endsection
