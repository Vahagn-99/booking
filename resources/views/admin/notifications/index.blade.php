@extends('adminlte::page')

@section('title', ' | ' . __('messages.Notifications') )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Notifications from agencies') !!}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if(count($agencyask ) > 0)
            @foreach ($agencyAsk as $a)
                <div class="row mx-0">
                    <div class="col-md-12 px-0">
                        <h5>{!! __('messages.Agency') !!} "{{ $a->agencyInfo->agency_name }}" {!! __('messages.has asked for adding property') !!}
                            <a href="property-settings.php?id= {{ $a->property }}">"{{ $a->propertyInfo->name }} "</a>
                            {!! __('messages.to their properties list') !!}
                        </h5>
                    </div>
                    <div class="col-md-12 px-0">
                        <button class="btn btn-success confirmAgencyAsking" data-property="{{ $a->property }}" data-agency="{{ $a->agency }}" type="button">{!! __('messages.Accept') !!}</button>
                        <button class="btn btn-danger declineAgencyAsking" data-property="{{ $a->property }}" data-agency="{{ $a->agency }}" type="button">{!! __('messages.Decline') !!}</button>
                    </div>
                </div>
                <hr>
            @endforeach
        @else
            <h4>{!! __('messages.No requests have not come from agencies yet') !!}</h4>
        @endif
    </div>
</div>
@stop
