<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (!Schema::hasColumn('units', 'property_id')) {
                $table->unsignedBigInteger('property_id')->nullable()->after('parent_id');
                $table->foreign('property_id')->references('id')->on('properties')->nullOnDelete();
            }
            if (!Schema::hasColumn('units', 'rooms')) $table->unsignedInteger('rooms')->nullable()->after('name');
            if (!Schema::hasColumn('units', 'toilets')) $table->unsignedInteger('toilets')->nullable()->after('rooms');
            if (!Schema::hasColumn('units', 'category')) $table->string('category')->nullable()->after('unit_type');
            if (!Schema::hasColumn('units', 'electricity_acc')) $table->string('electricity_acc')->nullable()->after('rent_amount');
            if (!Schema::hasColumn('units', 'water_acc')) $table->string('water_acc')->nullable()->after('electricity_acc');
            if (!Schema::hasColumn('units', 'occupancy_status')) $table->string('occupancy_status')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units','property_id')) { $table->dropForeign(['property_id']); $table->dropColumn('property_id'); }
            foreach (['rooms','toilets','category','electricity_acc','water_acc','occupancy_status'] as $col) {
                if (Schema::hasColumn('units',$col)) $table->dropColumn($col);
            }
        });
    }
};
