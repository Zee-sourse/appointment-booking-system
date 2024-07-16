<?php

use App\Models\Employee;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid');

            $table->foreignIdFor(Employee::class)->constrained()->cascadeOnDelete();

            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();

            $table->datetime('starts_at');

            $table->datetime('ends_at');

            $table->timestamp('cancelled_at')->nullable();

            $table->string('name');

            $table->string('email')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
