@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
@endif

@section('auth_header', __('messages.Create new account'))

@section('auth_body')
    {!! RecaptchaV3::initJs() !!}
    <form action="{{ $register_url }}" method="post">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="">{!! __('messages.Account type') !!}</label>
            <select id="typeRegistration" class="form-control @error('account_type') is-invalid @enderror"
                    name="account_type">
                <option value="">{!! __('messages.Choose account type') !!}</option>
                <option
                    value="owner" {{ old('account_type') == 'owner' ? 'selected' : '' }}>{!! __('messages.Property owner') !!}</option>
                <option
                    value="agency" {{ old('account_type') == 'agency' ? 'selected' : '' }}>{!! __('messages.Agency') !!}</option>
            </select>
            @error('account_type')
            <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>

        <div class="form-main {{ !old('account_type') || old('account_type') == '' ? 'd-none' : '' }}">

            <div class="agency-name {{ !old('account_type') || old('account_type') != 'agency' ? 'd-none' : '' }}">
                <div class="input-group mb-3">
                    <input type="text" name="agency_name"
                           class="form-control {{ $errors->has('agency_name') ? 'is-invalid' : '' }}"
                           value="{{ old('agency_name') }}" placeholder="{!! __('messages.Agency Name') !!}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>
                    @if($errors->has('agency_name'))
                        <div class="invalid-feedback">
                            <strong>{!! $errors->first('agency_name') !!}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <div class="full-name {{ !old('account_type') || old('account_type') != 'owner' ? 'd-none' : '' }}">
                {{-- First Name field --}}
                <div class="input-group mb-3">
                    <input type="text" name="first_name"
                           class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                           value="{{ old('first_name') }}" placeholder="{!! __('messages.First Name') !!}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>
                    @if($errors->has('first_name'))
                        <div class="invalid-feedback">
                            <strong>{!! $errors->first('first_name') !!}</strong>
                        </div>
                    @endif
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="last_name"
                           class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                           value="{{ old('last_name') }}" placeholder="{!! __('messages.Last Name') !!}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>
                    @if($errors->has('last_name'))
                        <div class="invalid-feedback">
                            <strong>{!! $errors->first('last_name') !!}</strong>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Email field --}}
            <div class="input-group mb-3">
                <input type="text" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email') }}" placeholder="{!! __('adminlte::adminlte.email') !!}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        <strong>{!! $errors->first('email') !!}</strong>
                    </div>
                @endif
            </div>

            {{-- Password field --}}
            <div class="input-group mb-3">
                <input type="password" name="password"
                       class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="{{ __('adminlte::adminlte.password') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        <strong>{!! $errors->first('password') !!}</strong>
                    </div>
                @endif
            </div>

            {{-- Confirm password field --}}
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation"
                       class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                       placeholder="{{ __('adminlte::adminlte.retype_password') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
                @if($errors->has('password_confirmation'))
                    <div class="invalid-feedback">
                        <strong>{!! $errors->first('password_confirmation') !!}</strong>
                    </div>
                @endif
            </div>
        </div>
        {{----}}
        <div class="input-group mb-3 {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
            {!! RecaptchaV3::field('register') !!}
            @if ($errors->has('g-recaptcha-response'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                </div>
            @endif
        </div>

        {{-- Register button --}}
        <button type="submit"
                {{ !old('account_type') || old('account_type') == '' ? 'disabled' : '' }} class="btn btn-block btn-register {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-user-plus"></span>
            {{ __('adminlte::adminlte.register') }}
        </button>

    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ $login_url }}">
            {!! __('messages.Log into account') !!}
        </a>
    </p>
@stop
