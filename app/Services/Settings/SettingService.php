<?php

namespace App\Services\Settings;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SettingService
{
    private $disk = 'local';
    private $path = 'settings.json';

    /**
     * Get all settings as an array.
     */
    public function getSettings(): array
    {
        if (!Storage::disk($this->disk)->exists($this->path)) {
            return $this->getDefaultSettings();
        }

        $content = Storage::disk($this->disk)->get($this->path);
        return json_decode($content, true) ?: $this->getDefaultSettings();
    }

    /**
     * Update settings with new data.
     */
    public function updateSettings(array $data): void
    {
        $current = $this->getSettings();
        $merged = array_merge($current, $data);
        
        Storage::disk($this->disk)->put($this->path, json_encode($merged, JSON_PRETTY_PRINT));
    }

    /**
     * Handle logo upload and return the new path, deleting the old one if it exists.
     */
    public function uploadLogo(UploadedFile $file, ?string $oldLogo = null): string
    {
        // Delete old logo if exists and not default
        if ($oldLogo && Storage::disk('public')->exists(str_replace('storage/', '', $oldLogo))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $oldLogo));
        }

        // Store new logo
        $path = $file->store('logo', 'public');
        return 'storage/' . $path;
    }

    /**
     * Get default settings structure.
     */
    private function getDefaultSettings(): array
    {
        return [
            'app_name' => 'Stockify',
            'app_logo' => null, // null means use default SVG
        ];
    }
}
