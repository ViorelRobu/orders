@extends('adminlte::page')

@section('title', env('APP_NAME'))

@section('content_header')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="m-0 text-dark">Logged in</h1>
        </div>
    </div>
@stop

@section('footer')
    @include('footer')
@stop



