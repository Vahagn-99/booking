@extends('layouts.app-inner')

@section('content')

<div class="container-fluid pt-5 pb-3">
    <div class="row mx-0">
        <div class="col-lg-10 px-0 mx-auto">
            @if($content)
                <h1 class="font-weight-bold text-center mb-3">{{ $content->title }}</h1>
                {!! $content->content !!}
            @endif
        </div>
    </div>
</div>

@stop
