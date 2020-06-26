@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Comenzi</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" data-toggle="modal" data-target="#newOrder">Comanda noua</a>
        </div>
    </div>
@stop

@include('orders.partials.form')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="comenzi" class="table table-bordered table-hover">
                        <thead>
                            <td></td>
                            <td>Nr Comanda</td>
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
                            {data: 'view', name: 'view', searchable: false, orderable: false, className: 'text-center'},
                            {data: 'id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'order', name: 'order'},
                            {data: 'au', name: 'au'},
                            {data: 'destination', name: 'destination'},
                            {data: 'production', name: 'production'},
                            {data: 'loading', name: 'loading'},
                            {data: 'month', name: 'month'},
                        ]
                    });
    </script>
@stop

