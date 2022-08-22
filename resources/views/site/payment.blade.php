@extends('layouts.app-inner')

@section('content')

<div class="container-fluid pt-5 pb-3" id="payment-body">
    <div class="row">
        <div class="col-lg-7">
            @include('site.partials.payment-info')
        </div>
        <div class="col-lg-5">
            @include('site.partials.payment-form')
        </div>
    </div>
</div>

@stop
