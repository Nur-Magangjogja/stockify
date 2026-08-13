<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings\SettingService;

class SettingController extends Controller
{
    protected $settingService;
    protected $stockTransactionService;
    protected $activityLogService;

    public function __construct(
        SettingService $settingService, 
        \App\Services\StockTransaction\StockTransactionService $stockTransactionService,
        \App\Services\ActivityLog\ActivityLogService $activityLogService
    ) {
        $this->settingService = $settingService;
        $this->stockTransactionService = $stockTransactionService;
        $this->activityLogService = $activityLogService;
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

    /**
     * Reset transaction history.
     */
    public function resetTransactions(Request $request)
    {
        $request->validate([
            'delete_type' => 'required|in:date_range,exact_month',
            'start_date' => 'nullable|required_if:delete_type,date_range|date|before_or_equal:end_date',
            'end_date' => 'nullable|required_if:delete_type,date_range|date|before_or_equal:today',
            'specific_month' => 'nullable|required_if:delete_type,exact_month|date_format:Y-m',
        ]);

        try {
            $mode = $request->delete_type === 'date_range' ? 'range' : 'month';
            $value = $request->delete_type === 'date_range' ? [ $request->start_date, $request->end_date ] : $request->specific_month;
            
            $this->stockTransactionService->resetTransactionHistory($mode, $value);
            return redirect()->route('admin.settings.index')->with('success', 'Riwayat transaksi berhasil dibersihkan dan diringkas ke Saldo Awal!');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')->with('error', 'Gagal mereset transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Reset activity history.
     */
    public function resetActivities(Request $request)
    {
        $request->validate([
            'activity_delete_type' => 'required|in:date_range,exact_month',
            'activity_start_date' => 'nullable|required_if:activity_delete_type,date_range|date|before_or_equal:activity_end_date',
            'activity_end_date' => 'nullable|required_if:activity_delete_type,date_range|date|before_or_equal:today',
            'activity_specific_month' => 'nullable|required_if:activity_delete_type,exact_month|date_format:Y-m',
        ]);

        try {
            $mode = $request->activity_delete_type === 'date_range' ? 'range' : 'month';
            $value = $request->activity_delete_type === 'date_range' ? [ $request->activity_start_date, $request->activity_end_date ] : $request->activity_specific_month;
            
            $this->activityLogService->deleteActivityLogs($mode, $value);
            return redirect()->route('admin.settings.index')->with('success', 'Riwayat aktivitas pengguna berhasil dibersihkan!');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')->with('error', 'Gagal mereset aktivitas: ' . $e->getMessage());
        }
    }
}
