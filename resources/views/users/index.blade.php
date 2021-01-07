@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Utilizatori</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" id="addNew" data-toggle="modal" data-target="#newUser">Utilizator nou</a>
        </div>
    </div>
@stop

@include('users.partials.form')
@include('audits')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="users" class="table table-bordered table-hover">
                        <thead>
                        <td>ID</td>
                        <td>Nume</td>
                        <td>Email</td>
                        <td>Username</td>
                        <td>Rol</td>
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
            url: '/users/fetch',
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                        $('#name').val(response.data.name);
                        $('.modal-title').html('Editeaza');
                        $('#newUserForm').attr('action', '/users/' + id + '/update');
                        $("input[name='_method']").val('PATCH');
                        $('#id').val(id);
                        $('#name').val(response.data.name);
                        $('#email').val(response.data.email);
                        $('#username').val(response.data.username);
                        $('#role').val(response.data.role_id);
                        $('#save').remove();
                        $('#submit').append(update);
                        console.log(response.data.role_id);
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

        let table = $('#users').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            order: [[0, 'asc']],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'username', name: 'username'},
                {data: 'rol', name: 'rol'},
                {data: 'actions', name: 'actions'},
            ]
        });

        $('#newUser').on('hidden.bs.modal', function () {
            $('#name').val('');
            $('.modal-title').html('Utilizator nou');
            $('#newUserForm').attr('action', '/users/add');
            $("input[name='_method']").val('POST');
            $('#update').remove();
            $('#save').remove();
        });
    })

    </script>
@stop

