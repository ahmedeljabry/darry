<?php

declare(strict_types=1);

use App\Domain\Enums\RentType;
use App\Domain\Enums\UnitStatus;
use App\Domain\Enums\UnitType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('name');
            $table->string('unit_type');
            $table->unsignedInteger('capacity')->nullable();
            $table->string('rent_type');
            $table->decimal('rent_amount', 12, 2);
            $table->string('status')->default(UnitStatus::ACTIVE->value);

            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('units')->nullOnDelete();
            $table->index(['unit_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
