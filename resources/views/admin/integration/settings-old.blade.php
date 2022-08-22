@extends('adminlte::page')

@section('title', ' | ' . __($title) )

@section('content_header')
    <h1 class="m-0 text-dark">{{ __($title) }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('save-group') }}" method="post">
            @csrf
            @if ($group)
                <input type="hidden" name="id" value="{{ $group->id }}">
            @endif

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">{!! __('messages.Name') !!}</label>
                        <input type="text" name="name" value="{{ old('name') ? old('name') : ($group ? $group->name : '') }}" class="form-control @error('name') is-invalid @enderror" id="name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="currency">{{ __('messages.Currency') }}</label>
                        <select name="currency" class="form-control @error('currency') is-invalid @enderror" id="currency">
                            <option value="">{!! __('messages.Choose') !!}...</option>
                            <option value="EUR" {{ old('currency') && old('currency') == "EUR" ? 'selected' : ($group && $group->currency == "EUR" ? 'selected' : '') }}>EUR</option>
                            <option value="USD" {{ old('currency') && old('currency') == "USD" ? 'selected' : ($group && $group->currency == "USD" ? 'selected' : '') }}>US Dollar</option>
                            <option value="RUB" {{ old('currency') && old('currency') == "RUB" ? 'selected' : ($group && $group->currency == "RUB" ? 'selected' : '') }}>Russian Rouble</option>
                            <option value="CAD" {{ old('currency') && old('currency') == "CAD" ? 'selected' : ($group && $group->currency == "CAD" ? 'selected' : '') }}>Canadian Dollar</option>
                            <option value="BRL" {{ old('currency') && old('currency') == "BRL" ? 'selected' : ($group && $group->currency == "BRL" ? 'selected' : '') }}>Brazilian Real</option>
                            <option value="GBP" {{ old('currency') && old('currency') == "GBP" ? 'selected' : ($group && $group->currency == "GBP" ? 'selected' : '') }}>GBP</option>
                        </select>
                        @error('currency')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="min_price">{{ __('messages.Minimum price') }}</label>
                        <input type="text" name="min_price" value="{{ old('min_price') ? old('min_price') : ($group ? $group->min_price : '') }}" class="form-control @error('min_price') is-invalid @enderror" id="min_price">
                        @error('min_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="description">{{ __('messages.Description') }}</label>
                <textarea name="description" rows="8" class="form-control @error('description') is-invalid @enderror" id="description">{{ old('description') ? old('description') : ($group ? $group->description : '') }}</textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="country">{!! __('messages.Country') !!}</label>
                        <input type="text" name="country" value="{{ old('country') ? old('country') : ($group ? $group->country : '') }}" class="form-control @error('country') is-invalid @enderror" id="country">
                        @error('country')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="state">{!! __('messages.State') !!}</label>
                        <input type="text" name="state" value="{{ old('state') ? old('state') : ($group ? $group->state : '') }}" class="form-control @error('state') is-invalid @enderror" id="state">
                        @error('state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="city">{!! __('messages.City') !!}</label>
                        <input type="text" name="city" value="{{ old('city') ? old('city') : ($group ? $group->city : '') }}" class="form-control @error('city') is-invalid @enderror" id="city">
                        @error('city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="zip">{!! __('messages.Zip') !!}</label>
                        <input type="number" name="zip_code" value="{{ old('zip_code') ? old('zip_code') : ($group ? $group->zip_code : '') }}" class="form-control @error('zip_code') is-invalid @enderror" id="zip">
                        @error('zip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address">{!! __('messages.Address') !!}</label>
                        <input type="text" name="address" value="{{ old('address') ? old('address') : ($group ? $group->address : '') }}" class="form-control @error('address') is-invalid @enderror" id="address">
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-7">
                    <div id="map"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="place_lat">{!! __('messages.Latitude') !!}</label>
                                <input type="text" name="latitude" value="{{ old('latitude') ? old('latitude') : ($group ? $group->latitude : '') }}" class="form-control @error('latitude') is-invalid @enderror" id="place_lat">
                                @error('latitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="place_lng">{!! __('messages.Longitude') !!}</label>
                                <input type="text" name="longitude" value="{{ old('longitude') ? old('longitude') : ($group ? $group->longitude : '') }}" class="form-control @error('longitude') is-invalid @enderror" id="place_lng">
                                @error('longitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <h5>{{ __('messages.Assign properties to group') }}</h5>
            <div class="over-block over-block-large mb-3">
                @foreach ($properties as $key => $value)
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="properties_{{ $value }}" name="properties[]" value="{{ $value }}"
                            {{ old('properties') && in_array($value,old('properties')) ? 'checked' : ($group && in_array($value,$group->properties->pluck('rooom_inner_iden')->toArray()) ? 'checked' : '') }}>
                        <label class="custom-control-label" for="properties_{{ $value }}">{{ $key }}</label>
                    </div>
                @endforeach
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
            </div>
        </form>
    </div>
</div>
@stop
