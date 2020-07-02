@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Clienti</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" data-toggle="modal" data-target="#newOrder">Client nou</a>
        </div>
    </div>
@stop

{{--@include('orders.partials.form')--}}

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="customers" class="table table-bordered table-hover">
                        <thead>
                            <td>Nr crt</td>
                            <td>Client</td>
                            <td>Tara</td>
                            <td>Actiuni</td>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
                    $('#customers').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('customers.index') }}",
                        columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'name', name: 'name'},
                            {data: 'country_id', name: 'country_id'},
                            {data: 'actions', name: 'actions'},
                        ]
                    });
    </script>
@stop

