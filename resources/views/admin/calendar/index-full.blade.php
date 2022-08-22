@extends('adminlte::page')

@section('title', ' | ' . __('messages.Calendar') )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Calendar') !!}</h1>
@stop

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <form action="{{ url()->full() }}" method="get">
            <div class="row mx-0 align-items-center">
                <div class="col-8 px-0">
                    <input type="text" name="query" class="form-control" placeholder="{!! __('messages.Search property') !!}" value="{{ \Request::has('query') ? \Request::get('query') : '' }}">
                </div>
                <div class="col-2 px-0">
                    <input type="submit" class="btn btn-primary btn-block" value="{!! __('messages.Search') !!}">
                </div>
                <div class="col-2 px-0">
                    <a href="{{ url()->current() }}" class="btn btn-warning btn-block">{!! __('messages.Reset') !!}</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6 text-right open-calendar">
        @if (count($properties) > 0)
            <a class="btn btn-primary" href="{{ route('admin/calendar',['id' => $properties[0]->id]) }}" role="button">{!! __('messages.Open calendar per property') !!}</a>
        @endif
        <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#choosePropertiesModal" role="button">{!! __('messages.Choose properties') !!}</a>
    </div>
</div>
<div class="card">
    <div class="card-body calendar-full">
        <div class="row mx-0 align-items-center mb-3">
            <div class="col-md-12 px-0">
                <div class="row mx-0 align-items-center period-range">
                    <button class="btn btn-primary" id="prevPeriod" type="button">{!! __('messages.Previous period') !!}</button>
                    <div class="col-6 px-0">
                        <input id="calendarDateToday" type="text" class="form-control datepicker" readonly>
                    </div>
                    <button class="btn btn-primary" id="nextPeriod" type="button">{!! __('messages.Next period') !!}</button>
                </div>
            </div>
        </div>
        <div class="hint-box">
            <div class="row align-items-center mb-3">
                <div class="col-sm-3">
                    <span class="bg-primary"></span><i>{!! __('messages.Reservations') !!}</i>
                </div>
                <div class="col-sm-3">
                    <span class="bg-info"></span><i>{!! __('messages.Reservations from agency') !!}</i>
                </div>
                <div class="col-sm-3">
                    <span class="bg-danger"></span><i>{!! __('messages.Pending reservations') !!}</i>
                </div>
                <div class="col-sm-3">
                    <span class="bg-warning"></span><i>{!! __('messages.Closed dates') !!}</i>
                </div>
            </div>
        </div>

        <div id="propCalendars">
            @include('admin.calendar.partials._calendar-full')
        </div>

        {{ $properties->onEachSide(1)->links() }}
    </div>
</div>

@include('admin.calendar.ajax-modals._choose-properties')

@stop

@push('scripts')
    <script src="{{ asset('js/calendar-admin.js') }}"></script>
@endpush
