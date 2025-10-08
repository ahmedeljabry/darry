<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants','tenant_type')) $table->string('tenant_type')->nullable()->after('full_name');
            if (!Schema::hasColumn('tenants','national_id_or_cr')) $table->string('national_id_or_cr')->nullable()->after('tenant_type');
            if (!Schema::hasColumn('tenants','work_or_study_place')) $table->string('work_or_study_place')->nullable()->after('national_id_or_cr');
            if (!Schema::hasColumn('tenants','phone2')) $table->string('phone2')->nullable()->after('phone');
            if (!Schema::hasColumn('tenants','address')) $table->string('address')->nullable()->after('phone2');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            foreach (['tenant_type','national_id_or_cr','work_or_study_place','phone2','address'] as $col) {
                if (Schema::hasColumn('tenants',$col)) $table->dropColumn($col);
            }
        });
    }
};

