@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Finisaje</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" id="addNew" data-toggle="modal" data-target="#newRefinement">Finisaj nou</a>
        </div>
    </div>
@stop

@include('refinements.partials.form')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="refinements" class="table table-bordered table-hover">
                        <thead>
                        <td>Nr crt</td>
                        <td>Finisaj</td>
                        <td>Descriere</td>
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
            url: '/refinements/fetch',
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                        $('#name').val(response.data.name);
                        $('#description').val(response.data.description);
                        $('.modal-title').html('Editeaza');
                        $('#newRefinementForm').attr('action', '/refinements/' + id + '/update');
                        $("input[name='_method']").val('PATCH');
                        $('#id').val(id);
                        $('#save').remove();
                        $('#submit').append(update);
            }
        });
    }

    $(document).ready(function() {
        $('#addNew').click(function() {
            $('#submit').append(save);
            $('#update').remove();
        })

        let table = $('#refinements').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('refinements.index') }}",
            order: [[1, 'asc']],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'actions', name: 'actions'},
            ]
        });

        $(document).on('click', '#save', function(event) {
            event.preventDefault();
            let name = $('#name').val();
            let description = $('#description').val();
            axios.post('/refinements/add', {
                name,
                description
            }).then(function(response) {
                $('#newRefinement').modal('hide');
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

        $(document).on('click', '#update', function(event) {
            event.preventDefault();
            let id = $('#id').val();
            let uri = '/refinements/' + id + '/update';
            let name = $('#name').val();
            let description = $('#description').val();
            axios.post(uri, {
                name,
                description,
                _method: 'patch'
            }).then(function(response) {
                Swal.fire({
                    position: 'top-end',
                    type: response.data.type,
                    title: 'Succes',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true
                });
                table.draw()
            }).catch(function(err) {
                $('#newRefinement').modal('hide');
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



        $('#newRefinement').on('hidden.bs.modal', function () {
            $('#name').val('');
            $('#description').val('');
            $('.modal-title').html('Finisaj nou');
            $('#newQualityForm').attr('action', '/refinements/add');
            $("input[name='_method']").val('POST');
            $('#update').remove();
            $('#save').remove();
        });
    })

    </script>
@stop

