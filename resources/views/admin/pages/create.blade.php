@extends('adminlte::page')

@section('title', ' | ' . __('messages.Page') )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Page') !!}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('save-page') }}" method="post">
            @csrf
            @if($page)
                <input type="hidden" name="id" value="{{ $page->id }}">
            @endif
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#page_en">{!! __('messages.En') !!}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#page_fr">{!! __('messages.Fr') !!}</a>
                </li>
            </ul>
            <div class="tab-content pt-3">
                <div id="page_en" class="tab-pane active">
                    <div class="form-group">
                        <label for="name">{!! __('messages.Title') !!}</label>
                        <input type="text" name="title" value="{!! old('title') ? old('title') : ($page && $page->title ? $page->title : '') !!}" class="form-control @error('title') is-invalid @enderror" id="title">
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">{!! __('messages.Description') !!}</label>
                        <textarea name="content" class="form-control editor" rows="10">{!! old('content') ? old('content') : ($page && $page->content ? $page->content : '') !!}</textarea>
                        @error('content')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div id="page_fr" class="tab-pane fade">
                    <div class="form-group">
                        <label for="title_fr">{!! __('messages.Title') !!}</label>
                        <input type="text" name="title_fr" value="{!! old('title_fr') ? old('title_fr') : ($page && $page->title_fr ? $page->title_fr : '') !!}" class="form-control @error('title_fr') is-invalid @enderror" id="title_fr">
                        @error('title_fr')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content_fr">{!! __('messages.Description') !!}</label>
                        <textarea name="content_fr" class="form-control editor" rows="10">{!! old('content_fr') ? old('content_fr') : ($page && $page->content_fr ? $page->content_fr : '') !!}</textarea>
                        @error('content_fr')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
            </div>
        </form>
    </div>
</div>
@stop
