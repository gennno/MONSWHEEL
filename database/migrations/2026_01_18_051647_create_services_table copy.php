<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELATION
            |--------------------------------------------------------------------------
            */
            $table->foreignId('unit_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // User shift 1 (creator)
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // User shift 2 (handover)
            $table->foreignId('handover_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | DATE & SHIFT
            |--------------------------------------------------------------------------
            */
            $table->date('service_date');
            $table->unsignedTinyInteger('shift')->default(1); // 1 / 2

            /*
            |--------------------------------------------------------------------------
            | PERSON IN CHARGE
            |--------------------------------------------------------------------------
            */
            $table->string('kapten')->nullable();
            $table->string('gl')->nullable();
            $table->string('qa1')->nullable();

            /*
            |--------------------------------------------------------------------------
            | NOTES & ACTIONS
            |--------------------------------------------------------------------------
            */
            $table->text('note1')->nullable();
            $table->enum('washing', ['yes', 'no'])->nullable();
            $table->text('note2')->nullable();
            $table->text('action_service')->nullable();
            $table->text('note3')->nullable();

            /*
            |--------------------------------------------------------------------------
            | BACKLOG
            | --------------------------------------------------------------------------
            */
            $table->string('bays')->nullable();
            $table->text('action_backlog')->nullable();
            $table->text('note4')->nullable();

            /*
            |--------------------------------------------------------------------------
            | RFU & DOWNTIME
            |--------------------------------------------------------------------------
            */
            $table->enum('rfu', ['ready', 'not_ready'])->nullable();
            $table->dateTime('downtime_plan')->nullable();
            $table->dateTime('downtime_actual')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FINAL NOTE
            |--------------------------------------------------------------------------
            */
            $table->text('note5')->nullable();

            /*
            |--------------------------------------------------------------------------
            | SERVICE STATUS & FLOW
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'open',        // shift 1 aktif
                'handover',    // shift 1 selesai → tunggu shift 2
                'on_process',  // shift 2 aktif
                'done'         // end job
            ])->default('open');

            /*
            |--------------------------------------------------------------------------
            | FLOW TIMESTAMPS (PENTING UNTUK MONITORING)
            |--------------------------------------------------------------------------
            */
            $table->timestamp('handover_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX (PERFORMANCE MONITORING)
            |--------------------------------------------------------------------------
            */
            $table->index(['service_date', 'shift']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
