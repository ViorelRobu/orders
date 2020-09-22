@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Clienti</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" id="addNew" data-toggle="modal" data-target="#newCustomer">Client nou</a>
        </div>
    </div>
@stop

@include('customers.partials.form')
@include('customers.partials.destinations')
@include('audits')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="customers" class="table table-bordered table-hover">
                        <thead>
                            <td>Nr crt</td>
                            <td>FIBU</td>
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

@section('footer')
    @include('footer')
@stop

@section('js')
    <script>
    const save = '<button type="submit" id="save" class="btn btn-primary">Creaza</button>';
    const update = '<button type="submit" id="update" class="btn btn-primary">Modifica</button>';

    const getDestinations = id => {
    $.ajax({
        url: '/customers/' + id + '/destinations',
        dataType: 'json',
        data: {id: id},
        type: 'GET',
        success: function(response){
                $.each(response.data, function(key, value) {
                    let destination = `<p>${key+1}. ${value.address}, ${value.country_id}</p>`
                    $('#destinations').append(destination)
                })
            }
        });
    }

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

    const audit = id => {
        $.ajax({
            url: `customers/audits`,
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                response.forEach(element => {
                    let old_values = [];
                    for (let key in element.old_values) {
                        old_values.push(`${key}: ${element.old_values[key]}|`);
                    }
                    let new_values = [];
                    for (let key in element.new_values) {
                        new_values.push(`${key}: ${element.new_values[key]}|`);
                    }
                    let html = `
                    <tr>
                        <td>
                            ${element.user}<br>
                            <small class="text-muted">
                                ${element.event}<br>
                                ${new Date(element.created_at)}
                            </small>
                        </td>
                        <td>${old_values.toString().split('|,').join('<br>').replace('|','')}</td>
                        <td>${new_values.toString().split('|,').join('<br>').replace('|','')}</td>
                    </tr>
                    `;

                    $('#audits-table').append(html);
                });
            }
        });
    }

    $('#audits').on('hidden.bs.modal', function () {
        $('#audits-table').empty();
    });

    $(document).ready(function() {
        $('#addNew').click(function() {
            $('#submit').append(save);
            $('#update').remove();
        })

        $('#country_id').select2({
            width: '100%'
        });

        let table = $('#customers').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('customers.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'fibu', name: 'fibu'},
                {data: 'name', name: 'name'},
                {data: 'country_id', name: 'country_id'},
                {data: 'actions', name: 'actions'},
            ]
        });

        $(document).on('click', '#save', function(event) {
            event.preventDefault();
            let fibu = $('#fibu').val();
            let name = $('#name').val();
            let country_id = $('#country_id').val();
            $('#save').prop('disabled', true);

            $.ajax({
                url: '/customers/add',
                method: 'POST',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    fibu, name, country_id
                },
                error: function(err) {
                    console.log(err);
                    let errors = err.responseJSON.message;
                    let errors_arr = [];
                    for (let error in errors) {
                        errors[error].forEach(el => {
                            errors_arr.push(el + '<br>');
                        });
                    }
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'Eroare',
                        html: errors_arr.toString().split(',').join(''),
                        showConfirmButton: false,
                        timer: 10000,
                        toast: true
                    });
                    $('#save').prop('disabled', false);

                },
                success: function(response) {
                    console.log(response.error);
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    $('#newCustomer').modal('hide');
                    table.draw()
                }
            });

        });

        $(document).on('click', '#update', function(event) {
            event.preventDefault();
            let fibu = $('#fibu').val();
            let name = $('#name').val();
            let country_id = $('#country_id').val();
            let id = $('#id').val();
            let uri = '/customers/' + id + '/update';
            $('#update').prop('disabled', true);

            $.ajax({
                url: uri,
                method: 'PATCH',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    fibu, name, country_id
                },
                error: function(err) {
                    console.log(err);
                    let errors = err.responseJSON.message;
                    let errors_arr = [];
                    for (let error in errors) {
                        errors[error].forEach(el => {
                            errors_arr.push(el + '<br>');
                        });
                    }
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'Eroare',
                        html: errors_arr.toString().split(',').join(''),
                        showConfirmButton: false,
                        timer: 10000,
                        toast: true
                    });
                    $('#update').prop('disabled', false);

                },
                success: function(response) {
                    console.log(response.error);
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    $('#newCustomer').modal('hide');
                    table.draw()
                }
            });
        });

        $('#newCustomer').on('hidden.bs.modal', function () {
            $('#fibu').val('');
            $('#name').val('');
            $('#country_id').val('');
            $('.modal-title').html('Creaza client nou');
            $('#newCustomerForm').attr('action', '/customers/add');
            $("input[name='_method']").val('POST');
            $('#select2-country_id-container').html('');
            $('#select2-country_id-container').attr('title', '');
            $('#update').remove();
            $('#save').remove();
        });

        $('#allDestinations').on('hidden.bs.modal', function () {
            $('#destinations').html('');
        });
    });
    </script>
@stop

