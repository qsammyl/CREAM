<?php

namespace App\Http\Controllers\Staff;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Comment;
use App\Models\Enquiry;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'new_enquiries' => Enquiry::where('status', 'new')->count(),
            'pending_comments' => Comment::where('status', 'pending')->count(),
            'pending_testimonials' => Testimonial::where('status', 'pending')->count(),
            'staff_total' => User::count(),
            'admins' => User::where('role', Role::Admin)->count(),
            'active' => User::where('is_active', true)->count(),
        ];

        $recentActivity = $request->user()->isAdmin()
            ? ActivityLog::with('user')->latest()->limit(8)->get()
            : collect();

        return view('staff.overview', [
            'stats' => $stats,
            'recentActivity' => $recentActivity,
        ]);
    }
}
