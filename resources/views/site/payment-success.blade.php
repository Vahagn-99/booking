@extends('layouts.app-inner')

@section('content')
<div class="container pt-5 pb-3">
    <div class="row mx-0">
        <div class="col-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h2>{!! __('messages.Successful payment') !!}</h2>
                </div>
                <div class="card-body">
                    <h5 class="mb-2">{!! __('messages.Thank you for choosing') !!} "{!! \Session::has('propertyName') ? \Session::get('propertyName') : config('app.name') !!}"</h5>
                    <p class="lead">{!! __('messages.We are pleased to welcome you as soon as possible') !!}.</p>
                    <p class="lead"><small>{!! __('messages.Sunny Regards') !!}, <br>{!! __('messages.Team') !!} {!! config('app.name') !!}</small></p>
                    <a class="btn btn-main" href="{{ url('/') }}" role="button">{!! __('messages.Go back to main page') !!}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
