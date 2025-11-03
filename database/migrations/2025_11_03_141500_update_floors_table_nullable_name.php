<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('floors', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->change();
            $table->unsignedInteger('sort_order')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('floors', function (Blueprint $table) {
            $table->string('name_ar')->nullable(false)->change();
            $table->unsignedInteger('sort_order')->default(0)->change();
        });
    }
};

