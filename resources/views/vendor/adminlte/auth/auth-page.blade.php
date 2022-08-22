@extends('adminlte::master')

@php( $home_url = View::getSection('home_url') ?? config('adminlte.home_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $home_url = $home_url ? route($home_url) : '' )
@else
    @php( $home_url = $home_url ? url($home_url) : '' )
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body'){{ ($auth_type ?? 'login') . '-page' }}@stop

@section('body')
    <div class="{{ $auth_type ?? 'login' }}-box bg-white">

        {{-- Card Box --}}
        <div class="card {{ config('adminlte.classes_auth_card', 'card-primary') }} col-md-12">

            {{-- Logo --}}
            <div class="{{ $auth_type ?? 'login' }}-logo">
                <a href="{{ $home_url }}">
                    <img src="{{ asset(config('adminlte.logo_img')) }}">

                </a>
            </div>

            {{-- Card Header --}}
            @hasSection('auth_header')
                <div class="card-header {{ config('adminlte.classes_auth_header', '') }}">
                    <h1 class="card-title float-none text-center text-uppercase">
                        @yield('auth_header')
                    </h1>
                </div>
            @endif

            {{-- Card Body --}}
            <div class="card-body {{ $auth_type ?? 'login' }}-card-body {{ config('adminlte.classes_auth_body', '') }}">
                @yield('auth_body')
            </div>

            {{-- Card Footer --}}
            @hasSection('auth_footer')
                <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
                    @yield('auth_footer')
                </div>
            @endif

        </div>

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
