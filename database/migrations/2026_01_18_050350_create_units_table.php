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
    $table->string('img')->nullable();
    $table->enum('status', ['active', 'service', 'inactive'])->default('Active');

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
