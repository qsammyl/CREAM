<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetMedia;
use App\Support\ActivityLogger;
use App\Support\ImageProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class AssetMediaController extends Controller
{
    public function store(Request $request, Asset $asset, ImageProcessor $processor): RedirectResponse
    {
        $this->authorize('update', $asset);

        $request->validate([
            'photos' => ['required', 'array', 'max:20'],
            'photos.*' => ['file', 'max:10240', 'mimes:jpeg,jpg,png,webp'], // 10 MB
        ], [], ['photos.*' => 'photo']);

        $next = ($asset->media()->max('sort_order') ?? -1) + 1;
        $hadMedia = $asset->media()->exists();
        $done = 0;

        foreach ($request->file('photos') as $i => $file) {
            try {
                $res = $processor->process($file->getRealPath());
            } catch (Throwable $e) {
                return back()->withErrors(['photos' => "“{$file->getClientOriginalName()}”: {$e->getMessage()}"]);
            }

            $asset->media()->create([
                'disk' => 'uploads',
                'path' => $res['path'],
                'path_sm' => $res['path_sm'],
                'original_name' => $file->getClientOriginalName(),
                'alt' => $asset->title,
                'is_cover' => ! $hadMedia && $i === 0,
                'sort_order' => $next++,
                'width' => $res['width'],
                'height' => $res['height'],
            ]);
            $done++;
        }

        ActivityLogger::log('update', $asset, properties: ['media_added' => $done]);

        return back()->with('flash', "{$done} photo(s) uploaded.");
    }

    public function updateAlt(Request $request, AssetMedia $media): RedirectResponse
    {
        $this->authorize('update', $media->asset);
        $request->validate(['alt' => ['nullable', 'string', 'max:255']]);
        $media->update(['alt' => $request->input('alt')]);

        return back()->with('flash', 'Caption saved.');
    }

    public function cover(AssetMedia $media): RedirectResponse
    {
        $this->authorize('update', $media->asset);
        $media->asset->media()->update(['is_cover' => false]);
        $media->update(['is_cover' => true]);

        return back()->with('flash', 'Cover photo set.');
    }

    public function move(AssetMedia $media, string $dir): RedirectResponse
    {
        $this->authorize('update', $media->asset);

        $list = $media->asset->media()->orderBy('sort_order')->orderBy('id')->get()->values();
        $idx = $list->search(fn ($m) => $m->id === $media->id);
        $swap = $dir === 'up' ? $idx - 1 : $idx + 1;

        if ($swap >= 0 && $swap < $list->count()) {
            $other = $list[$swap];
            [$a, $b] = [$media->sort_order, $other->sort_order];
            if ($a === $b) {
                $b = $dir === 'up' ? $a - 1 : $a + 1;
            }
            $media->update(['sort_order' => $b]);
            $other->update(['sort_order' => $a]);
        }

        return back();
    }

    public function destroy(AssetMedia $media, ImageProcessor $processor): RedirectResponse
    {
        $this->authorize('update', $media->asset);

        $asset = $media->asset;
        $wasCover = $media->is_cover;
        $processor->delete([$media->path, $media->path_sm]);
        $media->delete();

        if ($wasCover && ($first = $asset->media()->orderBy('sort_order')->first())) {
            $first->update(['is_cover' => true]);
        }

        ActivityLogger::log('update', $asset, properties: ['media_removed' => true]);

        return back()->with('flash', 'Photo removed.');
    }
}
