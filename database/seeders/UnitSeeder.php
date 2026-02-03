<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $units = [
            ['code' => 'DT7201', 'type' => 'Dump Truck'],
            ['code' => 'DT7202', 'type' => 'Dump Truck'],
            ['code' => 'DT7203', 'type' => 'Dump Truck'],
            ['code' => 'DT7100', 'type' => 'Dump Truck'],
            ['code' => 'DT7101', 'type' => 'Dump Truck'],
            ['code' => 'DT7301', 'type' => 'Dump Truck'],
            ['code' => 'DT7302', 'type' => 'Dump Truck'],
            ['code' => 'DT7303', 'type' => 'Dump Truck'],
            ['code' => 'DT7401', 'type' => 'Dump Truck'],
            ['code' => 'DT7402', 'type' => 'Dump Truck'],
        ];

        foreach ($units as $unit) {
            DB::table('units')->insert([
                'code'       => $unit['code'],
                'type'       => $unit['type'],
                'img'        => null,
                'status'     => 'active', // harus lowercase sesuai enum
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
