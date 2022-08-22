@extends('adminlte::page')

@section('title', ' | ' . __('messages.Calendar') . ' - ' . $property->name )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Calendar') . ' - <small><a href="'.route('admin/property-settings', ['id' => $property->id]).'">' . $property->name . '</a></small>' !!}
@stop

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <select class="form-control properies-select" onchange="goCalendar(this)">
            @foreach ($user->properties()->get() as $p)
                <option value="{{ $p->id }}" data-link="{{ route('admin/calendar',['id' => $p->id]) }}" {{ $property->id == $p->id ? 'selected' : '' }}>{!! $p->name !!}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 text-right">
        <a class="btn btn-primary" href="{{ route('admin/calendar') }}" role="button">{!! __('messages.Open calendar with all properties') !!}</a>
    </div>
</div>
<div class="card">
    <div class="card-body calendar-property">
        <div class="row align-items-center mb-3">
            <div class="col-sm-12">
                <button class="btn btn-primary" type="button" id="prevPeriod">{!! __('messages.Previous period') !!}</button>
                <button class="btn btn-primary" type="button" id="nextPeriod">{!! __('messages.Next period') !!}</button>
                <button onclick="openCalendarForm(this)" class="float-right date-remove btn btn-danger ml-2" data-property="{{$property->id}}" type="button">{!! __('messages.Remove Closed Dates') !!}</button>
                <button onclick="openCalendarForm(this)" class="float-right date-empty-wrapper btn btn-warning" data-property="{{$property->id}}" type="button">{!! __('messages.Date editing') !!}</button>
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

        <div id="propCalendar">
            @include('admin.calendar.partials._calendar')
        </div>
    </div>
</div>

@stop

@push('scripts')
    <script src="{{ asset('js/calendar-admin.js') }}"></script>
@endpush
