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
                    @if ($order->archived == 0)
                        <i class="fas fa-truck-loading" style="margin-left:10px" data-toggle="modal" data-target="#loadingDate"></i>
                    @endif
                </div>
                <div class="col-lg-1">
                    <i id="edit_details" class="fas fa-edit float-right" style="margin-left:10px"></i>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2">
                    <div class="form-group"><strong>Client</strong></div>
                    <div class="form-group"><strong>Comanda client</strong></div>
                    <div class="form-group"><strong>Auftrag</strong></div>
                    <div class="form-group"><strong>Tara de livrare</strong></div>
                    <div class="form-group"><strong>Adresa de livrare</strong></div>
                </div>
                <div class="col-lg-2" id="order_text">
                    <div>
                        <div id="customer" class="form-group">
                            {{ $customer->name }}
                        </div>
                        <input type="hidden" name="customer__id" id="customer__id" value="{{ $order->customer_id }}">
                        <select class="form-control" name="customer_id" id="customer_id" style="display: none">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div id="customer_order_text" class="form-group">
                            {{ $order->customer_order }}
                        </div>
                        <input type="text"
                            class="form-control" name="customer_order" id="customer_order" placeholder="Comanda clientului" style="display: none">
                    </div>
                    <div>
                        <div id="auftrag_text" class="form-group">
                            {{ $order->auftrag }}
                        </div>
                        <input type="text"
                            class="form-control" name="auftrag" id="auftrag" placeholder="Auftrag" style="display: none">
                    </div>
                    <div>
                        <div id="country_text" class="form-group">
                            {{ $country->name }}
                        </div>
                        <input type="hidden" name="country__id" id="country__id" value="{{ $destination->country_id }}">
                        <select class="form-control" name="country_id" id="country_id" style="display: none">
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div id="address_text" class="form-group">
                            {{ $destination->address}}
                        </div>
                        <input type="hidden" name="destination_id" id="destination_id" value="{{ $order->destination_id }}">
                        <input type="text"
                            class="form-control" name="address" id="address" placeholder="Adresa de livrare" style="display: none" data-toggle="dropdown" autocomplete="off">
                        <div class="dropdown-menu" id="autocomplete">
                            <a class="dropdown-item" href="#">Se incarca...</a>
                        </div>
                    </div>
                    <div class="input-group">
                        <button class="btn btn-secondary float-right form-control" id="cancel_details" style="display: none">Anuleaza</button>
                        <button class="btn btn-primary float-right form-control" id="save_details" style="display: none">Salveaza</button>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div id="observations_title">
                        <strong>Observatii</strong>
                        <i id="edit_observations" class="fas fa-edit"></i>
                    </div>
                    <div id="observations_text">
                        {!! $order->observations !!}
                    </div>
                    <textarea name="observations" id="observations" cols="20" rows="4" style="display: none"></textarea>
                    <div class="input-group">
                        <button class="btn btn-secondary float-right form-control" id="cancel_observations" style="display: none">Anuleaza</button>
                        <button class="btn btn-primary float-right form-control" id="save_observations" style="display: none">Salveaza</button>
                    </div>
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

    <!-- Modal -->
    <div class="modal fade" id="loadingDate" tabindex="-1" role="dialog" aria-labelledby="loadingDate" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-sm">
                <div class="modal-header">
                    <h5 class="modal-title">Data de incarcare</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <form action="/orders/{{ $order->id }}/ship" method="POST">
                        <div class="form-group">
                            @method('PATCH')
                            @csrf
                            <label for="">Data de incarcare</label>
                            <input type="text"
                                class="form-control" name="loading_date" id="loading_date" placeholder="Data incarcare" autocomplete="off">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuleaza</button>
                                <button type="submit" class="btn btn-primary" id="load_truck">Salveaza</button>
                            </div>
                        </div>
                    </form>
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
        // initialize the TinyMCE editor
        tinymce.init({
            selector: '#observations'
        });

        // select function for address autocomplete
        const select = (value) => {
            $('#address').val(value);
            document.getElementById('address').focus();
        }

        // datepickers
        $('#loading_date').datepicker({
            showWeek: true,
            firstDay: 1,
            dateFormat: 'dd.mm.yy'
        });

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
                    $.ajax({
                        url: '/orders/{{ $order->id }}/update/priority',
                        method: 'PATCH',
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            priority: $('#priority_value').val()
                        },
                        error: function(err) {
                            console.log(err);
                            Swal.fire({
                                position: 'top-end',
                                type: 'error',
                                title: 'Eroare',
                                titleText: err.responseJSON.message,
                                showConfirmButton: false,
                                timer: 5000,
                                toast: true
                            });
                        },
                        success: function(response) {
                            Swal.fire({
                                position: 'top-end',
                                type: response.status,
                                title: 'Succes',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 5000,
                                toast: true
                            });
                            $('#priority_text').html(response.value)
                        }
                    });
                };
            });

            // allow editing of the main details
            $('#edit_details').click(function() {
                $('#customer').hide();
                $('#customer_id').show();
                $('#customer_id').val($('#customer__id').val());
                $('#customer_order_text').hide();
                $('#customer_order').show();
                $('#customer_order').val($('#customer_order_text').html().trim());
                $('#auftrag_text').hide();
                $('#auftrag').show();
                $('#auftrag').val($('#auftrag_text').html().trim());
                $('#country_text').hide();
                $('#country_id').show();
                $('#country_id').val($('#country__id').val());
                $('#address_text').hide();
                $('#address').show();
                $('#address').val($('#address_text').html().trim());
                $('#save_details').show();
                $('#cancel_details').show();
                $('#edit_details').hide();
            })

            // cancel the editing of the main details
            $('#cancel_details').click(function() {
                $('#customer').show();
                $('#customer_id').hide();
                $('#customer_order_text').show();
                $('#customer_order').hide();
                $('#auftrag_text').show();
                $('#auftrag').hide();
                $('#country_text').show();
                $('#country_id').hide();
                $('#address_text').show();
                $('#address').hide();
                $('#save_details').hide();
                $('#cancel_details').hide();
                $('#edit_details').show();
            })

            // save the main details
            $('#save_details').click(function() {
                $.ajax({
                    url: '/orders/{{ $order->id }}/update/details',
                    method: 'PATCH',
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        customer_id: $('#customer_id').val(),
                        customer_order: $('#customer_order').val(),
                        auftrag: $('#auftrag').val(),
                        destination_id: $('#destination_id').val(),
                    },
                    error: function(err) {
                        console.log(err);
                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: 'Eroare',
                            titleText: err.responseJSON.message,
                            showConfirmButton: false,
                            timer: 5000,
                            toast: true
                        });
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'top-end',
                            type: response.status,
                            title: 'Succes',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 5000,
                            toast: true
                        });
                        $('#customer__id').val(response.order.customer_id);
                        $('#customer').show().html(response.customer.name);
                        $('#customer_id').hide();
                        $('#customer_order_text').show().html(response.order.customer_order);
                        $('#customer_order').hide();
                        $('#auftrag_text').show().html(response.order.auftrag);
                        $('#auftrag').hide();
                        $('#country__id').val(response.country.id);
                        $('#country_text').show().html(response.country.name);
                        $('#country_id').hide();
                        $('#destination_id').val(response.order.destination_id);
                        $('#address_text').show().html(response.destination.address);
                        $('#address').hide();
                        $('#save_details').hide();
                        $('#edit_details').show();
                    }
                });
            });

            // confirm existing destination or add a new one for the current customer
            $('#address').blur(function() {
                let customer_id = $('#customer_id').val();
                let address = $('#address').val();
                let country_id = $('#country_id').val();
                $.ajax({
                    url: `/customers/${customer_id}/destinations/find`,
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        customer_id, address, country_id
                    },
                    type: 'POST',
                    success: function(response) {
                        $('#destination_id').val(response.data);
                    }
                });
            });

            // return all destinations for the selected customer and country
            $('#address').focus(function() {
                let customer_id = $('#customer_id').val();
                let country_id = $('#country_id').val();
                $.ajax({
                    url: `/customers/${customer_id}/destinations/search`,
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        customer_id, country_id
                    },
                    type: 'POST',
                    success: function(response) {
                        $('#autocomplete').html('');
                        if (response.count > 0) {
                            response.data.forEach(element => {
                                let string = `<a class="dropdown-item" onclick="select(this.innerHTML)" href="#">${element}</a>`
                                $('#autocomplete').append(string);
                            });
                        } else {
                                let string = `<a class="dropdown-item" href="#">Nu exista nici o adresa pentru clientul si tara selectata!</a>`
                                $('#autocomplete').append(string);
                        }

                    }
                });
            });

            // allow editing of the observations
            $('#edit_observations').click(function() {
                $('#observations').val($('#observations_text').html().trim());
                tinymce.get('observations').show();
                $('#save_observations').show();
                $('#cancel_observations').show();
                $('#observations_text').hide();
                $('#edit_observations').hide();
            });

            // cancel editing of the observations
            $('#cancel_observations').click(function() {
                tinymce.get('observations').hide();
                $('#save_observations').hide();
                $('#cancel_observations').hide();
                $('#observations_text').show();
                $('#edit_observations').show();
            });

            // save the main details
            $('#save_observations').click(function() {
                $.ajax({
                    url: '/orders/{{ $order->id }}/update/observations',
                    method: 'PATCH',
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        observations: tinymce.activeEditor.getContent(),
                    },
                    error: function(err) {
                        console.log(err);
                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: 'Eroare',
                            titleText: err.responseJSON.message,
                            showConfirmButton: false,
                            timer: 5000,
                            toast: true
                        });
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'top-end',
                            type: response.status,
                            title: 'Succes',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 5000,
                            toast: true
                        });
                        $('.tox-tinymce').hide();
                        tinymce.get('observations').hide();
                        $('#observations_text').html(response.order.observations);
                        $('#save_observations').hide();
                        $('#cancel_observations').hide();
                        $('#observations_text').show();
                        $('#edit_observations').show();
                    }
                });
            });

        });
    </script>
@stop

