# نظام إدارة الإيجارات (Laravel 11 + Metronic RTL)

تطبيق صغير وجاهز للإنتاج لإدارة الوحدات/المستأجرين/العقود/الفواتير/المدفوعات مع واجهة عربية (RTL) باستخدام Laravel 11 ودمج Metronic v7.2.9 (HTML).

- واجهة عربية بالكامل (بدون أي نص إنجليزي)، RTL افتراضيًا.
- API v1 باستخدام Sanctum (login/logout/me + CRUD للوحدات/المستأجرين/العقود/الفواتير/المدفوعات + إجراءات إضافية).
- شاشات لوحة التحكم + جداول AJAX (Datatables) + Select2 + Flatpickr (عربي) + ApexCharts.
- قواعد الأعمال: منع تداخل العقود + سعة الغرف/الأسرة + حجب الشقة للعقود المتداخلة مع غرف/أسِرّة.

## المتطلبات
- PHP 8.3+
- Composer
- Node.js 18+ & npm
- Docker (اختياري عبر Sail)

## التحضير السريع
1) ضع ملفات Metronic (HTML) بدون الأصول المرخصة داخل المسار التالي:
   - `resources/metronic/theme/assets`
   - يجب أن يحتوي على `plugins/global/plugins.bundle.rtl.css` و `css/style.bundle.rtl.css` و الميديا/الخطوط.

2) تثبيت الحزم وبناء الواجهة:
```
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev
```

3) قاعدة البيانات (PostgreSQL مفضّلة) عبر Sail:
```
docker compose up -d
php artisan migrate --force
php artisan db:seed --force
```

بيانات دخول افتراضية (API فقط):
- البريد: admin@example.com
- كلمة المرور: password

قم بتسجيل الدخول من صفحة `/login`، يتم تخزين التوكن في LocalStorage وتُستَخدم لاستدعاء API.

## الإعدادات العربية
- الإعدادات الافتراضية: `config/app.php` لغة `ar`، منطقة زمنية `Africa/Cairo`.
- الأرقام: `config/arabic.php` يمكن تفعيل الأرقام الهندية العربية عبر `AR_USE_ARABIC_INDIC=true`.
- تم تفعيل Carbon بالعربية وتوجيه RTL في `SetArabicLocale`.

## بنية التطبيق
- Enums تحت `app/Domain/Enums`.
- الوحدات/المستأجرون/العقود/الفواتير/المدفوعات تحت `app/Modules/*` (Models, Actions, Http/*, Resources).
- قواعد الأعمال داخل Actions + قاعدة منع التداخل `App\\Support\\Rules\\NoOverlapLeaseRule`.
- مفاتيح UUID + SoftDeletes + ترحيلات وفهارس مساعدة.
  - في PostgreSQL: يُنشأ قيد EXCLUDE على `leases` لمنع التداخل (مع تفعيل `btree_gist`).

## الواجهة (Metronic RTL)
- Vite يحمّل `resources/sass/metronic.scss` و `resources/js/metronic.js`.
- `vite-plugin-static-copy` ينسخ الخطوط/الميديا إلى `public/assets`.
- القوالب: `resources/views/layouts/metronic.blade.php` + أجزاء الرأس/القائمة/التذييل.
- مكوّنات قابلة لإعادة الاستخدام تحت `resources/views/components/ui/`.
- شاشات عربية: لوحة التحكم + وحدات + مستأجرون + عقود + فواتير + مدفوعات.

## API (v1)
- Auth: `/api/v1/auth/login`, `/auth/logout`, `/auth/me`, `/auth/forgot-password`, `/auth/reset-password`.
- وحدات/مستأجرون/عقود/فواتير/مدفوعات: CRUD + `/leases/{id}/renew`, `/leases/{id}/terminate`, `/leases/{lease}/issue`.
- ترقيم مؤشّر (cursor) + فلاتر بسيطة.

## الاختبارات والجودة
- Pest + Larastan (phpstan.neon.dist) + Pint + Rector.
- GitHub Actions: تثبيت/تحليل/اختبارات.

## ملاحظات
- تم إبقاء الواجهة تعتمد على API عبر توكن Sanctum (بدلًا من جلسات)، لذا يتم فرض صلاحية الوصول للبيانات عبر API. صفحات Blade تتحقق من التوكن على جانب المتصفح.
- تأكد من PHP 8.3+ لتوافق المتطلبات.
