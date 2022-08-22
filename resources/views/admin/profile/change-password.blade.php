@extends('adminlte::page')

@section('title', ' | ' . __('messages.Change Password') )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Change Password') !!}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('save-password') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="password">{!! __('messages.Password') !!}</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">{!! __('messages.Password Confirmation') !!}</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                    </div>
                    <button type="submit" class="btn btn-primary">{!! __('messages.Submit') !!}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
