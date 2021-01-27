@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Situatie buget/livrari luna curenta</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="card">

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th rowspan="2" class="text-center">Grupa de produs</th>
                        @foreach ($weeks as $week)
                            <th colspan="2" class="text-center">Saptamana {{ $week }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($weeks as $week)
                            <th class="text-center">Buget</th>
                            <th class="text-center">Livrat</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td scope="row" class="text-center">{{ $item->name }}</td>
                            @foreach ($weeks as $week)
                                @php
                                    $budget = 'budget_' . $week;
                                    $delivered = 'delivered_' . $week;
                                @endphp

                                <td scope="row" class="text-center">{{ $item->$budget }}</td>
                                <td scope="row" class="text-center">{{ $item->$delivered }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('footer')
    @include('footer')
@stop



