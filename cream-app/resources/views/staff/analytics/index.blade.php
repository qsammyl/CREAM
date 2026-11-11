@extends('layouts.dashboard')

@section('title', 'Analytics')
@section('heading', 'Analytics')
@section('sub', 'First-party, cookieless traffic — no cookies, no third parties, visitors counted by a daily-rotating hash.')

@section('actions')
    <div class="tabs">
        @foreach ($ranges as $r)
            <a href="{{ route('staff.analytics', ['days' => $r]) }}" class="{{ $days === $r ? 'on' : '' }}">{{ $r }} days</a>
        @endforeach
    </div>
@endsection

@push('head')
<style>
.tabs{display:flex;gap:6px;flex-wrap:wrap}
.tabs a{padding:8px 14px;border-radius:999px;font-size:13px;border:1px solid var(--line);color:var(--ink-2)}
.tabs a.on{background:var(--emerald);color:#fff;border-color:var(--emerald)}
.chart-wrap{padding:22px}
.chart{display:flex;align-items:flex-end;gap:2px;height:190px}
.chart .col{flex:1 1 0;position:relative;height:100%;min-width:0;border-radius:3px 3px 0 0}
.chart .col:hover{background:var(--bone)}
.chart .views{position:absolute;left:0;right:0;bottom:0;background:var(--bone-2);border-radius:3px 3px 0 0;min-height:1px}
.chart .vis{position:absolute;left:0;right:0;bottom:0;background:var(--emerald);border-radius:3px 3px 0 0}
.chart-axis{display:flex;justify-content:space-between;font-family:var(--mono);font-size:10px;color:var(--ink-3);margin-top:8px}
.legend{display:flex;gap:18px;font-size:12px;color:var(--ink-3);margin-top:14px;align-items:center}
.legend span{display:inline-flex;align-items:center;gap:7px}
.legend i{width:12px;height:12px;border-radius:3px;display:inline-block}
.a-cols{display:grid;grid-template-columns:1fr 1fr;gap:24px}
@media(max-width:860px){.a-cols{grid-template-columns:1fr}}
.barcell{display:flex;align-items:center;gap:10px}
.barcell .track{flex:1;height:7px;background:var(--bone-2);border-radius:999px;overflow:hidden;min-width:40px}
.barcell .fill{height:100%;background:var(--emerald);border-radius:999px}
.barcell .num{font-family:var(--mono);font-size:12px;color:var(--ink-2);white-space:nowrap}
td .path{font-family:var(--mono);font-size:12.5px;word-break:break-all}
</style>
@endpush

@section('content')
    <div class="stat-row">
        <div class="stat">
            <div class="k">Page views</div>
            <div class="v">{{ number_format($totalViews) }}</div>
        </div>
        <div class="stat">
            <div class="k">Unique visitors</div>
            <div class="v">{{ number_format($totalVisitors) }}</div>
        </div>
        <div class="stat">
            <div class="k">Views today</div>
            <div class="v">{{ number_format($viewsToday) }}</div>
        </div>
        <div class="stat">
            <div class="k">Busiest day</div>
            <div class="v" style="font-size:26px">{{ $busiest && $busiest['views'] ? $busiest['label'] : '—' }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Daily traffic · last {{ $days }} days</h2>
        </div>
        <div class="chart-wrap">
            @if ($totalViews === 0)
                <p class="muted">No page views recorded in this window yet. Traffic will appear here as visitors browse the public site.</p>
            @else
                <div class="chart">
                    @foreach ($series as $d)
                        <div class="col" title="{{ $d['label'] }} — {{ $d['views'] }} views, {{ $d['visitors'] }} visitors">
                            <div class="views" style="height:{{ $d['views_pct'] }}%"></div>
                            <div class="vis" style="height:{{ $d['visitors_pct'] }}%"></div>
                        </div>
                    @endforeach
                </div>
                <div class="chart-axis">
                    <span>{{ $series->first()['label'] }}</span>
                    <span>{{ $series->last()['label'] }}</span>
                </div>
                <div class="legend">
                    <span><i style="background:var(--bone-2)"></i> Page views</span>
                    <span><i style="background:var(--emerald)"></i> Unique visitors</span>
                </div>
            @endif
        </div>
    </div>

    <div class="a-cols">
        <div class="panel">
            <div class="panel-head"><h2>Top pages</h2></div>
            @if ($topPages->isEmpty())
                <div class="panel-body muted">No data yet.</div>
            @else
                <table class="data">
                    <thead><tr><th>Page</th><th style="text-align:right">Views</th><th style="text-align:right">Visitors</th></tr></thead>
                    <tbody>
                        @foreach ($topPages as $p)
                            <tr>
                                <td><span class="path">{{ $p->path }}</span></td>
                                <td style="text-align:right">{{ number_format($p->views) }}</td>
                                <td style="text-align:right" class="muted">{{ number_format($p->visitors) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Most-viewed listings</h2></div>
            @if ($topListings->isEmpty())
                <div class="panel-body muted">No listing views yet.</div>
            @else
                <table class="data">
                    <thead><tr><th>Listing</th><th style="text-align:right">Views</th></tr></thead>
                    <tbody>
                        @foreach ($topListings as $row)
                            <tr>
                                <td>
                                    @if ($row['asset'])
                                        <a href="{{ route('staff.assets.edit', $row['asset']) }}">{{ $row['asset']->title }}</a>
                                        <div class="muted" style="font-size:12px">{{ $row['asset']->reference }}</div>
                                    @else
                                        <span class="muted">Deleted listing</span>
                                    @endif
                                </td>
                                <td style="text-align:right">{{ number_format($row['views']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Top referrers</h2></div>
            @if ($topReferrers->isEmpty())
                <div class="panel-body muted">No external referrers recorded. Direct visits and same-site clicks aren’t counted here.</div>
            @else
                <div class="panel-body">
                    @foreach ($topReferrers as $r)
                        <div class="barcell" style="margin-bottom:12px">
                            <span class="num" style="width:170px;overflow:hidden;text-overflow:ellipsis">{{ $r->label }}</span>
                            <div class="track"><div class="fill" style="width:{{ (int) round($r->views / $topReferrers->max('views') * 100) }}%"></div></div>
                            <span class="num">{{ number_format($r->views) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Campaign sources &amp; countries</h2></div>
            <div class="panel-body">
                <div class="muted" style="font-family:var(--mono);font-size:10px;letter-spacing:.12em;text-transform:uppercase;margin-bottom:10px">utm_source</div>
                @if ($topSources->isEmpty())
                    <p class="muted" style="margin-bottom:18px">No tagged campaign links yet (add <span class="path">?utm_source=…</span> to a shared link).</p>
                @else
                    @foreach ($topSources as $s)
                        <div class="barcell" style="margin-bottom:10px">
                            <span class="num" style="width:150px;overflow:hidden;text-overflow:ellipsis">{{ $s->label }}</span>
                            <div class="track"><div class="fill" style="width:{{ (int) round($s->views / $topSources->max('views') * 100) }}%"></div></div>
                            <span class="num">{{ number_format($s->views) }}</span>
                        </div>
                    @endforeach
                @endif

                <div class="muted" style="font-family:var(--mono);font-size:10px;letter-spacing:.12em;text-transform:uppercase;margin:18px 0 10px">Country</div>
                @if ($topCountries->isEmpty())
                    <p class="muted">Country data appears once the site is live behind Cloudflare.</p>
                @else
                    @foreach ($topCountries as $c)
                        <div class="barcell" style="margin-bottom:10px">
                            <span class="num" style="width:60px">{{ $c->label }}</span>
                            <div class="track"><div class="fill" style="width:{{ (int) round($c->views / $topCountries->max('views') * 100) }}%"></div></div>
                            <span class="num">{{ number_format($c->views) }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
