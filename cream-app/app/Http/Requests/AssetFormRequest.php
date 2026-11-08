<?php

namespace App\Http\Requests;

use App\Enums\AssetCategory;
use App\Enums\AssetStatus;
use App\Enums\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class AssetFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by the controller's authorizeResource / policy
    }

    public function rules(): array
    {
        $assetId = $this->route('asset')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('assets', 'slug')->ignore($assetId)],
            'reference' => ['nullable', 'string', 'max:50', Rule::unique('assets', 'reference')->ignore($assetId)],
            'summary' => ['nullable', 'string'],
            'summary2' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],

            'category' => ['required', new Enum(AssetCategory::class)],
            'status' => ['required', new Enum(AssetStatus::class)],

            'is_exclusive' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_off_plan' => ['boolean'],
            'is_furnished' => ['boolean'],
            'title_verified' => ['boolean'],
            'verified_summary' => ['nullable', 'string'],

            'price' => ['nullable', 'integer', 'min:0'],
            'currency' => ['required', new Enum(Currency::class)],
            'price_period' => ['required', Rule::in(['total', 'month', 'night'])],
            'price_on_request' => ['boolean'],
            'price_note' => ['nullable', 'string', 'max:255'],

            'beds' => ['nullable', 'string', 'max:30'],
            'baths' => ['nullable', 'string', 'max:30'],
            'floor_area' => ['nullable', 'string', 'max:60'],
            'plot_size' => ['nullable', 'string', 'max:80'],
            'tenure' => ['nullable', 'string', 'max:80'],
            'survey' => ['nullable', 'string', 'max:80'],
            'access' => ['nullable', 'string', 'max:80'],
            'stage' => ['nullable', 'string', 'max:80'],
            'expected_delivery' => ['nullable', 'string', 'max:80'],
            'availability' => ['nullable', 'string', 'max:120'],
            'max_guests' => ['nullable', 'integer', 'min:1'],

            'location' => ['nullable', 'string', 'max:255'],
            'location_area' => ['nullable', 'string', 'max:120'],
            'location_city' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'max:120'],
            'area_key' => ['nullable', 'string', 'max:120'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            'features_text' => ['nullable', 'string'],
            'documents_text' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }

    /**
     * Mapped, cleaned attributes ready to fill the model.
     *
     * @return array<string, mixed>
     */
    public function assetData(): array
    {
        $data = $this->safe()->except(['features_text', 'documents_text']);

        // Checkboxes
        foreach (['is_exclusive', 'is_featured', 'is_off_plan', 'is_furnished', 'title_verified', 'price_on_request'] as $flag) {
            $data[$flag] = $this->boolean($flag);
        }

        // Newline-separated lists → arrays (null when empty)
        $data['features'] = $this->linesToArray($this->input('features_text'));
        $data['documents_available'] = $this->linesToArray($this->input('documents_text'));

        return $data;
    }

    private function linesToArray(?string $text): ?array
    {
        if (blank($text)) {
            return null;
        }

        $items = collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn ($l) => trim($l))
            ->filter()
            ->values()
            ->all();

        return $items ?: null;
    }
}
