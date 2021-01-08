@extends('adminlte::page')

@section('title', 'Rapoarte')

@section('content')

    <div class="card">
        <div class="card-header bg-dark">
            <div class="row">
                <div class="col-lg-11">
                    <div class="card-title">
                        <h5>
                            Rapoarte
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="list-group">
                <a href="/reports/active" class="list-group-item list-group-item-action" target="_blank">
                    Comenzi active
                </a>
            </div>
            <div class="list-group">
                <a href="/reports/production/plan" class="list-group-item list-group-item-action" target="_blank">
                    Plan de productie
                </a>
            </div>
            @can('planificare')
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#delivery">
                        Livrari pentru perioada
                    </a>
                </div>
            @endcan
            @can('planificare')
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#printOrders">
                        Printare comenzi multiple
                    </a>
                </div>
            @endcan
        </div>
    </div>
@stop

@include('reports.partials.print')
@include('reports.partials.delivery')

@section('footer')
    @include('footer')
@stop

@section('js')
    <script>
        $('#start').datepicker({
            showWeek: true,
            firstDay: 1,
        });
        $('#end').datepicker({
            showWeek: true,
            firstDay: 1,
        });
    </script>
@stop

