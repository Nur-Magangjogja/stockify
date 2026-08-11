<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings\SettingService;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display the settings form.
     */
    public function index()
    {
        $settings = $this->settingService->getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:50',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // max 2MB
        ]);

        $settings = $this->settingService->getSettings();
        $updateData = ['app_name' => $request->app_name];

        if ($request->hasFile('app_logo')) {
            $newLogoPath = $this->settingService->uploadLogo(
                $request->file('app_logo'), 
                $settings['app_logo'] ?? null
            );
            $updateData['app_logo'] = $newLogoPath;
        }

        $this->settingService->updateSettings($updateData);

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan aplikasi berhasil diperbarui!');
    }
}
