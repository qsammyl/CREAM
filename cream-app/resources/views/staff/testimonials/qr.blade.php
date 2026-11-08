<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Feedback QR card · Credence Staff</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500;600&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root{--emerald:#0B5B45;--ink:#15201C;--gold:#B8893B;--paper:#FBF9F4;--line:#E6E1D6}
        *{box-sizing:border-box}
        body{margin:0;background:#EDEAE2;color:var(--ink);font-family:'Geist',system-ui,sans-serif;-webkit-print-color-adjust:exact;print-color-adjust:exact}
        .bar{display:flex;gap:10px;justify-content:center;padding:20px;flex-wrap:wrap}
        .bar a,.bar button{font-family:'Geist Mono',monospace;font-size:12px;letter-spacing:.06em;text-transform:uppercase;padding:10px 18px;border-radius:8px;border:1px solid var(--ink);background:#fff;color:var(--ink);cursor:pointer;text-decoration:none}
        .bar .solid{background:var(--emerald);color:#fff;border-color:var(--emerald)}
        .sheet{display:flex;justify-content:center;padding:0 20px 40px}
        .card{width:340px;background:var(--paper);border:1px solid var(--line);border-radius:18px;padding:34px 30px;text-align:center}
        .brand{font-family:'Geist Mono',monospace;font-size:11px;letter-spacing:.28em;text-transform:uppercase;color:var(--emerald);margin-bottom:22px}
        .card h1{font-family:'Instrument Serif',Georgia,serif;font-weight:400;font-size:30px;line-height:1.15;margin:0 0 10px}
        .card h1 em{color:var(--gold);font-style:italic}
        .card p{color:#50584f;font-size:14.5px;line-height:1.55;margin:0 0 22px}
        .qr{width:230px;height:230px;margin:0 auto 18px;background:#fff;border:1px solid var(--line);border-radius:12px;padding:14px}
        .qr svg{width:100%;height:100%;display:block}
        .stars{color:var(--gold);font-size:18px;letter-spacing:3px;margin-bottom:8px}
        .url{font-family:'Geist Mono',monospace;font-size:12px;color:var(--ink);word-break:break-all}
        .foot{font-family:'Geist Mono',monospace;font-size:9.5px;letter-spacing:.14em;text-transform:uppercase;color:#9a9a90;margin-top:20px}
        @media print{
            body{background:#fff}
            .bar{display:none}
            .sheet{padding:0}
            .card{border:none}
        }
    </style>
</head>
<body>
    <div class="bar">
        <button id="printBtn" type="button" class="solid">Print this card</button>
        <a href="{{ route('staff.testimonials.index') }}">Back to testimonials</a>
    </div>

    <div class="sheet">
        <div class="card">
            <div class="brand">Credence Real Estate</div>
            <div class="stars">★★★★★</div>
            <h1>How did we<br><em>do?</em></h1>
            <p>Scan this code with your phone camera to share your experience. It takes less than a minute.</p>
            <div class="qr">{!! $svg !!}</div>
            <div class="url">{{ \Illuminate\Support\Str::of($url)->after('://') }}</div>
            <div class="foot">Building Value · Creating Legacy</div>
        </div>
    </div>
    <script src="/assets/qr-card.js"></script>
</body>
</html>
