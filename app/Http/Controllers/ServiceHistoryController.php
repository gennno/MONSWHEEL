<?php

namespace App\Http\Controllers;

use App\Models\ServiceHistory;
use Illuminate\Http\Request;

class ServiceHistoryController extends Controller
{
    /**
     * Display history list
     */
    public function index()
    {
        $histories = ServiceHistory::with('unit')
            ->latest('service_date')
            ->get();

        return view('history', compact('histories'));
    }

    /**
     * Delete history
     */
    public function destroy(ServiceHistory $history)
    {
        $history->delete();

        return redirect()
            ->route('history.index')
            ->with('success', 'History deleted successfully.');
    }

    /**
     * Export (simple CSV)
     */
    public function export()
    {
        $histories = ServiceHistory::with('unit')->get();

        $filename = "service-history.csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($histories) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Date',
                'Unit',
                'GL',
                'Kapten',
                'Status',
                'Remark'
            ]);

            foreach ($histories as $h) {
                fputcsv($file, [
                    $h->service_date,
                    $h->unit->code ?? '-',
                    $h->gl,
                    $h->kapten,
                    $h->status,
                    $h->remark,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
