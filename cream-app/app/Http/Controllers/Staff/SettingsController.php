<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\ActivityLogger;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('staff.settings.edit', [
            'schema' => SiteSettings::schema(),
            'current' => collect(SiteSettings::fields())
                ->mapWithKeys(fn ($f) => [$f['key'] => SiteSettings::current($f)])
                ->all(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (SiteSettings::fields() as $field) {
            $rules[$field['key']] = match ($field['type']) {
                'email' => ['nullable', 'email', 'max:255'],
                'url' => ['nullable', 'url', 'max:255'],
                'number' => ['nullable', 'integer', 'min:0', 'max:3650'],
                default => ['nullable', 'string', 'max:255'],
            };
        }

        $data = $request->validate($rules);

        foreach (SiteSettings::fields() as $field) {
            $value = $data[$field['key']] ?? null;
            if ($field['type'] === 'number') {
                $value = (int) $value;
            }
            Setting::put($field['key'], $value === '' ? null : $value);
        }

        ActivityLogger::log('settings:update', null, $request, properties: ['keys' => array_column(SiteSettings::fields(), 'key')]);

        return back()->with('flash', 'Settings saved.');
    }
}
