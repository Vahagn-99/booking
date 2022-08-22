@extends('adminlte::page')

@section('title', ' | ' . __('messages.Profile Settings') )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Profile Settings') !!}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('save-profile') }}" method="post">
            @csrf

            @include('admin.profile._personal')

            @include('admin.profile._business')

            <div class="row mb-5">
                <div class="col-md-6">
                    @include('admin.profile._work-schedule')
                </div>
                <div class="col-md-6">
                    @include('admin.profile._paypal-stripe')
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">{!! __('messages.Submit') !!}</button>
            </div>
        </form>
    </div>
</div>
@stop
