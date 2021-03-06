@extends('adminlte::page')

@section('title', 'Actualizari')

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

    {{-- start import production --}}
    @can('planificare')
        <div class="card">
            <div class="card-header bg-dark">
                <div class="row">
                    <div class="col-lg-11">
                        <div class="card-title">
                            <h5>
                                Import actualizare productie
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="/import/production/start" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                            <label for="production_file">Selecteaza fisierul cu actualizarea productiei</label>
                            <input type="file" class="form-control-file" name="production_file" id="production_file" placeholder="Alege fisierul de productie">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <br>
                            <button class="btn btn-primary" type="submit">Actualizeaza</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    {{-- end import production --}}

    {{-- start import updated production plan --}}
    <div class="card">
        <div class="card-header bg-dark">
            <div class="row">
                <div class="col-lg-11">
                    <div class="card-title">
                        <h5>
                            Import actualizare plan productie
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="/import/production/plan/start" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                          <label for="production_plan">Selecteaza fisierul cu planificarea productiei</label>
                          <input type="file" class="form-control-file" name="production_plan" id="production_plan" placeholder="Alege fisierul de planificare a productiei">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <br>
                        <button class="btn btn-primary" type="submit">Actualizeaza</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    {{-- end import updated production plan --}}

    {{-- start import deliveries --}}
    @can('planificare')
        <div class="card">
            <div class="card-header bg-dark">
                <div class="row">
                    <div class="col-lg-11">
                        <div class="card-title">
                            <h5>
                                Import actualizare livrari
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="/import/deliveries/start" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                            <label for="deliveries">Selecteaza fisierul cu livrarile</label>
                            <input type="file" class="form-control-file" name="deliveries" id="deliveries" placeholder="Alege fisierul cu livrarile">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <br>
                            <button class="btn btn-primary" type="submit">Actualizeaza</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    {{-- end import deliveries --}}


@stop

@section('footer')
    @include('footer')
@stop

@section('js')
    <script>

    </script>
@stop

