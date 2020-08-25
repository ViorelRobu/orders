@extends('adminlte::page')

@section('title', 'Arhiva export rapoarte')

@section('content')

    <div class="card">
        <div class="card-header bg-dark">
            <div class="row">
                <div class="col-lg-11">
                    <div class="card-title">
                        <div class="row">
                            <h5>
                                Arhiva export rapoarte
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="docArchives" class="table table-bordered table-hover">
                <thead>
                <td>Nr crt</td>
                <td>Document</td>
                <td>Data/ora export</td>
                <td>Utilizator</td>
                <td>Export</td>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('footer')
    @include('footer')
@stop

@section('js')
    <script>
        let table = $('#docArchives').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('archive.fetch') }}",
            order: [[1, 'asc']],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'document', name: 'document'},
                {data: 'created_at', name: 'created_at'},
                {data: 'user_id', name: 'user_id'},
                {data: 'export', name: 'export'},
            ]
        });
    </script>
@stop

