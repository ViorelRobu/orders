@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Articole</h1>
        </div>
        <div class="col-lg-6">
            <a href="" class="btn btn-primary float-right" id="addNew" data-toggle="modal" data-target="#newArticle">Articol nou</a>
        </div>
    </div>
@stop

@include('articles.partials.form')

@section('content')
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
                        <td>Finisaje</td>
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
                        $('#default_refinements').val(response.data.refinements_arr).trigger('change');
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

    $(document).ready(function() {


        $('#addNew').click(function() {
            $('#submit').append(save);
            $('#update').remove();
        });

        $('#default_refinements').select2({
            width: '100%',
            tags: true,
            tokenSeparators: [','],
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
                {data: 'default_refinements', name: 'default_refinements'},
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
            let default_refinements = $('#default_refinements').val();
            let thickness = $('#thickness').val();
            let width = $('#width').val();
            axios.post('/articles/add', {
                name, species_id, quality_id, product_type_id, default_refinements, thickness, width
            }).then(function(response) {
                $('#newArticle').modal('hide');
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
            let uri = '/articles/' + id + '/update';
            let name = $('#name').val();
            let species_id = $('#species_id').val();
            let quality_id = $('#quality_id').val();
            let product_type_id = $('#product_type_id').val();
            let default_refinements = $('#default_refinements').val();
            let thickness = $('#thickness').val();
            let width = $('#width').val();
            axios.post(uri, {
                name, species_id, quality_id, product_type_id, default_refinements, thickness, width,
                _method: 'patch'
            }).then(function(response) {
                $('#newArticle').modal('hide');
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



        $('#newArticle').on('hidden.bs.modal', function () {
            $('#name').val('');
            $('#species_id').val('');
            $('#quality_id').val('');
            $('#product_type_id').val('');
            $('#default_refinements').val('');
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

