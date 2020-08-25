@extends('adminlte::page')

@section('title', 'Rapoarte')

@section('content')

    <div class="card">
        <div class="card-header bg-dark">
            <div class="row">
                <div class="col-lg-11">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-lg-10">
                                <h5>
                                    Rapoarte
                                </h5>
                            </div>
                            <div class="col-lg-1">
                                <a href="/reports/archive" target="_blank">
                                    <i class="fas fa-history"></i>
                                </a>
                            </div>
                        </div>
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
        </div>
    </div>
@stop

@section('footer')
    @include('footer')
@stop

@section('js')
    <script>

    </script>
@stop

