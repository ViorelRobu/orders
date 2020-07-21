@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Numere de comanda</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" id="addNew" data-toggle="modal" data-target="#newNumber">Numar de comanda nou</a>
        </div>
    </div>
@stop

@include('numbers.partials.form')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="numbers" class="table table-bordered table-hover">
                        <thead>
                        <td>Nr crt</td>
                        <td>Numar de comanda</td>
                        <td>Adaugat la</td>
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

    $(document).ready(function() {
        $('#addNew').click(function() {
            $('#submit').append(save);
        })

        let table = $('#numbers').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('order_numbers.index') }}",
            order: [[1, 'asc']],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'start_number', name: 'start_number'},
                {data: 'created_at', name: 'created_at'},
            ]
        });

        $(document).on('click', '#save', function(event) {
            event.preventDefault();
            let start_number = $('#start_number').val();
            axios.post('/numbers/add', {
                start_number
            }).then(function(response) {
                $('#newNumber').modal('hide');
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

        $('#newNumber').on('hidden.bs.modal', function () {
            $('#name').val('');
            $('.modal-title').html('Numar de comanda nou');
            $('#newQualityForm').attr('action', '/numbers/add');
            $("input[name='_method']").val('POST');
            $('#save').remove();
        });
    })

    </script>
@stop

