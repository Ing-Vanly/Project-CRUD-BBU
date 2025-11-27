<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class BusinessSettingController extends Controller
{
    /**
     * Display the business settings form.
     */
    public function edit()
    {
        $this->enforcePermission('business_setting.view');

        $setting = BusinessSetting::first();

        if (!$setting) {
            $setting = new BusinessSetting([
                'currency_code' => 'USD',
                'timezone' => config('app.timezone'),
            ]);
        }

        return view('admin.backends.business_settings.edit', [
            'businessSetting' => $setting,
        ]);
    }

    /**
     * Update the business settings.
     */
    public function update(Request $request)
    {
        $this->enforcePermission('business_setting.update');

        $validation = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'currency_code' => 'required|string|size:3',
            'timezone' => 'required|timezone',
            'footer_text' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:1024',
        ]);

        $data = Arr::except($validation, ['logo', 'favicon']);
        $data['currency_code'] = strtoupper($data['currency_code']);
        $setting = BusinessSetting::first();

        $data = $this->attachUploads($request, $data, $setting);

        if ($setting) {
            $setting->update($data);
        } else {
            $setting = BusinessSetting::create($data);
        }

        return redirect()
            ->route('business-setting.edit')
            ->with('success', 1)
            ->with('msg', 'Business settings updated successfully!');
    }

    /**
     * Attach uploaded files to payload.
     */
    private function attachUploads(Request $request, array $data, ?BusinessSetting $setting = null): array
    {
        foreach (['logo', 'favicon'] as $field) {
            $path = $this->storeImage($request, $field, $setting?->{$field});

            if ($path) {
                $data[$field] = $path;
            } elseif ($setting && $setting->{$field}) {
                $data[$field] = $setting->{$field};
            }
        }

        return $data;
    }

    /**
     * Handle upload storage.
     */
    private function storeImage(Request $request, string $field, ?string $existingPath = null): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);
        $destination = public_path('uploads/business_settings');

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        if ($existingPath) {
            $this->deleteFile($existingPath);
        }

        $filename = uniqid($field . '_') . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/business_settings/' . $filename;
    }

    /**
     * Delete a file if it exists.
     */
    private function deleteFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        $fullPath = public_path($path);

        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}
