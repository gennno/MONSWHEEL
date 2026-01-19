<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_histories', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | ORIGINAL SERVICE ID (OPTIONAL TRACE)
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('service_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | RELATION
            |--------------------------------------------------------------------------
            */
            $table->foreignId('unit_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

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
            $table->unsignedTinyInteger('shift'); // final shift (biasanya 2)

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
            |--------------------------------------------------------------------------
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
            | FLOW TIMESTAMPS
            |--------------------------------------------------------------------------
            */
            $table->timestamp('handover_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | ARCHIVE METADATA
            |--------------------------------------------------------------------------
            */
            $table->timestamp('archived_at')->useCurrent();
            $table->string('archived_by')->default('system'); 
            // system / scheduler / admin username

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */
            $table->index(['service_date']);
            $table->index(['unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_histories');
    }
};
