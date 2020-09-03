@extends('adminlte::page')

@section('title', 'Arhiva comenzi')

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Arhiva comenzi</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="orders" class="table table-hover">
                        <thead class="thead-dark">
                            <th>Cda</th>
                            <th>Specif</th>
                            <th>Client</th>
                            <th>Cda client</th>
                            <th>Auftrag</th>
                            <th>Destinatie</th>
                            <th>Data incarcare</th>
                            <th>Volum (m&sup3;)</th>
                            <th></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    @include('footer')
@stop

@section('js')
    <script>
    $(document).ready(function() {
        // percentage color for order completion percentage
        function getColor(value){
            //value from 0 to 1
            var hue=((value)*120).toString(10);

            return ["hsl(",hue,",100%,50%)"].join("");
        }


        // orders datatable
        let table = $('#orders').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('archive.index') }}",
            columns: [
                {data: 'order', name: 'order'},
                {data: 'specification', name: 'specification'},
                {data: 'customer', name: 'customer'},
                {data: 'customer_order', name: 'customer_order'},
                {data: 'auftrag', name: 'auftrag'},
                {data: 'destination', name: 'destination'},
                {data: 'loading_date', name: 'loading_date'},
                {data: 'total', name: 'total'},
                {data: 'actions', name: 'actions'},
            ],
            rowCallback: function(row, data, index) {
                $('td:eq(0)', row).addClass('table-secondary').css('font-weight', 'bold');
                $('td:eq(1)', row).addClass('table-primary').css('font-weight', 'bold');
                $('td:eq(2)', row).addClass('table-success').css('font-weight', 'bold');
                $('td:eq(3)', row).addClass('table-success').css('font-weight', 'bold');
                $('td:eq(4)', row).addClass('table-success').css('font-weight', 'bold');
                $('td:eq(5)', row).addClass('table-success').css('font-weight', 'bold');
                $('td:eq(6)', row).addClass('table-success').css('font-weight', 'bold');
                $('td:eq(7)', row).addClass('table-success').css('font-weight', 'bold');
            },
        });
    });
    </script>
@stop

