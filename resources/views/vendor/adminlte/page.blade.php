<?php
if (Auth::user()->is_admin()) {
    $menu = [
        [
            'text' => __('messages.Properties'),
            'url' => 'admin',
            'icon' => 'fas fa-fw fa-hotel',
        ],
        [
            'text' => __('messages.Calendar'),
            'url' => 'admin/calendar',
            'icon' => 'fas fa-fw fa-calendar',
        ],
        [
            'text' => __('messages.Integration'),
            'url' => 'admin/integration',
            'icon' => 'fas fa-fw fa-pen',
        ],
        [
            'text' => __('messages.Notifications'),
            'url' => 'admin/notifications',
            'icon' => 'fas fa-fw fa-bell',
        ],
        [
            'text' => __('messages.Users'),
            'url' => 'admin/users',
            'icon' => 'fas fa-fw fa-users',
        ],
        [
            'text' => __('messages.Customize Website'),
            'url' => 'admin/customize',
            'icon' => 'fas fa-fw fa-cogs',
        ],
        [
            'text' => __('messages.Pages'),
            'url' => 'admin/pages',
            'icon' => 'fas fa-fw fa-file',
        ],
        [
            'text' => __('messages.Report'),
            'url' => 'admin/report',
            'icon' => 'fas fa-fw fa-file',
        ],
    ];
} else {
    $menu = [
        [
            'text' => __('messages.Properties'),
            'url' => 'admin',
            'icon' => 'fas fa-fw fa-hotel',
        ],
        [
            'text' => __('messages.Calendar'),
            'url' => 'admin/calendar',
            'icon' => 'fas fa-fw fa-calendar',
        ],
        [
            'text' => __('messages.Customize Website'),
            'url' => 'admin/customize',
            'icon' => 'fas fa-fw fa-cogs',
        ],
        [
            'text' => __('messages.Pages'),
            'url' => 'admin/pages',
            'icon' => 'fas fa-fw fa-file',
        ],
    ];
    if (auth()->user()->account_type == 'owner') {
        array_push($menu,[
            'text'        => __('messages.Report'),
            'url'         => 'admin/report',
            'icon' => 'fas fa-fw fa-file',
        ]);
    }
}
$menu [] = ['header' => 'account_settings'];
$menu [] = [
    'text' => __('messages.Profile'),
    'url' => 'admin/settings',
    'icon' => 'fas fa-fw fa-user',
];
$menu [] = [
    'text' => __('messages.Change Password'),
    'url' => 'admin/password',
    'icon' => 'fas fa-fw fa-lock',
];
Config::set('adminlte.menu', $menu);
?>

@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@if($layoutHelper->isLayoutTopnavEnabled())
    @php( $def_container_class = 'container' )
@else
    @php( $def_container_class = 'container-fluid' )
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        <div class="content-wrapper {{ config('adminlte.classes_content_wrapper') ?? '' }}">

            {{-- Content Header --}}
            @hasSection('content_header')
                <div class="content-header">
                    <div class="{{ config('adminlte.classes_content_header') ?: $def_container_class }}">
                        @yield('content_header')
                    </div>
                </div>
            @endif

            {{-- Main Content --}}
            <div class="content">
                <div class="{{ config('adminlte.classes_content') ?: $def_container_class }}">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Success!</strong> {{ session('success') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Warning!</strong> {{ session('warning') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>

        </div>

        {{-- Footer --}}
        @include('adminlte::partials.footer.footer')

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

        <div class="modal-container"></div>

    </div>
    <div class="loader" style="display: none;"><img src="{{ asset('img/loader.gif') }}" alt="load"></div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
