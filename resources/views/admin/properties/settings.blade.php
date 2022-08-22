@extends('adminlte::page')

@section('title', ' | ' . __($title) )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __($title) !!}
        @if ($property)
            <div class="float-right">
                <a href="{{ !$subdomain ? route('property', ['id' => $property->id]) : route('agency-property', ['id' => $property->id,'subdomain' => $subdomain]) }}" class="btn btn-sm btn-primary" target="_blank">{!! __('messages.View page') !!}</a>
                <a href="{{ route('admin/calendar', ['id' => $property->id]) }}" class="btn btn-sm btn-success" title="{!! __('messages.Open individual calendar') !!}"><i class="far fa-calendar-alt"></i> {!! __('messages.Open calendar') !!}</a>
            </div>
        @endif
    </h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if ($property && ($user->is_admin() || $user->id == $property->owner))
            <form action="{{ route('show-property') }}" method="post" class="text-right mb-3">
                @csrf
                <input type="hidden" name="hash" value="">
                <input type="hidden" name="id" value="{{ $property->id }}">
                <input type="hidden" name="show_on_main" value="{{ !$property->show_on_main ? 'yes' : null }}">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="show" onchange="this.form.submit()" {{ $property->show_on_main ? 'checked' : '' }}>
                    <label class="custom-control-label" for="show">{!! __('messages.Show on main website') !!}</label>
                </div>
            </form>
        @endif
        @if ($property && ($user->is_admin() || $user->id == $property->owner) && $property->agencywish->count() == 0 && $property->agencyproperties->count() == 0)
            <div class="alert alert-info" role="alert">
                <div class="container-fluid px-0">
                    <div class="row mx-0">
                        <div class="col-12 px-0">
                            <strong>{!! __('messages.Dear') !!}, {{ $user->first_name }}</strong>. {!! __('messages.Do you want to suggest property') !!} "{!! $property->name !!}" {!! __('messages.for agencies') !!}?
                        </div>
                        <form action="{{ route('suggest-agency') }}" method="post">
                            @csrf
                            <input type="hidden" name="hash" value="#owner">
                            <input type="hidden" name="property" value="{{ $property->id }}">
                            <div class="px-0 mt-2">
                                <button type="submit" class="btn btn-success">{!! __('messages.Accept') !!}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        @if($errors->any())
            {!! implode('', $errors->all('<p class="text-danger mb-0">:message</p>')) !!}
        @endif
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#base">{!! __('messages.Base') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#description">{!! __('messages.Description') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#location">{!! __('messages.Location') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#amenities">{!! __('messages.Amenities') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#photos">{!! __('messages.Photos') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#rates">{!! __('messages.Rates') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#rental_agreement">{!! __('messages.Rental Agreement') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#rental_cancellation">{!! __('messages.Rental Cancellation Policy') !!}</a>
            </li>
             <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#ical">{!! __('messages.iCal') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#seo">{!! __('messages.SEO') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#owner">{!! __('messages.Owner') !!}</a>
            </li>
        </ul>
        <form action="{{ route('save-property') }}" method="post">
            @csrf
            @if ($property)
                <input type="hidden" name="id" value="{{ $property->id }}">
                <input type="hidden" name="show_on_main" value="{{ $property->show_on_main }}">
            @endif
            <input type="hidden" name="hash" value="">
            <div class="tab-content pt-3">
                <div id="base" class="tab-pane active">
                    @include('admin.properties.partials._base')
                </div>
                <div id="description" class="tab-pane fade">
                    @include('admin.properties.partials._description')
                </div>
                <div id="location" class="tab-pane fade">
                    @include('admin.properties.partials._location')
                </div>
                <div id="amenities" class="tab-pane fade">
                    @include('admin.properties.partials._amenities')
                </div>
                <div id="photos" class="tab-pane fade">
                    @include('admin.properties.partials._photos')
                </div>
                <div id="rates" class="tab-pane fade">
                    @include('admin.properties.partials._rates')
                </div>
                <div id="rental_agreement" class="tab-pane fade">
                    @include('admin.properties.partials._rental_agreement')
                </div>
                <div id="rental_cancellation" class="tab-pane fade">
                    @include('admin.properties.partials._rental_cancellation')
                </div>
                <div id="ical" class="tab-pane fade">
                    @include('admin.properties.partials._ical')
                </div>
                <div id="seo" class="tab-pane fade">
                    @include('admin.properties.partials._seo')
                </div>
                <div id="owner" class="tab-pane fade">
                    @include('admin.properties.partials._owner')
                </div>
            </div>
        </form>
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
