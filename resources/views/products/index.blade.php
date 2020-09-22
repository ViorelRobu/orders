@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Tipuri de produse</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" id="addNew" data-toggle="modal" data-target="#newProduct">Tip produs nou</a>
        </div>
    </div>
@stop

@include('products.partials.form')
@include('audits')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="products" class="table table-bordered table-hover">
                        <thead>
                        <td>Nr crt</td>
                        <td>Tip produs</td>
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

    const fetch = id => {
        $.ajax({
            url: '/products/fetch',
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                        $('#name').val(response.data.name);
                        $('.modal-title').html('Editeaza');
                        $('#newProductForm').attr('action', '/products/' + id + '/update');
                        $("input[name='_method']").val('PATCH');
                        $('#id').val(id);
                        $('#save').remove();
                        $('#submit').append(update);
            }
        });
    }

    const audit = id => {
        $.ajax({
            url: `products/audits`,
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

        let table = $('#products').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.index') }}",
            order: [[1, 'asc']],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'actions', name: 'actions'},
            ]
        });

        $(document).on('click', '#save', function(event) {
            event.preventDefault();
            let name = $('#name').val();

            $.ajax({
                url: '/products/add',
                method: 'POST',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    name
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
                    $('#newProduct').modal('hide');
                    table.draw()
                }
            });
        });

        $(document).on('click', '#update', function(event) {
            event.preventDefault();
            let id = $('#id').val();
            let uri = '/products/' + id + '/update';
            let name = $('#name').val();

            $.ajax({
                url: uri,
                method: 'PATCH',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    name
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
                    $('#newProduct').modal('hide');
                    table.draw()
                }
            });
        });

        $('#newProduct').on('hidden.bs.modal', function () {
            $('#name').val('');
            $('.modal-title').html('Tip produs nou');
            $('#newProductForm').attr('action', '/products/add');
            $("input[name='_method']").val('POST');
            $('#update').remove();
            $('#save').remove();
        });
    })

    </script>
@stop

