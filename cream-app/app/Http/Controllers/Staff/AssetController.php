<?php

namespace App\Http\Controllers\Staff;

use App\Enums\AssetCategory;
use App\Enums\AssetStatus;
use App\Enums\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssetFormRequest;
use App\Models\Asset;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Asset::class);

        $assets = Asset::query()
            ->with('media')
            ->when($request->filled('q'), function ($q) use ($request) {
                $s = $request->string('q');
                $q->where(fn ($w) => $w->where('title', 'like', "%{$s}%")
                    ->orWhere('reference', 'like', "%{$s}%")
                    ->orWhere('location', 'like', "%{$s}%")
                    ->orWhere('location_area', 'like', "%{$s}%"));
            })
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->input('category')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->boolean('exclusive'), fn ($q) => $q->where('is_exclusive', true))
            ->when($request->boolean('featured'), fn ($q) => $q->where('is_featured', true))
            ->when(true, function ($q) use ($request) {
                match ($request->input('sort')) {
                    'price' => $q->orderByDesc('price'),
                    'title' => $q->orderBy('title'),
                    default => $q->orderByDesc('updated_at'),
                };
            })
            ->paginate(25)
            ->withQueryString();

        return view('staff.assets.index', [
            'assets' => $assets,
            'categories' => AssetCategory::options(),
            'statuses' => AssetStatus::options(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Asset::class);

        return view('staff.assets.form', [
            'asset' => new Asset(['status' => AssetStatus::Draft, 'currency' => Currency::USD, 'price_period' => 'total', 'country' => 'Ghana', 'location_city' => 'Accra']),
            'categories' => AssetCategory::options(),
            'statuses' => AssetStatus::options(),
            'currencies' => Currency::options(),
        ]);
    }

    public function store(AssetFormRequest $request): RedirectResponse
    {
        $this->authorize('create', Asset::class);

        $data = $request->assetData();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;
        $this->applyPublishIntent($request, $data);

        $asset = Asset::create($data);
        ActivityLogger::log('create', $asset);

        return redirect()->route('staff.assets.edit', $asset)
            ->with('flash', 'Asset created. Add photos below.');
    }

    public function edit(Asset $asset): View
    {
        $this->authorize('update', $asset);
        $asset->load('media');

        return view('staff.assets.form', [
            'asset' => $asset,
            'categories' => AssetCategory::options(),
            'statuses' => AssetStatus::options(),
            'currencies' => Currency::options(),
        ]);
    }

    public function update(AssetFormRequest $request, Asset $asset): RedirectResponse
    {
        $this->authorize('update', $asset);

        $data = $request->assetData();
        $data['updated_by'] = $request->user()->id;
        $this->applyPublishIntent($request, $data, $asset);

        $asset->update($data);
        ActivityLogger::log('update', $asset);

        return redirect()->route('staff.assets.edit', $asset)->with('flash', 'Asset saved.');
    }

    public function destroy(Asset $asset): RedirectResponse
    {
        $this->authorize('delete', $asset);

        $asset->delete(); // soft delete → bin
        ActivityLogger::log('delete', $asset);

        return redirect()->route('staff.assets.index')->with('flash', 'Asset moved to the bin.');
    }

    public function duplicate(Asset $asset): RedirectResponse
    {
        $this->authorize('create', Asset::class);

        $copy = $asset->replicate(['slug', 'reference', 'published_at']);
        $copy->title = $asset->title.' (copy)';
        $copy->slug = Asset::uniqueSlug($asset->title.' copy');
        $copy->reference = Asset::generateReference($asset->title);
        $copy->status = AssetStatus::Draft;
        $copy->is_featured = false;
        $copy->published_at = null;
        $copy->created_by = auth()->id();
        $copy->updated_by = auth()->id();
        $copy->save();

        ActivityLogger::log('create', $copy, properties: ['duplicated_from' => $asset->id]);

        return redirect()->route('staff.assets.edit', $copy)
            ->with('flash', 'Duplicated as a draft. Photos are not copied — add them below.');
    }

    public function preview(Asset $asset): View
    {
        $this->authorize('view', $asset);
        $asset->load('media');

        return view('public.listing', [
            'asset' => $asset,
            'active' => 'assets',
            'title' => $asset->title.' (preview)',
            'description' => $asset->summary,
            'includePopup' => false,
        ]);
    }

    /* ---------------- Bin (soft-deleted) ---------------- */

    public function bin(): View
    {
        $this->authorize('viewAny', Asset::class);

        $assets = Asset::onlyTrashed()->with('media')->orderByDesc('deleted_at')->paginate(25);

        return view('staff.assets.bin', ['assets' => $assets]);
    }

    public function restore(int $id): RedirectResponse
    {
        $asset = Asset::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $asset);
        $asset->restore();
        ActivityLogger::log('restore', $asset);

        return redirect()->route('staff.assets.bin')->with('flash', 'Asset restored.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $asset = Asset::onlyTrashed()->with('media')->findOrFail($id);
        $this->authorize('forceDelete', $asset);

        // Remove stored image files, then the record (cascades media rows).
        $processor = app(\App\Support\ImageProcessor::class);
        foreach ($asset->media as $m) {
            $processor->delete([$m->path, $m->path_sm]);
        }
        $asset->forceDelete();
        ActivityLogger::log('delete', $asset, properties: ['permanent' => true]);

        return redirect()->route('staff.assets.bin')->with('flash', 'Asset permanently deleted.');
    }

    /**
     * If the "Publish" button was used, force the published status and stamp
     * published_at the first time it goes live.
     *
     * @param  array<string, mixed>  $data
     */
    protected function applyPublishIntent(Request $request, array &$data, ?Asset $asset = null): void
    {
        if ($request->input('publish')) {
            $data['status'] = AssetStatus::Published->value;
        }

        $becomingPublished = ($data['status'] ?? null) === AssetStatus::Published->value;
        $hadPublishedAt = $asset?->published_at;
        if ($becomingPublished && ! $hadPublishedAt) {
            $data['published_at'] = now();
        }
    }
}
