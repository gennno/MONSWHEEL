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

        // Ambil semua service yang sudah selesai
        $services = DB::table('services')
            ->where('status', 'done')
            ->get();

        foreach ($services as $service) {
            DB::table('service_histories')->insert([
                // TRACE
                'service_id' => $service->id,

                // RELATION
                'unit_id'     => $service->unit_id,
                'created_by'  => $service->created_by,
                'handover_to' => $service->handover_to,

                // DATE & SHIFT
                'service_date' => $service->service_date,
                'shift'        => 2, // final shift

                // PERSON IN CHARGE
                'kapten' => $service->kapten,
                'gl'     => $service->gl,
                'qa1'    => $service->qa1,

                // NOTES & ACTIONS
                'note1'          => $service->note1,
                'washing'        => $service->washing,
                'note2'          => $service->note2,
                'action_service' => $service->action_service,
                'note3'          => $service->note3,

                // BACKLOG
                'bays'           => $service->bays,
                'action_backlog' => $service->action_backlog,
                'note4'          => $service->note4,

                // RFU & DOWNTIME
                'rfu'             => $service->rfu,
                'downtime_plan'   => $service->downtime_plan,
                'downtime_actual' => $service->downtime_actual,

                // FINAL NOTE
                'note5' => $service->note5,

                // FLOW TIMESTAMPS
                'handover_at'  => $service->handover_at,
                'completed_at' => $service->completed_at,

                // ARCHIVE META
                'archived_at' => $now,
                'archived_by' => 'system',

                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
