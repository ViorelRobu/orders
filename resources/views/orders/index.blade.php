@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <h1 class="m-0 text-dark">Comenzi</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="comenzi" class="table table-bordered table-hover">
                        <thead>
                            <td>Client</td>
                            <td>Comanda client</td>
                            <td>Auftrag</td>
                            <td>Destinatie</td>
                            <td>Productie</td>
                            <td>Incarcare</td>
                            <td>Luna</td>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
                    $('#comenzi').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('orders.index') }}",
                        columns: [
                            {data: 'id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'au', name: 'au'},
                            {data: 'destination', name: 'destination'},
                            {data: 'production', name: 'production'},
                            {data: 'loading', name: 'loading'},
                            {data: 'month', name: 'month'},
                        ]
                    });
    </script>
@stop

