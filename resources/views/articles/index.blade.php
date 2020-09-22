@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Articole</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" style="margin-left: 5px;" data-toggle="modal" data-target="#import">Importa articole</a>
            <a href="" class="btn btn-primary float-right" id="addNew" data-toggle="modal" data-target="#newArticle">Articol nou</a>
        </div>
    </div>
@stop

@include('articles.partials.form')
@include('articles.partials.import')
@include('audits')

@section('content')


    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>
            {{ session('success') }}
        </strong>
        </div>
    @endif

    @if (session('failure'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>
            {{ session('failure') }}
        </strong>
        </div>
    @endif


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="articles" class="table table-bordered table-hover">
                        <thead>
                        <td>Nr crt</td>
                        <td>Articol</td>
                        <td>Specie</td>
                        <td>Calitate</td>
                        <td>Tip produs</td>
                        <td>Grosime</td>
                        <td>Latime</td>
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
            url: '/articles/fetch',
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                        $('#name').val(response.data.name);
                        $('#species_id').val(response.data.species_id);
                        $('#quality_id').val(response.data.quality_id);
                        $('#product_type_id').val(response.data.product_type_id);
                        $('#thickness').val(response.data.thickness);
                        $('#width').val(response.data.width);
                        $('.modal-title').html('Editeaza');
                        $('#newArticleForm').attr('action', '/articles/' + id + '/update');
                        $("input[name='_method']").val('PATCH');
                        $('#id').val(id);
                        $('#save').remove();
                        $('#submit').append(update);
            }
        });
    }

    const audit = id => {
        $.ajax({
            url: `articles/audits`,
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
        });

        let table = $('#articles').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('articles.index') }}",
            order: [[1, 'asc']],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'species', name: 'species'},
                {data: 'quality', name: 'quality'},
                {data: 'product', name: 'product'},
                {data: 'thickness', name: 'thickness'},
                {data: 'width', name: 'width'},
                {data: 'actions', name: 'actions'},
            ]
        });

        $(document).on('click', '#save', function(event) {
            event.preventDefault();
            let name = $('#name').val();
            let species_id = $('#species_id').val();
            let quality_id = $('#quality_id').val();
            let product_type_id = $('#product_type_id').val();
            let thickness = $('#thickness').val();
            let width = $('#width').val();
            // disable the save button until you get an answer from the server
            $('#save').prop('disabled', true);

            $.ajax({
                url: `/articles/add`,
                method: 'POST',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    name, species_id, quality_id, product_type_id, thickness, width
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
                    $('#newArticle').modal('hide');
                    table.draw()
                }
            });
        });

        $(document).on('click', '#update', function(event) {
            event.preventDefault();
            let id = $('#id').val();
            let uri = '/articles/' + id + '/update';
            let name = $('#name').val();
            let species_id = $('#species_id').val();
            let quality_id = $('#quality_id').val();
            let product_type_id = $('#product_type_id').val();
            let thickness = $('#thickness').val();
            let width = $('#width').val();

            $.ajax({
                url: uri,
                method: 'PATCH',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    name, species_id, quality_id, product_type_id, thickness, width
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
                    $('#newArticle').modal('hide');
                    table.draw()
                }
            });

            // axios.post(uri, {
            //     name, species_id, quality_id, product_type_id, thickness, width,
            //     _method: 'patch'
            // }).then(function(response) {
            //     $('#newArticle').modal('hide');
            //     Swal.fire({
            //         position: 'top-end',
            //         type: response.data.type,
            //         title: 'Succes',
            //         title: response.data.message,
            //         showConfirmButton: false,
            //         timer: 1500,
            //         toast: true
            //     });
            //     table.draw()
            // }).catch(function(err) {
            //     Swal.fire({
            //         position: 'top-end',
            //         type: 'error',
            //         title: 'Eroare',
            //         titleText: err,
            //         showConfirmButton: false,
            //         timer: 5000,
            //         toast: true
            //     });
            // });
        });



        $('#newArticle').on('hidden.bs.modal', function () {
            $('#name').val('');
            $('#species_id').val('');
            $('#quality_id').val('');
            $('#product_type_id').val('');
            $('#thickness').val('');
            $('#width').val('');
            $('.modal-title').html('Articol nou');
            $('#newCountryForm').attr('action', '/articles/add');
            $("input[name='_method']").val('POST');
            $('#save').show();
            $('#update').remove();
            $('#save').remove();
        });
    })

    </script>
@stop

