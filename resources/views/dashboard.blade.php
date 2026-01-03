@extends('layouts.app')

@section('title', 'Dashboard - MONSWHEEL')

@section('topbar')
    <x-topbar />
@endsection

@section('content')
    <div class="p-4 space-y-4">

        <!-- Example Dashboard Cards -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-900 rounded-xl p-4 text-center">
                <div class="text-gray-400 text-sm">Total Unit</div>
                <div class="text-2xl font-bold">24</div>
            </div>

            <div class="bg-gray-900 rounded-xl p-4 text-center">
                <div class="text-gray-400 text-sm">RFU Ready</div>
                <div class="text-2xl font-bold text-green-400">18</div>
            </div>
        </div>

    </div>
@endsection
