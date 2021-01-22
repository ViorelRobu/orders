@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Buget</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" id="addNew" data-toggle="modal" data-target="#newBudget">Pozitie noua</a>
        </div>
    </div>
@stop

@include('budget.partials.form')
@include('audits')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="budget" class="table table-bordered table-hover">
                        <thead>
                        <td>ID</td>
                        <td>Grupa produs</td>
                        <td>An</td>
                        <td>Saptamana</td>
                        <td>Volum</td>
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
    const save = '<button type="submit" id="save" class="btn btn-primary">Creaza</button>';
    const update = '<button type="submit" id="update" class="btn btn-primary">Modifica</button>';

    const fetch = id => {
        $.ajax({
            url: 'budget/fetch',
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                console.log(response.data);
                        $('#id').val(response.data.id);
                        $('.modal-title').html('Editeaza pozitie buget');
                        $('#newBudgetForm').attr('action', '/budget/' + id + '/update');
                        $("input[name='_method']").val('PATCH');
                        $('#group').val(response.data.product_type_id);
                        $('#year').val(response.data.year);
                        $('#week').val(response.data.week);
                        $('#volume').val(response.data.volume);
                        $('#save').remove();
                        $('#submit').append(update);
            }
        });
    }

    const audit = id => {
        $.ajax({
            url: `budget/audits`,
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                response.forEach(element => {
                    let old_values = [];
                    for (let key in element.old_values) {
                        old_values.push(`${key}: ${element.old_values[key]}`);
                    }
                    let new_values = [];
                    for (let key in element.new_values) {
                        new_values.push(`${key}: ${element.new_values[key]}`);
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
                        <td>${old_values.toString().split(',').join('<br>')}</td>
                        <td>${new_values.toString().split(',').join('<br>')}</td>
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

        let table = $('#budget').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('budget.index') }}",
            order: [[0, 'asc']],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'product_type_id', name: 'product_type_id'},
                {data: 'year', name: 'year'},
                {data: 'week', name: 'week'},
                {data: 'volume', name: 'volume'},
                {data: 'actions', name: 'actions'},
            ]
        });

        $(document).on('click', '#save', function(event) {
            event.preventDefault();
            let id = $('#id').val();
            let group = $('#group').val();
            let year = $('#year').val();
            let week = $('#week').val();
            let volume = $('#volume').val();

            $('#save').prop('disabled', true);

            $.ajax({
                url: `/budget/add`,
                method: 'POST',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    id, group, year, week, volume
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
                    $('#newBudget').modal('hide');
                    table.draw()
                }
            });

        });

        $(document).on('click', '#update', function(event) {
            event.preventDefault();
            let id = $('#id').val();
            let uri = '/budget/' + id + '/update';
            let group = $('#group').val();
            let year = $('#year').val();
            let week = $('#week').val();
            let volume = $('#volume').val();

            $('#update').prop('disabled', true);

            $.ajax({
                url: uri,
                method: 'PATCH',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    id, group, year, week, volume
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
                    $('#newBudget').modal('hide');
                    table.draw()
                }
            });

        });

        $('#newBudget').on('hidden.bs.modal', function () {
            $('#group').val('');
            $('.modal-title').html('Intrare noua buget');
            $('#newBudgetForm').attr('action', '/budget/add');
            $("input[name='_method']").val('POST');
            $('#id').val('');
            $('#group').val('');
            $('#year').val('');
            $('#week').val('');
            $('#volume').val('');
            $('#save').show();
            $('#update').remove();
            $('#save').remove();
        });
    })

    </script>
@stop

