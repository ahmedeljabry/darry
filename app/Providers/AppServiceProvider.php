<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $appName = Setting::get('app_name', config('app.name'));

        $logoPath = Setting::get('logo');
        $faviconPath = Setting::get('favicon');

        $logoUrl = $logoPath ? Storage::disk('public')->url($logoPath) : asset('admin/assets/media/logos/logo-light.png');
        $faviconUrl = $faviconPath ? Storage::disk('public')->url($faviconPath) : asset('admin/assets/media/logos/favicon.ico');

        View::share('appBrand', [
            'name' => $appName,
            'logo_url' => $logoUrl,
            'favicon_url' => $faviconUrl,
        ]);
    }
}
