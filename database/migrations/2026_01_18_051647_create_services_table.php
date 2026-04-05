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

            // Relations
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();

            // Header
            $table->date('service_date');
            $table->string('gl')->nullable();
            $table->string('kapten')->nullable();
            $table->string('bays')->nullable();
            $table->string('backlog_item')->nullable();

            // ===== TIME LOG (PLAN vs ACTUAL) =====
            $table->datetime('in_plan')->nullable();
            $table->datetime('in_actual')->nullable();

            $table->datetime('qa1_plan')->nullable();
            $table->datetime('qa1_actual')->nullable();

            $table->datetime('washing_plan')->nullable();
            $table->datetime('washing_actual')->nullable();
            
            $table->unsignedSmallInteger('washing_remark')->nullable();

            $table->datetime('action_service_plan')->nullable();
            $table->datetime('action_service_actual')->nullable();

            $table->datetime('action_backlog_plan')->nullable();
            $table->datetime('action_backlog_actual')->nullable();

            $table->datetime('qa7_plan')->nullable();
            $table->datetime('qa7_actual')->nullable();

            // Downtime (duration-based)
            $table->unsignedSmallInteger('downtime_plan')->nullable();   // minutes or hours
            $table->unsignedSmallInteger('downtime_actual')->nullable();

            $table->unsignedSmallInteger('downtime_countdown')->nullable();

            // Notes
            $table->text('note_in')->nullable();
            $table->text('note_qa1')->nullable();
            $table->text('note_washing')->nullable();
            $table->text('note_action_service')->nullable();
            $table->text('note_action_backlog')->nullable();
            $table->text('note_qa7')->nullable();
            $table->text('note_downtime')->nullable();

            // Status
            $table->enum('remark', ['ok', 'over'])->nullable();

            $table->enum('status', ['plan', 'process', 'continue', 'done'])->default('plan');
            $table->timestamp('handover_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
