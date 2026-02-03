<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Ambil 10 unit
        $units = DB::table('units')->take(10)->get();

        foreach ($units as $index => $unit) {

            /**
             * Tentukan status service
             * 0–3   : plan
             * 4–6   : process
             * 7–8   : continue
             * 9     : done
             */
            $status = match (true) {
                $index < 4 => 'plan',
                $index < 7 => 'process',
                $index < 9 => 'continue',
                default    => 'done',
            };

            DB::table('services')->insert([
                'unit_id'      => $unit->id,

                // Header
                'service_date' => now()->toDateString(),
                'kapten'       => 'KAPTEN ' . ($index + 1),
                'gl'           => 'GL ' . ($index + 1),
                'bays'         => 'BAY-' . ($index + 1),
                'backlog_item' => $index % 3 === 0
                                    ? 'Replace brake pad'
                                    : null,

                // ===== TIME LOG =====
                'in_plan'                => '08:00:00',
                'in_actual'              => $status !== 'plan' ? '08:10:00' : null,

                'qa1_plan'               => '09:00:00',
                'qa1_actual'             => in_array($status, ['process','continue','done'])
                                            ? '09:15:00'
                                            : null,

                'washing_plan'           => '10:00:00',
                'washing_actual'         => $status === 'done' ? '10:20:00' : null,

                'action_service_plan'    => '11:00:00',
                'action_service_actual'  => in_array($status, ['continue','done'])
                                            ? '11:30:00'
                                            : null,

                'action_backlog_plan'    => '13:00:00',
                'action_backlog_actual'  => $status === 'done' ? '13:40:00' : null,

                'qa7_plan'               => '14:00:00',
                'qa7_actual'             => $status === 'done' ? '14:10:00' : null,

                // Downtime (minutes)
                'downtime_plan'   => 120,
                'downtime_actual' => $status === 'done' ? 150 : null,

                // Notes
                'note_in'              => 'Initial inspection',
                'note_qa1'             => 'QA1 checked',
                'note_washing'         => 'Washing completed',
                'note_action_service'  => 'Routine service',
                'note_action_backlog'  => 'Backlog checked',
                'note_qa7'             => 'Final QA',
                'note_downtime'        => $status === 'done'
                                            ? 'Extra downtime due to part replacement'
                                            : null,

                // Status
                'remark'        => $status === 'done' && $index % 2 === 0 ? 'over' : 'ok',
                'status'        => $status,
                'handover_at'   => in_array($status, ['continue','done'])
                                    ? $now->copy()->addHours(4)
                                    : null,
                'completed_at'  => $status === 'done'
                                    ? $now->copy()->addHours(6)
                                    : null,

                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }
}
