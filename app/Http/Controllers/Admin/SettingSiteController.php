<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrontendSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class SettingSiteController extends Controller
{

    public function index()
    {
        $settings = FrontendSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'site_keywords' => 'nullable|string',
            'site_logo' => 'nullable|max:2048',
            'site_favicon' => 'nullable|max:2048',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string|max:255',
            'site_facebook' => 'nullable|url',
            'site_instagram' => 'nullable|url',
            'site_tiktok' => 'nullable|url',
            'site_youtube' => 'nullable|url',
            'sub_introduction' => 'nullable|string|max:255',
            'main_introduction' => 'nullable|string|max:255',
            'description_introduction' => 'nullable|string',
            'image_introduction' => 'nullable|max:2048',
        ]);

        // Process and store files
        $fileFields = [
            'site_logo',
            'site_favicon',
            'image_introduction',
        ];

        try {
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . "_{$field}." . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('setting', $filename, 'public');

                    // Delete old file if exists
                    $old = FrontendSetting::where('key', $field)->first();
                    if ($old && $old->value && file_exists(public_path('storage/' . $old->value))) {
                        @unlink(public_path('storage/' . $old->value));
                    }

                    $validated[$field] = $filePath;
                }
            }

            // Update or create settings
            foreach ($validated as $key => $value) {
                if ($value !== null) {
                    $setting = FrontendSetting::where('key', $key)->first();
                    if ($setting) {
                        $setting->value = $value;
                        $setting->save();
                    } else {
                        FrontendSetting::create(['key' => $key, 'value' => $value]);
                    }
                }
            }

            // Update primary and secondary colors
            $primaryColor = $request->input('primary_color');
            $secondaryColor = $request->input('secondary_color');

            $this->setEnvValue('WEB_PRIMARY_COLOR', $primaryColor);
            $this->setEnvValue('WEB_SECONDARY_COLOR', $secondaryColor);

            // Có thể cần clear cache config để đảm bảo cập nhật có hiệu lực
            Artisan::call('config:clear');
            Artisan::call('cache:clear');



            return redirect()->back()->with('success', 'Settings updated successfully');
        } catch (\Exception $e) {
            Log::error('Settings update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }
    

    function setEnvValue($key, $value)
    {
        $envPath = base_path('.env');
        $value = trim(str_replace('"', '', $value)); // Xử lý giá trị chứa dấu nháy kép

        if (file_exists($envPath)) {
            if (strpos(file_get_contents($envPath), "$key=") !== false) {
                file_put_contents($envPath, preg_replace(
                    "/^$key=.*$/m",
                    "$key=\"$value\"",
                    file_get_contents($envPath)
                ));
            } else {
                file_put_contents($envPath, PHP_EOL . "$key=\"$value\"", FILE_APPEND);
            }
        }
    }
}
