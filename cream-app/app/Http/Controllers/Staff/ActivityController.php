<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(): View
    {
        $logs = ActivityLog::with('user')->latest()->paginate(50);

        return view('staff.activity', ['logs' => $logs]);
    }
}
