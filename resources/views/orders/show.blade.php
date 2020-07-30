@extends('adminlte::page')

@section('title', 'Comanda ' . $order->order)

@section('content_header')
    <div class="row">
        <div class="col-lg-6">

        </div>
        <div class="col-lg-6">
            {{-- <a href="" class="btn btn-primary float-right" id="addNew" data-toggle="modal" data-target="#newOrder">Comanda noua</a> --}}
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-dark">
            <div class="row">
                <div class="col-lg-11">
                    <div class="card-title">
                        <h5>Comanda {{ $order->order }}
                            @if ($order->loading_date != null)
                                <span class="badge badge-pill badge-success">Incarcata in {{ $loading_date }}</span>
                            @endif
                            @if ($order->archived === 1)
                                <span class="badge badge-pill badge-info">Arhivata</span>
                            @else
                                <span class="badge badge-pill badge-success" id="priority">
                                    Prioritate <span id="priority_text">{{ $order->priority }}</span>
                                    <input style="display:none" id="priority_value" type="text" value="{{ $order->priority }}">
                                </span>
                            @endif
                        </h5>
                    </div>
                </div>
                <div class="col-lg-1">
                    <i id="edit_details_1" class="fas fa-edit float-right"></i>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-1">
                    <p><strong>Client</strong></p>
                    <p><strong>Comanda client</strong></p>
                    <p><strong>Auftrag</strong></p>
                    <p><strong>Adresa de livrare</strong></p>
                    <p><strong>Tara de livrare</strong></p>
                </div>
                <div class="col-lg-3" id="order_text">
                    <p>{{ $customer->name }}</p>
                    <p>{{ $order->customer_order }}</p>
                    <p>{{ $order->auftrag }}</p>
                    <p>{{ $destination->address}}</p>
                    <p>{{ $country->name }}</p>
                </div>
                <div style="display: none" class="col-lg-5" id="order_data">
                    {{-- TODO: input pentru editare --}}
                </div>
                <div class="col-lg-8">
                    <strong>Observatii</strong>
                    <p>
                        {{ $order->observations }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark">
            <div class="row">
                <div class="col-lg-11">
                    <div class="card-title">
                        <h5>
                            Sumar comanda
                        </h5>
                    </div>
                </div>
                <div class="col-lg-1">
                    <i id="edit_details_2" class="fas fa-edit float-right"></i>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-1">
                    <p class="text-center"><strong>KW client</strong></p>
                    <p class="text-center">{{ $customer_kw }}</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>KW productie</strong></p>
                    <p class="text-center">{{ $production_kw }}</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>KW livrare</strong></p>
                    <p class="text-center">{{ $delivery_kw }}</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>ETA</strong></p>
                    <p class="text-center">{{ $eta }}</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Luna</strong></p>
                    <p class="text-center">{{ $order->month }}</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Total comanda</strong></p>
                    <p class="text-center">50 mc</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Rest de produs</strong></p>
                    <p class="text-center">10 mc</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Livrat</strong></p>
                    <p class="text-center">40 mc</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Rest de livrat</strong></p>
                    <p class="text-center">10 mc</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Gata livrare</strong></p>
                    <p class="text-center">10 mc</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Finalizat</strong></p>
                    <p class="text-center">100%</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Livrat</strong></p>
                    <p class="text-center">80%</p>
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
            // allow editing of priority
            $('#priority').dblclick(function() {
                $('#priority_value').show();
                $('#priority_text').hide();
            })
            // save the priority and display the value
            $('#priority_value').keyup(function(e) {
                if(e.keyCode == 13) {
                    $('#priority_value').hide();
                    $('#priority_text').show();
                }
            });

            //
        });
    </script>
@stop

