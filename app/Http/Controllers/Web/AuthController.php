<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse|RedirectResponse
    {
        $expectsJson = $request->expectsJson();

        $credentials = $request->only('email', 'password');
        $remember = (bool) $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return $expectsJson
                ? response()->json(['status' => 'error', 'message' => __('auth.failed')], 422)
                : back()->withErrors(['email' => __('auth.failed')])->withInput();
        }

        $request->session()->regenerate();

        return $expectsJson
            ? response()->json(['status' => 'success', 'redirect' => route('admin.dashboard')])
            : redirect()->intended('/admin');
    }

    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        $expectsJson = $request->expectsJson();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $expectsJson
            ? response()->json(['status' => 'success', 'redirect' => route('admin.login')])
            : redirect()->route('admin.login');
    }
}
