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
@section('js')
    <script>
        $('#countries').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('countries.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'actions', name: 'actions'},
            ]
        });

        const fetch = id => {
            $.ajax({
                url: 'countries/fetch',
                dataType: 'json',
                data: {id: id},
                type: 'GET',
                success: function(response){
                    switch(response.message_type){
                        case 'success':

                            $('#name').val(response.data.name);
                            $('.modal-title').html('Editeaza');
                            $('#newCountryForm').attr('action', '/countries/' + id + '/update');
                            $("input[name='_method']").val('PATCH');

                            break;
                        case 'danger':
                            alert('A aparut o eroare la incarcarea tarii. Reincarcati pagina si reincercati.')
                            break;
                        default:
                            break;
                    }
                }
            });
        }

    </script>
@stop

