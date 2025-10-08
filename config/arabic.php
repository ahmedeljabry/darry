<?php
declare(strict_types=1);

return [
    // Toggle conversion of Western digits 0-9 to Arabic-Indic digits ٠١٢٣٤٥٦٧٨٩
    'use_arabic_indic_digits' => env('AR_USE_ARABIC_INDIC', false),

    // Default locale for number/date formatting
    'locale' => env('AR_LOCALE', 'ar_EG'),

    // Week starts on: saturday|sunday|monday
    'week_starts_on' => env('AR_WEEK_STARTS_ON', 'saturday'),
];

