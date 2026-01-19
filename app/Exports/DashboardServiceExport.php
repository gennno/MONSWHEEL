<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DashboardServiceExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Service::with('unit')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'CN',
            'Kapten',
            'GL',
            'Unit Masuk',
            'QA 1',
            'Washing',
            'Action Service',
            'Action Backlog',
            'QA 7',
            'RFU',
            'DT Plan',
            'DT Actual',
            'Remark',
        ];
    }

    public function map($service): array
    {
        return [
            $service->unit->code ?? '-',
            $service->kapten ?? '-',
            $service->gl ?? '-',
            $service->unit->code ?? '-',
            $service->qa1 ?? '-',
            strtoupper($service->washing ?? 'no'),
            $service->action_service ?? '-',
            $service->action_backlog ?? '-',
            $service->qa7 ?? '-',
            strtoupper($service->rfu ?? 'process'),
            optional($service->downtime_plan)->diffForHumans(null, true),
            optional($service->downtime_actual)->diffForHumans(null, true),
            $service->note5 ?? '-',
        ];
    }
}
