@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Tari</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" data-toggle="modal" data-target="#newCountry">Tara noua</a>
        </div>
    </div>
@stop

@include('countries.partials.form')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="countries" class="table table-bordered table-hover">
                        <thead>
                        <td>Nr crt</td>
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
    const fetch = id => {
        $.ajax({
            url: 'countries/fetch',
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                        $('#name').val(response.data.name);
                        $('.modal-title').html('Editeaza');
                        $('#newCountryForm').attr('action', '/countries/' + id + '/update');
                        $("input[name='_method']").val('PATCH');
                        $('#id').val(id);
                        $('#save').hide();
                        $('#update').show();
            }
        });
    }

    $(document).ready(function() {

        let table = $('#countries').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('countries.index') }}",
            order: [[1, 'asc']],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'actions', name: 'actions'},
            ]
        });

        $('#save').click(function(event) {
            event.preventDefault();
            let country = $('#name').val();
            axios.post('/countries/add', {
                name: country
            }).then(function(response) {
                $('#newCountry').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    title: 'Succes',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true
                });
                table.draw()
            })
        });

        $('#update').click(function(event) {
            event.preventDefault();
            let id = $('#id').val();
            let uri = '/countries/' + id + '/update';
            let country = $('#name').val();
            axios.post(uri, {
                name: country,
                _method: 'patch'
            }).then(function(response) {
                $('#newCountry').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    title: 'Succes',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true
                });
                table.draw()
            })
        });



        $('#newCountry').on('hidden.bs.modal', function () {
            $('#name').val('');
            $('.modal-title').html('Tara noua');
            $('#newCountryForm').attr('action', '/countries/add');
            $("input[name='_method']").val('POST');
            $('#save').show();
            $('#update').hide();
        });
    })

    </script>
@stop

