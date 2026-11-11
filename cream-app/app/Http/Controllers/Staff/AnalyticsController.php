<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /** Allowed look-back windows, in days. */
    private const RANGES = [7, 30, 90];

    public function index(Request $request): View
    {
        $days = (int) $request->query('days', 30);
        if (! in_array($days, self::RANGES, true)) {
            $days = 30;
        }

        $since = now()->subDays($days - 1)->startOfDay();

        // One grouped query gives per-day views + unique visitors (DATE() is
        // portable across SQLite and MariaDB). Visitors can't be deduplicated
        // across days by design, so the total is the sum of daily uniques.
        $rows = PageView::query()
            ->where('created_at', '>=', $since)
            ->selectRaw('DATE(created_at) as d, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')
            ->groupBy('d')
            ->get()
            ->keyBy('d');

        $series = $this->fillSeries($rows, $since, $days);

        $totalViews = (int) $series->sum('views');
        $totalVisitors = (int) $series->sum('visitors');
        $maxViews = max(1, (int) $series->max('views'));
        $busiest = $series->sortByDesc('views')->first();
        $todayKey = now()->format('Y-m-d');
        $viewsToday = (int) ($series->firstWhere('key', $todayKey)['views'] ?? 0);

        return view('staff.analytics.index', [
            'days' => $days,
            'ranges' => self::RANGES,
            'series' => $series,
            'maxViews' => $maxViews,
            'totalViews' => $totalViews,
            'totalVisitors' => $totalVisitors,
            'viewsToday' => $viewsToday,
            'busiest' => $busiest,
            'topPages' => $this->topPages($since),
            'topListings' => $this->topListings($since),
            'topReferrers' => $this->topColumn($since, 'referer_host'),
            'topSources' => $this->topColumn($since, 'utm_source'),
            'topCountries' => $this->topColumn($since, 'country'),
        ]);
    }

    /**
     * Expand the grouped rows into one entry per calendar day (zero-filled),
     * each already carrying the bar heights the chart needs.
     *
     * @param  Collection<string, PageView>  $rows
     * @return Collection<int, array<string, mixed>>
     */
    private function fillSeries($rows, Carbon $since, int $days)
    {
        $max = max(1, (int) $rows->max('views'));
        $out = collect();

        for ($i = 0; $i < $days; $i++) {
            $day = $since->copy()->addDays($i);
            $key = $day->format('Y-m-d');
            $views = (int) ($rows[$key]->views ?? 0);
            $visitors = (int) ($rows[$key]->visitors ?? 0);

            $out->push([
                'key' => $key,
                'label' => $day->format('d M'),
                'views' => $views,
                'visitors' => $visitors,
                'views_pct' => (int) round($views / $max * 100),
                'visitors_pct' => (int) round($visitors / $max * 100),
            ]);
        }

        return $out;
    }

    /**
     * @return Collection<int, object>
     */
    private function topPages(Carbon $since)
    {
        return PageView::query()
            ->where('created_at', '>=', $since)
            ->select('path', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT visitor_hash) as visitors'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(12)
            ->get();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function topListings(Carbon $since)
    {
        $counts = PageView::query()
            ->where('created_at', '>=', $since)
            ->whereNotNull('asset_id')
            ->select('asset_id', DB::raw('COUNT(*) as views'))
            ->groupBy('asset_id')
            ->orderByDesc('views')
            ->limit(8)
            ->pluck('views', 'asset_id');

        if ($counts->isEmpty()) {
            return collect();
        }

        $assets = Asset::withTrashed()->whereIn('id', $counts->keys())->get()->keyBy('id');

        return $counts->map(fn ($views, $id) => [
            'asset' => $assets->get($id),
            'views' => (int) $views,
        ])->values();
    }

    /**
     * Top non-null values of a single column (referrers / sources / countries).
     *
     * @return Collection<int, object>
     */
    private function topColumn(Carbon $since, string $column)
    {
        return PageView::query()
            ->where('created_at', '>=', $since)
            ->whereNotNull($column)
            ->select($column.' as label', DB::raw('COUNT(*) as views'))
            ->groupBy($column)
            ->orderByDesc('views')
            ->limit(8)
            ->get();
    }
}
