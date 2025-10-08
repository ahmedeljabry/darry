import './bootstrap';
import $ from 'jquery';
import 'select2';
import flatpickr from 'flatpickr';
import { Arabic as FlatpickrArabic } from 'flatpickr/dist/l10n/ar.js';
import ApexCharts from 'apexcharts';
import 'datatables.net';
import 'datatables.net-bs5';

window.$ = $;
window.jQuery = $;
window.flatpickr = flatpickr;
window.ApexCharts = ApexCharts;

flatpickr.localize(FlatpickrArabic);

// Default Arabic Datatables language
export const dtArabic = {
    sEmptyTable: 'لا توجد بيانات متاحة في الجدول',
    sLoadingRecords: 'جارٍ التحميل...',
    sProcessing: 'جارٍ المعالجة...',
    sLengthMenu: 'أظهر _MENU_ مدخلات',
    sZeroRecords: 'لم يتم العثور على سجلات',
    sInfo: 'إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل',
    sInfoEmpty: 'يعرض 0 إلى 0 من أصل 0 سجل',
    sInfoFiltered: '(منتقاة من مجموع _MAX_ مُدخل)',
    sSearch: 'بحث:',
    oPaginate: { sFirst: 'الأول', sPrevious: 'السابق', sNext: 'التالي', sLast: 'الأخير' },
};

export function initSelect2(el, placeholder = 'اختر...') {
    $(el).select2({ dir: 'rtl', width: '100%', placeholder });
}

export function initDatepicker(el, opts = {}) {
    return flatpickr(el, { locale: 'ar', dateFormat: 'Y-m-d', ...opts });
}

export function initDataTable(el, options = {}) {
    return $(el).DataTable({
        language: dtArabic,
        ...options,
    });
}

export function defaultApexOptions() {
    return {
        chart: { locales: [{ name: 'ar', options: { months: ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'], shortMonths: ['ينا','فبر','مار','أبر','ماي','يون','يول','أغس','سبت','أكت','نوف','ديس'], days: ['السبت','الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة'], shortDays: ['سبت','أحد','اثن','ثلا','أرب','خمس','جمع'], toolbar: { exportToSVG: 'تصدير SVG', exportToPNG: 'تصدير PNG', exportToCSV: 'تصدير CSV' } } }], defaultLocale: 'ar' },
        yaxis: { labels: { formatter: (val) => new Intl.NumberFormat('ar-EG').format(val) } },
        xaxis: { labels: { style: { fontFamily: 'Tajawal' } } },
        legend: { fontFamily: 'Tajawal' },
    };
}

