@extends('adminlte::page')

@section('title', 'Comenzi active')

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Comenzi active</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" id="addNew" data-toggle="modal" data-target="#newOrder">Comanda noua</a>
        </div>
    </div>
@stop

@include('orders.partials.form')

@section('content')
    @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="orders" class="table table-bordered table-hover">
                        <thead>
                            <td>Cda</td>
                            <td>Prioritate</td>
                            <td>Client</td>
                            <td>Cda client</td>
                            <td>Auftrag</td>
                            <td>Productie</td>
                            <td>Destinatie</td>
                            <td>Luna</td>
                            <td>Livrare</td>
                            <td>ETA</td>
                            <td>KW Client</td>
                            <td>Total</td>
                            <td>Produs</td>
                            <td>Rest</td>
                            <td>%</td>
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
    // save and update buttons markup
    const save = '<input id="save" type="submit" class="btn btn-primary float-right" value="Adauga">';
    const update = '<button type="submit" id="update" class="btn btn-primary">Modifica</button>';

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

    const select = (value) => {
        $('#address').val(value);
        document.getElementById('address').focus();
    }

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

    // Datepickers
    $('#customer_kw').datepicker({
        showWeek: true,
        firstDay: 1,
        dateFormat: 'dd.mm.yy'
    });

    $('#production_kw').datepicker({
        showWeek: true,
        firstDay: 1,
        dateFormat: 'dd.mm.yy'
    });

    $('#delivery_kw').datepicker({
        showWeek: true,
        firstDay: 1,
        dateFormat: 'dd.mm.yy'
    });

    $('#eta').datepicker({
        showWeek: true,
        firstDay: 1,
        dateFormat: 'dd.mm.yy'
    });

    // fetch the data for a certain order and update the modal
    const fetch = id => {
        $.ajax({
            url: '/orders/fetch',
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                $('.modal-title').html('Editeaza');
                $('#newOrderForm').attr('action', '/orders/' + id + '/update');
                $('#id').val(id);
                $('#customer_id').val(response.data.customer_id);
                $('#select2-customer_id-container').html(response.data.customer);
                $('#select2-customer_id-container').attr('title', response.data.customer);
                $('#customer_order').val(response.data.customer_order);
                $('#auftrag').val(response.data.auftrag);
                $('#country_id').val(response.data.country_id);
                $('#select2-country_id-container').html(response.data.country);
                $('#select2-country_id-container').attr('title', response.data.country);
                $('#address').val(response.data.address);
                $('#destination_id').val(response.data.destination_id);
                $('#customer_kw').val(response.data.customer_kw);
                $('#production_kw').val(response.data.production_kw);
                $('#delivery_kw').val(response.data.delivery_kw);
                $('#eta').val(response.data.eta);
                $('#save').remove();
                $('#submit').append(update);
            }
        });
    }

    $(document).ready(function() {
        // configure button for a add new event
        $('#addNew').click(function() {
            $('#update').remove();
            $('#submit').append(save);
        })

        // add select 2 to all selects
        $('#country_id').select2({
            width: '100%'
        });

        $('#customer_id').select2({
            width: '100%'
        });

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
            ajax: "{{ route('orders.index') }}",
            columns: [
                {data: 'order', name: 'order'},
                {data: 'priority', name: 'priority'},
                {data: 'customer', name: 'customer'},
                {data: 'customer_order', name: 'customer_order'},
                {data: 'auftrag', name: 'auftrag'},
                {data: 'kw_production', name: 'kw_production'},
                {data: 'destination', name: 'destination'},
                {data: 'month', name: 'month'},
                {data: 'kw_delivery', name: 'kw_delivery'},
                {data: 'eta', name: 'eta'},
                {data: 'kw_customer', name: 'kw_customer'},
                {data: 'total', name: 'total'},
                {data: 'produced', name: 'produced'},
                {data: 'to_produce', name: 'to_produce'},
                {data: 'percentageDisplay', name: 'percentageDisplay'},
                {data: 'actions', name: 'actions'},
            ],
            rowCallback: function(row, data, index) {
                $('td:eq(14)', row).css('color', getColor(data.percentage));
            },
        });


        $(document).on('click', '#update', function(event) {
            event.preventDefault();
            let id = $('#id').val();
            let customer_id = $('#customer_id').val();
            let customer_order = $('#customer_order').val();
            let auftrag = $('#auftrag').val();
            let destination_id = $('#destination_id').val();
            let customer_kw = $('#customer_kw').val();
            let production_kw = $('#production_kw').val();
            let delivery_kw = $('#delivery_kw').val();
            let eta = $('#eta').val();
            let url = '/orders/' + id + '/update';
            $.ajax({
                url: url,
                method: 'PATCH',
                dataType: 'json',
                data: {
                '_token': '{{ csrf_token() }}',
                customer_id, customer_order, auftrag, destination_id,
                customer_kw: customer_kw.split('.').reverse().join('-'),
                production_kw: production_kw.split('.').reverse().join('-'),
                delivery_kw: delivery_kw.split('.').reverse().join('-'),
                eta: eta.split('.').reverse().join('-'),
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
                    $('#newOrder').modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    table.draw()
                }
            });
        });

        // reset the modal on closing it
        $('#newOrder').on('hidden.bs.modal', function () {
            $('#customer_id').val('');
            $('#customer_order').val('');
            $('#auftrag').val('');
            $('#country_id').val('');
            $('#destination_id').val('');
            $('#address').val('');
            $('#customer_kw').val('');
            $('#production_kw').val('');
            $('#delivery_kw').val('');
            $('#eta').val('');
            $('.modal-title').html('Creaza comanda noua');
            $('#newOrderForm').attr('action', '/orders/add');
            $("input[name='_method']").val('POST');
            $("country_id").val('');
            $('#select2-country_id-container').html('');
            $('#select2-country_id-container').attr('title', '');
            $("customer_id").val('');
            $('#select2-customer_id-container').html('');
            $('#select2-customer_id-container').attr('title', '');
            $('#update').remove();
            $('#save').remove();
        });
    });
    </script>
@stop

