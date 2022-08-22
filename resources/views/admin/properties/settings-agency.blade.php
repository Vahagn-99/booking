@extends('adminlte::page')

@section('title', ' | ' . __($title) )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __($title) !!}
        <div class="float-right">
            <a href="{{ !$subdomain ? route('property', ['id' => $property->id]) : route('agency-property', ['id' => $property->id,'subdomain' => $subdomain]) }}" class="btn btn-sm btn-primary" target="_blank">{!! __('messages.View page') !!}</a>
            <a href="{{ route('admin/calendar', ['id' => $property->id]) }}" class="btn btn-sm btn-success" title="{!! __('messages.Open individual calendar') !!}"><i class="far fa-calendar-alt"></i> {!! __('messages.Open calendar') !!}</a>
        </div>
    </h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#rates">{!! __('messages.Rates') !!}</a>
            </li>
             <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#ical">{!! __('messages.iCal') !!}</a>
            </li>
        </ul>
        <div class="tab-content pt-3">
            <div id="rates" class="tab-pane active">
                @include('admin.properties.partials._rates-agency')
            </div>
            <div id="ical" class="tab-pane list-propertyfade">
                @include('admin.properties.partials._ical')
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="stopAgency" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{!! __('messages.Stop to work with agency') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete-agency-prop') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="">
                    <h5>{!! __('messages.Are you sure you want to stop to work with agency?') !!}</h5>
                    <input type="hidden" name="property" value="" id="prop_id">
                    <input type="hidden" name="id" value="" id="data_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-danger">{!! __('messages.Submit') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop
