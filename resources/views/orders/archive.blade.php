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
                    <table id="orders" class="table table-bordered table-hover">
                        <thead>
                            <td>Cda</td>
                            <td>Client</td>
                            <td>Cda client</td>
                            <td>Auftrag</td>
                            <td>Destinatie</td>
                            <td>Data incarcare</td>
                            <td>Total</td>
                            <td></td>
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
                {data: 'customer', name: 'customer'},
                {data: 'customer_order', name: 'customer_order'},
                {data: 'auftrag', name: 'auftrag'},
                {data: 'destination', name: 'destination'},
                {data: 'loading_date', name: 'loading_date'},
                {data: 'total', name: 'total'},
                {data: 'actions', name: 'actions'},
            ]
        });
    });
    </script>
@stop

