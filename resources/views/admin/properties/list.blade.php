@extends('adminlte::page')

@section('title', ' | ' . __('messages.Properties List'))

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Properties List') !!}</h1>
@stop

@section('content')
<div class="row mb-3">
    <div class="col-md-12 text-right">
        <a class="btn btn-danger" href="{{ route('admin') }}" role="button"><i class="fas fa-plus mr-2"></i>{!! __('messages.Cancel') !!}</a>
    </div>
</div>
<div class="table-grid">
    <table>
        <thead>
            <tr>
                <th>{!! __('messages.Main Image') !!}</th>
                <th>{!! __('messages.Property Name') !!}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($properties as $property)
            <tr>
                <td><img class="list-property" src="{{ $property->main_photo ? $property->main_photo->photo : asset('img/placeholder.jpg') }}" alt="" width="150" height="100"></td>
                <td>{!! $property->name . "<br>" . $property->info() !!}</td>
                <td>
                    @if (!$property->agencyproperties->where('agency',$user->id)->first())
                        <form action="{{ route('save-agencyproperty') }}" method="post">
                            @csrf
                            <input type="hidden" name="property" value="{{ $property->id }}">
                            <input type="hidden" name="agency" value="{{ $user->id }}">
                            <div class="text-right">
                                <button type="submit" class="btn btn-success"><i class="fas fa-edit"></i> {!! __('messages.Add to properties') !!}</button>
                            </div>
                        </form>
                    @else
                        <div class="text-right">
                            <button type="button" class="btn btn-success" disabled><i class="fas fa-check"></i> {!! __('messages.Added to properties') !!}</button>
                        </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $properties->links() }}
</div>
@stop
