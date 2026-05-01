<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('settings.index', [
            'settingKeys' => Setting::OPTION_KEYS,
            'settings' => Setting::query()
                ->whereIn('meta_key', array_keys(Setting::OPTION_KEYS))
                ->orderBy('meta_key')
                ->orderBy('meta_value')
                ->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'meta_key' => trim((string) $request->input('meta_key')),
            'meta_value' => $this->normalizeMetaValue(
                $request->input('meta_key'),
                $request->input('meta_value')
            ),
        ]);

        $data = $request->validate([
            'meta_key' => ['required', Rule::in(array_keys(Setting::OPTION_KEYS))],
            'meta_value' => [
                'required',
                'string',
                'max:255',
                Rule::unique('settings', 'meta_value')->where(
                    fn ($query) => $query->where('meta_key', $request->input('meta_key'))
                ),
            ],
        ]);

        Setting::create($data);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Setting option added.');
    }

    public function destroy(Setting $setting): RedirectResponse
    {
        $setting->delete();

        return redirect()
            ->route('settings.index')
            ->with('success', 'Setting option deleted.');
    }

    private function normalizeMetaValue(?string $metaKey, mixed $metaValue): string
    {
        $value = trim((string) $metaValue);

        if ($metaKey === Setting::KEY_CURRENCIES) {
            return strtoupper($value);
        }

        return $value;
    }
}