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
            |----------------------------------------------------------------------
            | TRACE TO ORIGINAL SERVICE
            |----------------------------------------------------------------------
            */
            $table->unsignedBigInteger('service_id')->nullable();

            /*
            |----------------------------------------------------------------------
            | RELATION
            |----------------------------------------------------------------------
            */
            $table->foreignId('unit_id')
                  ->constrained()
                  ->cascadeOnDelete();

            /*
            |----------------------------------------------------------------------
            | HEADER
            |----------------------------------------------------------------------
            */
            $table->date('service_date');
            $table->string('gl')->nullable();
            $table->string('kapten')->nullable();
            $table->string('bays')->nullable();
            $table->string('backlog_item')->nullable();

            /*
            |----------------------------------------------------------------------
            | TIME LOG (PLAN vs ACTUAL)
            |----------------------------------------------------------------------
            */
            $table->time('in_plan')->nullable();
            $table->time('in_actual')->nullable();

            $table->time('qa1_plan')->nullable();
            $table->time('qa1_actual')->nullable();

            $table->time('washing_plan')->nullable();
            $table->time('washing_actual')->nullable();

            $table->time('action_service_plan')->nullable();
            $table->time('action_service_actual')->nullable();

            $table->time('action_backlog_plan')->nullable();
            $table->time('action_backlog_actual')->nullable();

            $table->time('qa7_plan')->nullable();
            $table->time('qa7_actual')->nullable();

            /*
            |----------------------------------------------------------------------
            | DOWNTIME (DURATION)
            |----------------------------------------------------------------------
            */
            $table->unsignedSmallInteger('downtime_plan')->nullable();
            $table->unsignedSmallInteger('downtime_actual')->nullable();

            /*
            |----------------------------------------------------------------------
            | NOTES
            |----------------------------------------------------------------------
            */
            $table->text('note_in')->nullable();
            $table->text('note_qa1')->nullable();
            $table->text('note_washing')->nullable();
            $table->text('note_action_service')->nullable();
            $table->text('note_action_backlog')->nullable();
            $table->text('note_qa7')->nullable();
            $table->text('note_downtime')->nullable();

            /*
            |----------------------------------------------------------------------
            | FINAL STATUS SNAPSHOT
            |----------------------------------------------------------------------
            */
            $table->enum('remark', ['ok', 'over'])->nullable();
            $table->enum('status', ['plan', 'process', 'continue', 'done']);

            $table->timestamp('handover_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            /*
            |----------------------------------------------------------------------
            | ARCHIVE METADATA
            |----------------------------------------------------------------------
            */
            $table->timestamp('archived_at')->useCurrent();
            $table->string('archived_by')->default('system');

            $table->timestamps();

            /*
            |----------------------------------------------------------------------
            | INDEX
            |----------------------------------------------------------------------
            */
            $table->index(['service_date']);
            $table->index(['unit_id']);
            $table->index(['service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_histories');
    }
};
