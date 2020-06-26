@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Comanda {{ $order->id }}</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" data-toggle="modal" data-target="#newPos">Pozitie noua</a>
        </div>
    </div>
@stop

@include('orders.partials.details')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-1">
                            <b>Client</b> <br>
                            <b>Auftrag</b> <br>
                            <b>Destinatie</b> <br>
                            <b>Productie</b> <br>
                            <b>Incarcare</b> <br>
                            <b>Luna</b> <br>
                        </div>
                        <div class="col-lg-6">
                            {{ $customer->name }} <br>
                            {{ $order->customer_order }} <br>
                            {{ $order->destination }}<br>
                            {{ $order->production }}<br>
                            {{ $order->loading }}<br>
                            {{ $order->month }}<br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="comenzi" class="table table-bordered table-hover">
                        <thead>
                        <td>Nr crt</td>
                        <td>Articol</td>
                        <td>Finisaje</td>
                        <td>Grosime</td>
                        <td>Latime</td>
                        <td>Lungime</td>
                        <td>Bucati</td>
                        <td>Volum</td>
                        <td>Eticheta</td>
                        <td>Sticker panou</td>
                        <td>EAN palet</td>
                        <td>EAN picior</td>
                        <td>Paletizare</td>
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
        $('#comenzi').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/orders/' . $order->id) }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'article_id', name: 'article_id'},
                {data: 'finisaje', name: 'finisaje'},
                {data: 'grosime', name: 'grosime'},
                {data: 'latime', name: 'latime'},
                {data: 'lungime', name: 'lungime'},
                {data: 'bucati', name: 'bucati'},
                {data: 'volum', name: 'volum'},
                {data: 'eticheta', name: 'eticheta'},
                {data: 'stick_panou', name: 'stick_panou'},
                {data: 'ean_pal', name: 'ean_pal'},
                {data: 'ean_picior', name: 'ean_picior'},
                {data: 'paletizare', name: 'paletizare'},
                {data: 'actions', name: 'actions', searchable: false, orderable: false},
            ]
        });
    </script>
@stop

