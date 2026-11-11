<?php

namespace App\Http\Controllers\Staff;

use App\Enums\TestimonialStatus;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Support\ActivityLogger;
use App\Support\ImageProcessor;
use App\Support\QrCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $tab = in_array($request->input('tab'), ['pending', 'approved', 'archived', 'spam'], true)
            ? $request->input('tab') : 'pending';

        $testimonials = Testimonial::query()
            ->where('status', $tab)
            ->when($tab === 'approved', fn ($q) => $q->orderByDesc('is_featured')->orderBy('sort_order'))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $counts = [
            'pending' => Testimonial::pending()->count(),
            'approved' => Testimonial::approved()->count(),
            'archived' => Testimonial::archived()->count(),
            'spam' => Testimonial::spam()->count(),
        ];

        return view('staff.testimonials.index', compact('testimonials', 'tab', 'counts'));
    }

    /**
     * Light-touch tidy of the display fields before (or after) publishing.
     * Staff may fix a name's casing or add a "role · location, year" line —
     * they never edit the testimonial's wording.
     */
    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        $update = [
            'name' => $data['name'],
            'role' => $data['role'] ?? null,
            'rating' => $data['rating'],
            'sort_order' => $data['sort_order'] ?? $testimonial->sort_order,
        ];

        $processor = new ImageProcessor;

        if ($request->hasFile('photo')) {
            $result = $processor->process($request->file('photo')->getRealPath(), 'testimonials');
            $processor->delete([$result['path']]); // avatar only needs the 800px variant
            if ($testimonial->photo_path) {
                $processor->delete([$testimonial->photo_path]);
            }
            $update['photo_path'] = $result['path_sm'];
        } elseif ($request->boolean('remove_photo') && $testimonial->photo_path) {
            $processor->delete([$testimonial->photo_path]);
            $update['photo_path'] = null;
        }

        $testimonial->update($update);
        ActivityLogger::log('update', $testimonial);

        return back()->with('flash', 'Testimonial details updated.');
    }

    public function approve(Testimonial $testimonial): RedirectResponse
    {
        if (! $testimonial->canPublish()) {
            return back()->with('flash_error', 'This feedback cannot be published — the author did not agree to publication.');
        }

        $this->setStatus($testimonial, TestimonialStatus::Approved, 'approve');

        return back()->with('flash', 'Testimonial published.');
    }

    public function archive(Testimonial $testimonial): RedirectResponse
    {
        $this->setStatus($testimonial, TestimonialStatus::Archived, 'archive');

        return back()->with('flash', 'Testimonial archived.');
    }

    public function spam(Testimonial $testimonial): RedirectResponse
    {
        $this->setStatus($testimonial, TestimonialStatus::Spam, 'spam');

        return back()->with('flash', 'Testimonial marked as spam.');
    }

    public function feature(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_featured' => ! $testimonial->is_featured]);
        ActivityLogger::log('update', $testimonial, properties: ['is_featured' => $testimonial->is_featured]);

        return back()->with('flash', $testimonial->is_featured ? 'Pinned to the homepage.' : 'Unpinned from the homepage.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        ActivityLogger::log('delete', $testimonial);
        $testimonial->delete();

        return back()->with('flash', 'Testimonial deleted.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:approve,archive,spam,delete'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $testimonials = Testimonial::whereIn('id', $data['ids'])->get();
        $skipped = 0;

        foreach ($testimonials as $t) {
            match ($data['action']) {
                'approve' => $t->canPublish()
                    ? $this->setStatus($t, TestimonialStatus::Approved, 'approve')
                    : $skipped++,
                'archive' => $this->setStatus($t, TestimonialStatus::Archived, 'archive'),
                'spam' => $this->setStatus($t, TestimonialStatus::Spam, 'spam'),
                'delete' => tap($t, fn ($x) => ActivityLogger::log('delete', $x))->delete(),
            };
        }

        $msg = (count($testimonials) - $skipped).' testimonial(s) updated.';
        if ($skipped > 0) {
            $msg .= " {$skipped} skipped (no publish consent).";
        }

        return back()->with('flash', $msg);
    }

    /** Printable card with a QR code to the public feedback form. */
    public function qr(QrCode $qr): View
    {
        $url = route('feedback', ['src' => 'qr']);

        return view('staff.testimonials.qr', [
            'url' => $url,
            'svg' => $qr->inlineSvg($url, 320),
        ]);
    }

    protected function setStatus(Testimonial $testimonial, TestimonialStatus $status, string $action): void
    {
        $testimonial->update([
            'status' => $status,
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);
        ActivityLogger::log($action, $testimonial);
    }
}
