<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceHistorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Ambil beberapa unit
        $units = DB::table('units')->take(5)->get();

        foreach ($units as $index => $unit) {

            DB::table('service_histories')->insert([
                // Trace
                'service_id' => $index + 1, // simulasi id service lama

                // Relation
                'unit_id' => $unit->id,

                // Header
                'service_date' => now()->subDays($index + 1)->toDateString(),
                'kapten'       => 'KAPTEN H-' . ($index + 1),
                'gl'           => 'GL H-' . ($index + 1),
                'bays'         => 'BAY-' . ($index + 1),
                'backlog_item' => $index % 2 === 0
                                    ? 'Replace hydraulic hose'
                                    : null,

                // ===== TIME LOG =====
                'in_plan'               => '08:00:00',
                'in_actual'             => '08:10:00',

                'qa1_plan'              => '09:00:00',
                'qa1_actual'            => '09:05:00',

                'washing_plan'          => '10:00:00',
                'washing_actual'        => '10:25:00',

                'action_service_plan'   => '11:00:00',
                'action_service_actual' => '11:40:00',

                'action_backlog_plan'   => '13:00:00',
                'action_backlog_actual' => $index % 2 === 0
                                            ? '13:50:00'
                                            : null,

                'qa7_plan'              => '14:00:00',
                'qa7_actual'            => '14:15:00',

                // Downtime (minutes)
                'downtime_plan'   => 120,
                'downtime_actual' => $index % 2 === 0 ? 150 : 110,

                // Notes
                'note_in'             => 'Initial inspection completed',
                'note_qa1'            => 'QA1 passed',
                'note_washing'        => 'Truck washed',
                'note_action_service' => 'Routine maintenance',
                'note_action_backlog' => 'Backlog handled',
                'note_qa7'            => 'Final QA passed',
                'note_downtime'       => $index % 2 === 0
                                            ? 'Delay due to spare part'
                                            : null,

                // Final snapshot
                'remark'       => $index % 2 === 0 ? 'over' : 'ok',
                'status'       => 'done',
                'handover_at'  => $now->copy()->subHours(6),
                'completed_at' => $now->copy()->subHours(4),

                // Archive metadata
                'archived_at' => $now,
                'archived_by' => 'system',

                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
