@extends('adminlte::page')

@section('title', ' | ' .  __('messages.Edit user'))

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Edit user') !!}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h4>{!! __('messages.User') !!} #{{ $user->id }}</h4>
                <div class="list-group mb-3">
                  <p class="mb-0 list-group-item list-group-item-action">{!! __('messages.Full name') !!}: <i>{!! $user->fullName() !!}</i></p>
                  <p class="mb-0 list-group-item list-group-item-action">{!! __('messages.Email') !!}: <i>{{ $user->email }}</i></p>
                </div>

                <form action="{{ route('save-user') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="form-group">
                        <label for="subdomain">{!! __('messages.Subdomain') !!} {{ $user->subdomain_status ? '('.$user->subdomain_status.')' : '' }}</label>
                        <div class="input-group">
                            <div>
                                <input type="text" name="subdomain" value="{{ old('subdomain') ? old('subdomain') : $user->subdomain }}" class="form-control @error('subdomain') is-invalid @enderror" id="subdomain">
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">.{{ config('app.mainURL') }}</span>
                            </div>
                        </div>
                        @error('subdomain')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="subdomain">{!! __('messages.Subdomain') !!} {{ $user->subdomain_status ? '('.$user->subdomain_status.')' : '' }}</label>
                        <select class="form-control mb-2" name="subdomain_status">
                            <option value="delete">{!! __('messages.Deactivate subdomain') !!}</option>
                            <option value="active" {{ old('subdomain_status') && old('subdomain_status') == "active" ? "selected" : ($user->subdomain_status == "active" ? "selected" : "") }}>{!! __('messages.Active') !!}</option>
                            <option value="pending" {{ old('subdomain_status') && old('subdomain_status') == "pending" ? "selected" : ($user->subdomain_status == "pending" ? "selected" : "") }}>{!! __('messages.Pending') !!}</option>
                        </select>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">{!! __('messages.Save') !!}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <h4>{!! __('messages.Properties') !!}: {{ $user->propertiesCount() }}</h4>
                <div class="list-group list-of-properties-admin">
                    @foreach ($user->properties() as $p)
                        <li class="list-group-item">
                            <a href="{{ route('admin/property-settings', ['id' => $p->id]) }}" class="d-block">{!! $p->name !!}</a>
                        </li>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@stop
