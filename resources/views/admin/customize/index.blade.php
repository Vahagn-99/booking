@extends('adminlte::page')

@section('title', ' | ' .  __('messages.Customize Website') )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Customize Website') !!}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if (!$user->is_admin())
            <div class="subdomain-block mb-4">
                @if ($user->subdomain_status == "pending")
                    <h5>{{ $user->first_name }}{!! __('messages., thank you for your request to create your website.<br>Your request will be processed within 2 hours.') !!}</h5>
                @elseif (!$user->subdomain)
                    <h5>{!! __('messages.You can have your own website. Just write the name for subdomain which you want to use.') !!}</h5>
                    <form action="{{ route('save-subdomain') }}" method="post">
                        @csrf
                        @error('subdomain')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                        <div class="form-group d-inline-block">
                            <label>{!! __('messages.Subdomain name') !!}</label>
                            <div class="input-group">
                                <div>
                                    <input type="text" name="subdomain" class="form-control">
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">.{{ config('app.mainURL') }}</span>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{!! __('messages.Send') !!}</button>
                    </form>
                @endif
            </div>
        @endif
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#header">{!! __('messages.Website header') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#footer">{!! __('messages.Website footer') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#locations">{!! __('messages.List of cities on the home page') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#futured">{!! __('messages.Futured properties') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#seo">{!! __('messages.SEO') !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#cancellation">{!! __('messages.Cancellation Policy') !!}</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="header" class="pt-4 tab-pane active">
                @include('admin.customize._header')
            </div>
            <div id="footer" class="pt-4 tab-pane fade">
                @include('admin.customize._footer')
            </div>
            <div id="cancellation" class="pt-4 tab-pane fade">
                @include('admin.customize._cancellation_policy')
            </div>
            <div id="locations" class="pt-4 tab-pane fade">
                @include('admin.customize._locations')
            </div>
            <div id="futured" class="pt-4 tab-pane fade">
                @include('admin.customize._futured')
            </div>
            <div id="seo" class="pt-4 tab-pane fade">
                @include('admin.customize._seo')
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addMenuEl" tabindex="-1" aria-labelledby="addMenuElLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addMenuElLabel">{!! __('messages.Add link') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('save-customize') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="">
                    <input type="hidden" name="name" value="" id="customize_name">
                    <div class="form-group link_type_check">
                        <div class="form-check">
                            <input class="form-check-input" name="link_type" value="city" type="radio" id="customizeCity" checked>
                            <label class="form-check-label" for="customizeCity">{!! __('messages.Link for city') !!}</label>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" name="link_type" value="custom" type="radio" id="customLink">
                            <label class="form-check-label" for="customLink">{!! __('messages.Custom link') !!}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="link_name">{!! __('messages.Link title') !!}</label>
                        <input type="text" name="link_name" value="{{ old('link_name') }}" class="form-control" id="link_name" required>
                    </div>
                    <div class="form-group">
                        <label for="link">{!! __('messages.Link URL') !!}</label>
                        <input type="text" name="link" value="{{ old('link') }}" class="form-control" id="link" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-primary">{!! __('messages.Add link') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addProperty" tabindex="-1" aria-labelledby="addPropertyLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addPropertyLabel">{!! __('messages.Add property') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('save-customize') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="">
                    <input type="hidden" name="name" value="website-home-featured-properties">
                    <div class="form-group">
                        <label for="property_id">{!! __('messages.Select property') !!}</label>
                        <select class="form-control" id="property_id" name="property">
                            @foreach ($propertiesArray as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-primary">{!! __('messages.Add property') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addCity" tabindex="-1" aria-labelledby="addCityLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addCityLabel">{!! __('messages.Add city') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('save-customize') }}" method="post" enctype='multipart/form-data'>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="">
                    <input type="hidden" name="name" value="website-home-city-list">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group w-100">
                                <label for="city_img">{!! __('messages.City photo') !!}</label>
                                <input type="file" name="photo" class="form-control-file" id="city_img">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group w-100">
                                <label for="city_name">{!! __('messages.City') !!}</label>
                                <input type="text" name="city" class="form-control" id="city_name" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-primary">{!! __('messages.Add city') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addphoto" tabindex="-1" aria-labelledby="addPhotoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addPhotoLabel">{!! __('messages.Add slide photo') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('save-slides') }}" method="post" enctype='multipart/form-data'>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group w-100">
                                <label for="photo">{!! __('messages.Slide photo') !!}</label>
                                <input type="file" name="photo" class="form-control-file" id="photo">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-primary">{!! __('messages.Add photo') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="removeEl" tabindex="-1" aria-labelledby="removeElLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="removeElLabel">{!! __('messages.Remove item') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete-customize') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="">
                    <h5>{!! __('messages.Are you sure you want to delete this item?') !!}</h5>
                    <input type="hidden" name="id" value="" id="data_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-danger">{!! __('messages.Remove') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="removeSlide" tabindex="-1" aria-labelledby="removeSlideLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="removeSlideLabel">{!! __('messages.Remove item') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete-slide') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="">
                    <h5>{!! __('messages.Are you sure you want to delete this item?') !!}</h5>
                    <input type="hidden" name="id" value="" id="dataId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-danger">{!! __('messages.Remove') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
