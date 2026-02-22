<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Service;
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

        $totalUnit = Unit::count();


        $totalService = Service::count();


        $totalServiceDone = Service::where('status', 'process')->count();

        $rfuReady = Service::where('status', 'done')->count();



        $services = Service::with('unit')
            ->orderBy('created_at', 'asc')
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


        $totalUnit = Unit::where('status', 'active')->count();
        $totalService = Service::count();
        $totalServiceDone = Unit::where('status', 'service')->count();
        $rfuReady = Service::where('status', 'done')->count();


        $services = Service::with('unit')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('videotron', compact(
            'totalUnit',
            'totalService',
            'totalServiceDone',
            'rfuReady',
            'services'
        ));
    }
public function videotronPartial()
{
    $services = Service::with('unit')->latest()->get();

    $totalService = Service::count();
    $totalServiceDone = Service::where('status', 'process')->count();
    $rfuReady = Service::where('status', 'done')->count();

    return view('videotron._content', compact(
        'services',
        'totalService',
        'totalServiceDone',
        'rfuReady'
    ));
}


}
