@extends('layouts.app-inner')

@section('content')
<div class="container pt-5 pb-3">
    <div class="row mx-0">
        <div class="col-8 mx-auto pl-0 pr-2">
            <div class="card">
                <div class="card-header">
                    <h2>{!! __('messages.Your payment is canceled') !!}.</h2>
                </div>
                <div class="card-body">
                    <p class="lead"><small>{!! __('messages.Team') !!} {!! config('app.name') !!}</small></p>
                    <a class="btn btn-success" href="{{ url('/') }}" role="button">{!! __('messages.Go back to main page') !!}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
