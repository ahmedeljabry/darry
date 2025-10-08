<!DOCTYPE html>
<html direction="rtl" dir="rtl" style="direction: rtl" >
  <!--begin::Head-->
  <head>
    <base href="/">
    <meta charset="utf-8" />
    <title>تسجيل الدخول | {{ $appBrand['name'] ?? config('app.name') }}</title>
    <meta name="description" content="تسجيل الدخول للنظام" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap">

    <!--begin::Page Custom Styles (Login-4 RTL)-->
    <link href="{{ asset('admin/assets/css/pages/login/login-4.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles-->

    <!--begin::Global Theme Styles (used by all pages)-->
    <link href="{{ asset('admin/assets/plugins/global/plugins.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/plugins/custom/prismjs/prismjs.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/style.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->

    <!--begin::Layout Themes (used by all pages)-->
    <link href="{{ asset('admin/assets/css/themes/layout/header/base/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/themes/layout/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/themes/layout/brand/dark.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/themes/layout/aside/dark.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Layout Themes-->

    <link rel="shortcut icon" href="{{ $appBrand['favicon_url'] ?? asset('admin/assets/media/logos/favicon.ico') }}" />

    <style>
      body { font-family: 'Tajawal', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji'; }
    </style>
  </head>
  <!--end::Head-->

  <!--begin::Body-->
  <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
      <!--begin::Login-->
      <div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Content-->
        <div class="login-container order-2 order-lg-1 d-flex flex-center flex-row-fluid px-7 pt-lg-0 pb-lg-0 pt-4 pb-6 bg-white">
          <!--begin::Wrapper-->
          <div class="login-content d-flex flex-column pt-lg-0 pt-12" style="width:100%; max-width: 460px;">
            <!--begin::Logo-->
            <a href="{{ url('/') }}" class="login-logo pb-xl-20 pb-15 d-block text-center">
              <img src="{{ $appBrand['logo_url'] ?? asset('admin/assets/media/logos/logo-4.png') }}" class="max-h-70px" alt="{{ $appBrand['name'] ?? 'الشعار' }}" />
            </a>
            <!--end::Logo-->

            <!--begin::Signin-->
            <div class="login-form">
              <!--begin::Form-->
              <form class="form" id="kt_login_singin_form" method="POST" action="{{ route('admin.login.attempt') }}">
                @csrf

                <!--begin::Form group: Email-->
                <div class="form-group">
                    <div class="d-flex justify-content-between mt-n5">
                    <label class="font-size-h6 font-weight-bolder text-dark pt-5">البريد الإلكتروني</label>
                  </div>
                  <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0"
                         type="email" name="email" value="{{ old('email') }}" autocomplete="email" required />
                  @error('email')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
                <!--end::Form group-->

                <!--begin::Form group: Password-->
                <div class="form-group">
                  <div class="d-flex justify-content-between mt-n5">
                    <label class="font-size-h6 font-weight-bolder text-dark pt-5">كلمة المرور</label>
                  </div>
                  <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0"
                         type="password" name="password" autocomplete="current-password" required />
                  @error('password')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
                <!--end::Form group-->

                <!--begin::Action-->
                <div class="pb-5">
                  <button type="submit" id="kt_login_singin_form_submit_button" class="btn btn-primary font-weight-bolder font-size-h6 py-4 btn-block">
                    دخول
                  </button>
                </div>
                <!--end::Action-->
              </form>
              <!--end::Form-->
            </div>
            <!--end::Signin-->
          </div>
          <!--end::Wrapper-->
        </div>
        <!--end::Content-->
      </div>
      <!--end::Login-->
    </div>
    <!--end::Main-->

    <!--begin::Global Config (optional preview var)-->
    <script>window.HOST_URL = "{{ url('/') }}";</script>
    <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
    <!--end::Global Config-->

    <!--begin::Global Theme Bundle (used by all pages)-->
    <script src="{{ asset('admin/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ asset('admin/assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Theme Bundle-->

    <!--begin::Page Scripts (used by this page)-->
    <script src="{{ asset('admin/assets/js/pages/custom/login/login-4.js') }}"></script>
    <!--end::Page Scripts-->
  </body>
  <!--end::Body-->
</html>
