@extends('layouts.app')

@section('title', 'Videotron - MONSWHEEL')

@section('content')
@php
    function actualCellClass($field, $last) {
        if (!$last) {
            return 'bg-green-600/20';
        }

        return $field === $last
            ? 'bg-green-600 text-white font-bold ring-2 ring-green-400'
            : 'bg-green-600/20';
    }
@endphp
<!-- FULLSCREEN BUTTON (TIDAK KE-REFRESH) -->
<button
    id="fullscreenBtn"
    class="fixed top-4 right-4 z-50
           bg-gray-900/80 hover:bg-gray-800
           text-white px-4 py-2 rounded-lg
           shadow-lg transition">
    ⛶ Fullscreen
</button>

<!-- CONTENT YANG DI-REFRESH -->
<div id="videotronContent"></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
    function initTable() {
        
        $.fn.dataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable('#serviceTable')) {
            
            $('#serviceTable').DataTable().destroy();
        }

        $('#serviceTable').DataTable({
            scrollX: false,
            autoWidth: false,
            paging: false,
            searching: false,
            ordering: true,
            info: false,
            pageLength: 15,
            lengthChange: false,
            order: [[3, 'asc']]
        });
    }

    function refreshVideotron() {
        fetch('{{ route("videotron.partial") }}')
            .then(res => res.text())
            .then(html => {
                document.getElementById('videotronContent').innerHTML = html;
                initTable(); // INIT DATATABLE SETELAH LOAD
            });
    }

    refreshVideotron();              // load pertama
    setInterval(refreshVideotron, 5000); // refresh tiap 5 detik
</script>

<script>
    const btn = document.getElementById('fullscreenBtn');

    btn.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            btn.innerText = '⛶ Exit Fullscreen';
        } else {
            document.exitFullscreen();
            btn.innerText = '⛶ Fullscreen';
        }
    });
</script>

@endsection
