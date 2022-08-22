@extends('adminlte::page')

@section('title', ' | ' . __($title) )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __($title) !!}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('save-group') }}" method="post">
            @csrf
            @if ($group)
                <input type="hidden" name="id" value="{{ $group->id }}">
            @endif

            <div class="form-group">
                <label for="name">{!! __('messages.Name') !!}</label>
                <input type="text" name="name" value="{{ old('name') ? old('name') : ($group ? $group->name : '') }}" class="form-control @error('name') is-invalid @enderror" id="name">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <hr>
            <h5>{!! __('messages.Assign properties to group') !!}</h5>
            <div class="over-block over-block-large mb-3">
                @foreach ($properties as $p)
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="properties_{{ $p->id }}" name="properties[]" value="{{ $p->id }}"
                            {{ old('properties') && in_array($p->id,old('properties')) ? 'checked' : ($group && in_array($p->id,$group->rooms()->pluck('property_iden')->toArray()) ? 'checked' : '') }}>
                        <label class="custom-control-label" for="properties_{{ $p->id }}">{!! $p->name !!}</label>
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
