<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(): View
    {
        $defaults = [
            'app_name' => config('app.name'),
            'company_email' => '',
            'company_phone' => '',
            'address' => '',
            'locale' => config('app.locale'),
            'currency' => 'SAR',
            'logo' => '',
            'favicon' => '',
            // Email/Mailer defaults
            'mail_from_address' => config('mail.from.address'),
            'mail_driver' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_password' => '',
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_port' => (string) (config('mail.mailers.smtp.port') ?? ''),
        ];
        $values = [];
        foreach (array_keys($defaults) as $k) {
            $values[$k] = Setting::get($k, $defaults[$k]);
        }
        return view('admin.settings.index', compact('values'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'app_name' => ['required','string','max:255'],
            'company_email' => ['nullable','email','max:255'],
            'company_phone' => ['nullable','string','max:190'],
            'address' => ['nullable','string','max:500'],
            'locale' => ['nullable','string','max:10'],
            'currency' => ['nullable','string','max:10'],
            'logo' => ['nullable','image'],
            'favicon' => ['nullable','image'],
            // Email/Mailer
            'mail_from_address' => ['nullable','email','max:255'],
            'mail_driver' => ['nullable','string','max:50'],
            'mail_host' => ['nullable','string','max:190'],
            'mail_username' => ['nullable','string','max:190'],
            'mail_password' => ['nullable','string','max:190'],
            'mail_encryption' => ['nullable','string','max:20'],
            'mail_port' => ['nullable','numeric'],
        ]);

        if ($request->hasFile('logo')) {
            $old = (string) Setting::get('logo', '');
            $new = $request->file('logo')->store('settings','public');
            $data['logo'] = $new;
            if ($old) { Storage::disk('public')->delete($old); }
        }
        if ($request->hasFile('favicon')) {
            $old = (string) Setting::get('favicon', '');
            $new = $request->file('favicon')->store('settings','public');
            $data['favicon'] = $new;
            if ($old) { Storage::disk('public')->delete($old); }
        }
        foreach ($data as $k => $v) {
            Setting::set($k, $v);
        }
        return back()->with('status', __('messages.success_updated'));
    }
}
