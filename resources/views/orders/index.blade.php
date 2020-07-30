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
    const save = '<input id="save" type="submit" class="btn btn-primary float-right" value="Adauga">';
    const update = '<button type="submit" id="update" class="btn btn-primary">Modifica</button>';

    const fetch = id => {
        $.ajax({
            url: '/customers/fetch',
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                $('#fibu').val(response.data.fibu);
                $('#name').val(response.data.name);
                $('.modal-title').html('Editeaza');
                $('#newCustomerForm').attr('action', '/customers/' + id + '/update');
                $("input[name='_method']").val('PATCH');
                $('#country_id').val(response.data.country_id);
                $('#select2-country_id-container').html(response.data.country);
                $('#select2-country_id-container').attr('title', response.data.country);
                $('#id').val(id);
                $('#save').remove();
                $('#submit').append(update);
            }
        });
    }

    $(document).ready(function() {
        $('#addNew').click(function() {
            $('#update').remove();
            $('#submit').append(save);
        })

        $('#country_id').select2({
            width: '100%'
        });

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
                {data: 'percentage', name: 'percentage'},
                {data: 'actions', name: 'actions'},
            ]
        });


        $(document).on('click', '#update', function(event) {
            event.preventDefault();
            let fibu = $('#fibu').val();
            let name = $('#name').val();
            let country_id = $('#country_id').val();
            let id = $('#id').val();
            let uri = '/customers/' + id + '/update';
            axios.post(uri, {
                fibu: fibu,
                name: name,
                country_id: country_id,
                _method: 'patch'
            }).then(function(response) {
                $('#newCustomer').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    type: response.data.type,
                    title: 'Succes',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 5000,
                    toast: true
                });
                table.draw()
            }).catch(function(err) {
                console.log(err);
                Swal.fire({
                    position: 'top-end',
                    type: 'error',
                    title: 'Eroare',
                    titleText: err,
                    showConfirmButton: false,
                    timer: 5000,
                    toast: true
                });
            });
        });

        $('#newOrder').on('hidden.bs.modal', function () {
            $('#fibu').val('');
            $('#name').val('');
            $('#country_id').val('');
            $('.modal-title').html('Creaza comanda noua');
            $('#newOrderForm').attr('action', '/orders/add');
            $("input[name='_method']").val('POST');
            $('#select2-country_id-container').html('');
            $('#select2-country_id-container').attr('title', '');
            $('#update').remove();
            $('#save').remove();
        });
    });
    </script>
@stop

