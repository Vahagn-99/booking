@extends('layouts.app-inner')

@section('content')

<div class="container-fluid pt-5 pb-3">
    <div class="row mx-0">
        <div class="col-lg-10 px-0 mx-auto">
            <h1 class="font-weight-bold text-center mb-3">{!! session()->get('locale') == 'fr' ? ($content->title_fr ? $content->title_fr : $content->title) : $content->title !!}</h1>
            @if($content)
                {!! session()->get('locale') == 'fr' ? ($content->content_fr ? $content->content_fr : $content->content) : $content->content !!}
            @endif
        </div>
    </div>
</div>

@stop
