<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
            'timezone' => config('app.timezone'),
            'logo' => '',
            'favicon' => '',
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

        $logoPath = $values['logo'] ?? '';
        $values['logo_url'] = $logoPath ? Storage::disk('public')->url($logoPath) : null;

        $faviconPath = $values['favicon'] ?? '';
        $values['favicon_url'] = $faviconPath ? Storage::disk('public')->url($faviconPath) : null;

        $mailConfigured = filled($values['mail_host']) && filled($values['mail_username']) && filled($values['mail_from_address']);
        $brandingConfigured = filled($values['logo']);

        $generalSummary = [
            'app_name' => $values['app_name'],
            'company_email' => $values['company_email'],
            'company_phone' => $values['company_phone'],
            'address' => $values['address'],
        ];

        return view('admin.settings.index', [
            'values' => $values,
            'localeOptions' => $this->localeOptions(),
            'currencyOptions' => $this->currencyOptions(),
            'timezoneOptions' => $this->timezoneOptions(),
            'mailDriverOptions' => $this->mailDriverOptions(),
            'mailEncryptionOptions' => $this->mailEncryptionOptions(),
            'mailConfigured' => $mailConfigured,
            'brandingConfigured' => $brandingConfigured,
            'generalSummary' => $generalSummary,
            'smtpTestRoute' => Route::has('admin.settings.test-smtp') ? route('admin.settings.test-smtp') : null,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $localeKeys = array_keys($this->localeOptions());
        $currencyKeys = array_keys($this->currencyOptions());
        $timezoneKeys = array_keys($this->timezoneOptions());
        $driverKeys = array_keys($this->mailDriverOptions());
        $encryptionKeys = array_keys($this->mailEncryptionOptions());

        $data = $request->validate([
            'app_name' => ['required','string','max:255'],
            'company_email' => ['nullable','email','max:255'],
            'company_phone' => ['nullable','string','max:190'],
            'address' => ['nullable','string','max:500'],
            'locale' => ['nullable','string','max:10', Rule::in($localeKeys)],
            'currency' => ['nullable','string','max:10', Rule::in($currencyKeys)],
            'timezone' => ['nullable','string','max:64', Rule::in($timezoneKeys)],
            'logo' => ['nullable','image'],
            'favicon' => ['nullable','image'],
            // Email/Mailer
            'mail_from_address' => ['nullable','email','max:255'],
            'mail_driver' => ['nullable','string','max:50', Rule::in($driverKeys)],
            'mail_host' => ['nullable','string','max:190'],
            'mail_username' => ['nullable','string','max:190'],
            'mail_password' => ['nullable','string','max:190'],
            'mail_encryption' => ['nullable','string','max:20', Rule::in($encryptionKeys)],
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

        if (array_key_exists('currency', $data) && $data['currency']) {
            $data['currency'] = strtoupper($data['currency']);
        }
        if (array_key_exists('locale', $data) && $data['locale']) {
            $data['locale'] = strtolower($data['locale']);
        }
        foreach ($data as $k => $v) {
            Setting::set($k, $v);
        }
        return back()->with('status', __('messages.success_updated'));
    }

    private function localeOptions(): array
    {
        return [
            'ar' => 'العربية',
            'en' => 'English',
        ];
    }

    private function currencyOptions(): array
    {
        return [
            'SAR' => 'ريال سعودي (SAR)',
            'AED' => 'درهم إماراتي (AED)',
            'KWD' => 'دينار كويتي (KWD)',
            'BHD' => 'دينار بحريني (BHD)',
            'QAR' => 'ريال قطري (QAR)',
            'OMR' => 'ريال عُماني (OMR)',
            'EGP' => 'جنيه مصري (EGP)',
            'USD' => 'دولار أمريكي (USD)',
            'EUR' => 'يورو (EUR)',
        ];
    }

    private function timezoneOptions(): array
    {
        return [
            'Asia/Riyadh' => 'Asia/Riyadh',
            'Asia/Dubai' => 'Asia/Dubai',
            'Asia/Kuwait' => 'Asia/Kuwait',
            'Asia/Qatar' => 'Asia/Qatar',
            'Asia/Muscat' => 'Asia/Muscat',
            'Africa/Cairo' => 'Africa/Cairo',
            'Europe/London' => 'Europe/London',
            'UTC' => 'UTC',
        ];
    }

    private function mailDriverOptions(): array
    {
        return [
            'smtp' => 'SMTP',
            'log' => 'Log',
            'sendmail' => 'Sendmail',
        ];
    }

    private function mailEncryptionOptions(): array
    {
        return [
            '' => 'None',
            'tls' => 'TLS',
            'ssl' => 'SSL',
        ];
    }
}
