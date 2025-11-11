<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            if (!Schema::hasColumn('owners', 'property_id')) {
                $table->foreignId('property_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            }
        });

        Schema::table('owners', function (Blueprint $table) {
            if (Schema::hasColumn('owners', 'password')) {
                $table->dropColumn('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            if (Schema::hasColumn('owners', 'property_id')) {
                $table->dropConstrainedForeignId('property_id');
            }
        });

        Schema::table('owners', function (Blueprint $table) {
            if (!Schema::hasColumn('owners', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
        });
    }
};
