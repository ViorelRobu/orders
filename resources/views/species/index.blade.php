@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Specii</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" data-toggle="modal" data-target="#newSpecies">Specie noua</a>
        </div>
    </div>
@stop

@include('species.partials.form')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="species" class="table table-bordered table-hover">
                        <thead>
                        <td>Nr crt</td>
                        <td>Specia</td>
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
    const fetch = id => {
        $.ajax({
            url: '/species/fetch',
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                        $('#name').val(response.data.name);
                        $('.modal-title').html('Editeaza');
                        $('#newCountryForm').attr('action', '/species/' + id + '/update');
                        $("input[name='_method']").val('PATCH');
                        $('#id').val(id);
                        $('#save').hide();
                        $('#update').show();
            }
        });
    }

    $(document).ready(function() {

        let table = $('#species').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('species.index') }}",
            order: [[1, 'asc']],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'actions', name: 'actions'},
            ]
        });

        $('#save').click(function(event) {
            event.preventDefault();
            let name = $('#name').val();
            axios.post('/species/add', {
                name: name
            }).then(function(response) {
                $('#newSpecies').modal('hide');
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

        $('#update').click(function(event) {
            event.preventDefault();
            let id = $('#id').val();
            let uri = '/species/' + id + '/update';
            let name = $('#name').val();
            axios.post(uri, {
                name: name,
                _method: 'patch'
            }).then(function(response) {
                $('#newSpecies').modal('hide');
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



        $('#newSpecies').on('hidden.bs.modal', function () {
            $('#name').val('');
            $('.modal-title').html('Specie noua');
            $('#newCountryForm').attr('action', '/species/add');
            $("input[name='_method']").val('POST');
            $('#save').show();
            $('#update').hide();
        });
    })

    </script>
@stop

