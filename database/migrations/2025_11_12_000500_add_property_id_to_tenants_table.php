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
            if (!Schema::hasColumn('tenants', 'property_id')) {
                $table->foreignId('property_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'property_id')) {
                $table->dropConstrainedForeignId('property_id');
            }
        });
    }
};
