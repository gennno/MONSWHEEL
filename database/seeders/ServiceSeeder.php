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

        // Ambil user sesuai seeder kamu
        $operator = DB::table('users')->where('username', 'operator1')->first();
        $office   = DB::table('users')->where('username', 'office1')->first();

        // Ambil 10 unit
        $units = DB::table('units')->take(10)->get();

        foreach ($units as $index => $unit) {

            // Tentukan status service
            if ($index < 5) {
                $status = 'open';
                $shift  = 1;
            } elseif ($index < 8) {
                $status = 'handover';
                $shift  = 1;
            } else {
                $status = 'done';
                $shift  = 2;
            }

            $serviceId = DB::table('services')->insertGetId([
                'unit_id'        => $unit->id,
                'created_by'     => $operator->id,
                'handover_to'    => $status !== 'open' ? $office->id : null,

                'service_date'   => now()->toDateString(),
                'shift'          => $shift,

                'kapten'         => 'KAPTEN ' . ($index + 1),
                'gl'             => 'GL ' . ($index + 1),
                'qa1'            => 'QA ' . ($index + 1),

                'note1'          => 'Initial inspection',
                'washing'        => $index % 2 === 0 ? 'yes' : 'no',
                'note2'          => 'Washing note',
                'action_service' => 'Routine service',
                'note3'          => 'Service in progress',

                'bays'           => 'BAY-' . ($index + 1),
                'action_backlog' => $index % 3 === 0 ? 'Replace brake pad' : null,
                'note4'          => 'Backlog checked',

                'rfu'            => $status === 'done' ? 'ready' : 'not_ready',
                'downtime_plan'  => $now->copy()->addHours(2),
                'downtime_actual'=> $status === 'done'
                                    ? $now->copy()->addHours(3)
                                    : null,

                'note5'          => 'Final note',

                'status'         => $status,
                'handover_at'    => $status !== 'open'
                                    ? $now->copy()->addHours(4)
                                    : null,
                'completed_at'   => $status === 'done'
                                    ? $now->copy()->addHours(6)
                                    : null,

                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            // Sinkron ke table units
            DB::table('units')
                ->where('id', $unit->id)
                ->update([
                    'current_shift'     => $shift,
                    'service_status'    => match ($status) {
                        'open'     => 'on_service',
                        'handover' => 'handover',
                        'done'     => 'finished',
                        default    => 'idle',
                    },
                    'active_service_id' => $status !== 'done' ? $serviceId : null,
                    'updated_at'        => $now,
                ]);
        }
    }
}
