<?php

namespace App\Http\Controllers\Staff;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    private const STATUSES = ['new', 'read', 'replied', 'closed', 'spam'];

    public function index(Request $request): View
    {
        $tab = in_array($request->input('tab'), self::STATUSES, true)
            ? $request->input('tab') : 'new';

        $enquiries = Enquiry::query()->with('asset')
            ->where('status', $tab)
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $counts = collect(self::STATUSES)
            ->mapWithKeys(fn ($s) => [$s => Enquiry::where('status', $s)->count()])
            ->all();

        return view('staff.enquiries.index', [
            'enquiries' => $enquiries,
            'tab' => $tab,
            'counts' => $counts,
            'types' => EnquiryType::cases(),
            'activeType' => $request->input('type'),
        ]);
    }

    public function show(Enquiry $enquiry): View
    {
        $enquiry->load('asset', 'handler');

        // Seeing a new enquiry marks it read (keeps the "New" badge meaningful).
        if ($enquiry->status === EnquiryStatus::New) {
            $enquiry->update([
                'status' => EnquiryStatus::Read,
                'handled_by' => auth()->id(),
                'handled_at' => now(),
            ]);
            ActivityLogger::log('read', $enquiry);
        }

        return view('staff.enquiries.show', ['enquiry' => $enquiry]);
    }

    public function updateStatus(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
        ]);

        $enquiry->update([
            'status' => $data['status'],
            'handled_by' => auth()->id(),
            'handled_at' => now(),
        ]);
        ActivityLogger::log('status:'.$data['status'], $enquiry);

        return back()->with('flash', 'Marked as '.EnquiryStatus::from($data['status'])->label().'.');
    }

    public function destroy(Enquiry $enquiry): RedirectResponse
    {
        ActivityLogger::log('delete', $enquiry);
        $enquiry->delete();

        return redirect()->route('staff.enquiries.index')->with('flash', 'Enquiry deleted.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:read,replied,closed,spam,delete'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $enquiries = Enquiry::whereIn('id', $data['ids'])->get();

        foreach ($enquiries as $enquiry) {
            if ($data['action'] === 'delete') {
                ActivityLogger::log('delete', $enquiry);
                $enquiry->delete();

                continue;
            }

            $enquiry->update([
                'status' => $data['action'],
                'handled_by' => auth()->id(),
                'handled_at' => now(),
            ]);
            ActivityLogger::log('status:'.$data['action'], $enquiry);
        }

        return back()->with('flash', count($enquiries).' enquiry(ies) updated.');
    }
}
