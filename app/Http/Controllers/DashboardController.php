<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Service;
use App\Models\ServiceHistory;

use App\Exports\DashboardServiceExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
        public function download()
    {
        return Excel::download(
            new DashboardServiceExport,
            'dashboard-services-' . now()->format('Y-m-d_H-i') . '.xlsx'
        );
    }
    
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | DASHBOARD CARDS
        |--------------------------------------------------------------------------
        */

        $totalUnit = Unit::where('status', 'Active')->count();

        $totalService = Service::count();

        $totalServiceDone = ServiceHistory::count();

        $rfuReady = Service::where('rfu', 'ready')->count();

        /*
        |--------------------------------------------------------------------------
        | TABLE DATA (SEMUA SERVICE)
        |--------------------------------------------------------------------------
        */

        $services = Service::with([
                'unit',
                'creator',
                'handoverUser'
            ])
            ->orderBy('created_at')
            ->get();

        return view('dashboard', compact(
            'totalUnit',
            'totalService',
            'totalServiceDone',
            'rfuReady',
            'services'
        ));
    }

    public function videotron()
    {
        /*
        |--------------------------------------------------------------------------
        | DASHBOARD CARDS
        |--------------------------------------------------------------------------
        */

        $totalUnit = Unit::where('status', 'Active')->count();

        $totalService = Service::count();

        $totalServiceDone = ServiceHistory::count();

        $rfuReady = Service::where('rfu', 'ready')->count();

        /*
        |--------------------------------------------------------------------------
        | TABLE DATA (SEMUA SERVICE)
        |--------------------------------------------------------------------------
        */

        $services = Service::with([
                'unit',
                'creator',
                'handoverUser'
            ])
            ->orderBy('created_at')
            ->get();

        return view('videotron', compact(
            'totalUnit',
            'totalService',
            'totalServiceDone',
            'rfuReady',
            'services'
        ));
    }
}
