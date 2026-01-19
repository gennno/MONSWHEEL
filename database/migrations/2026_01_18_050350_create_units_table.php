<?php

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
Schema::create('units', function (Blueprint $table) {
    $table->id();

    // Identity
    $table->string('code')->unique();
    $table->string('type')->nullable();

    // Operational state
    $table->unsignedTinyInteger('current_shift')->nullable();
    $table->enum('service_status', [
        'idle',
        'on_service',
        'handover',
        'finished'
    ])->default('idle');

    // FK DIISI NANTI
    $table->unsignedBigInteger('active_service_id')->nullable();

    $table->enum('status', ['Active', 'Inactive'])->default('Active');

    $table->timestamps();
});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
