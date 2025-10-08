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
            if (!Schema::hasColumn('owners','owner_type')) {
                $table->string('owner_type')->nullable()->after('address');
            }
            if (!Schema::hasColumn('owners','password')) {
                $table->string('password')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            if (Schema::hasColumn('owners','owner_type')) {
                $table->dropColumn('owner_type');
            }
            if (Schema::hasColumn('owners','password')) {
                $table->dropColumn('password');
            }
        });
    }
};

