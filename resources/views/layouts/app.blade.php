<!doctype html>
<html lang="{{ session()->get('locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BookingFWI') }}</title>

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $sitemeta && $sitemeta->website_title != "" ? $sitemeta->website_title : 'BookingFWI' }}">
    <meta property="og:title" content="{{ $sitemeta && $sitemeta->title != "" ? $sitemeta->title : 'BookingFWI | The best vacation rentals in St Barts & Saint-Martin' }}">
    <meta property="og:description" content="{{ $sitemeta && $sitemeta->description != "" ? str_replace('<br>', "\n", $sitemeta->description) : 'Are you looking to rent a villa with swimming pool, a village house, an apartment or a room in St Barts, Saint-Martin , Sint Maarten? All year round, we have the vacation rental' }}">
    <meta property="og:url" content="{{ $mainUrl }}">
    <meta property="og:image" content="{{ asset($logo) }}">
    <meta name="twitter:title" content="{{ $sitemeta && $sitemeta->title != "" ? $sitemeta->title : 'BookingFWI | The best vacation rentals in St Barts & Saint-Martin' }}">
    <meta name="twitter:description" content="{{ $sitemeta && $sitemeta->description != "" ? str_replace('<br>', "\n", $sitemeta->description) : 'Are you looking to rent a villa with swimming pool, a village house, an apartment or a room in St Barts, Saint-Martin , Sint Maarten? All year round, we have the vacation rental' }}">
    <meta name="twitter:image:src" content="{{ asset($logo) }}">
    <meta name="twitter:url" content="{{ $mainUrl }}">
    <meta name="twitter:domain" content="View website">
    <meta name="name" content="{{ $sitemeta && $sitemeta->title != "" ? $sitemeta->title : 'BookingFWI | The best vacation rentals in St Barts & Saint-Martin' }}">
    <meta name="description" content="{{ $sitemeta && $sitemeta->description != "" ? str_replace('<br>', "\n", $sitemeta->description) : 'Are you looking to rent a villa with swimming pool, a village house, an apartment or a room in St Barts, Saint-Martin , Sint Maarten? All year round, we have the vacation rental' }}">
    <meta name="image" content="{{ asset($logo) }}">
    <meta name="keywords" content="{{ $sitemeta && $sitemeta->keywords }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset($favicon) }}"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/fontawesome/css/all.min.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2QXN47Y75Q"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-2QXN47Y75Q');
    </script>

    <link rel="stylesheet" href="https://unpkg.com/swiper@6.5.0/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper@6.5.0/swiper-bundle.min.js"></script>

    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <header class="fixed-top">
            <nav class="navbar navbar-expand-lg navbar-dark bg-transparent" id="home-menu">
                <a class="navbar-brand" href="{{ $mainUrl }}">
                    <img src="{{ asset($logo) }}" alt="Logo" loading="lazy">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#headerMenu" aria-controls="headerMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="headerMenu">
                    <ul class="navbar-nav ml-auto align-items-center">
                        @if (isset($menu))
                            @foreach ($menu as $item)
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold" href="{{ $item->link_type == 'custom' ? $item->link : '/locations?city=' . $item->link_name }}">{!! $item->link_name !!}</a>
                                </li>
                            @endforeach
                        @endif
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" href="{{ url('/contact') }}">Contact</a>
                        </li>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link font-weight-bold" href="{{ route('login') }}">{!! __('messages.Log in') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-weight-bold" href="{{ route('register') }}">{!! __('messages.Sign up') !!}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link font-weight-bold" href="{{ route('admin') }}">{!! __('messages.Dashboard') !!}</a>
                            </li>
                        @endguest

                        <li class="nav-item">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle font-weight-bold" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ \Cookie::has('currency') ? \Cookie::get('currency') : 'EUR'}}
                                </button>
                                <div class="dropdown-menu dropdown-menu-lg-right dropdown-menu-left" aria-labelledby="currencyChange">
                                    <form action="{{ route('change-currency') }}" method="post">
                                        @csrf
                                        <button name="currency" value="EUR" type="submit" class="dropdown-item">EUR</button>
                                        <button name="currency" value="USD" type="submit" class="dropdown-item">US Dollar (USD)</button>
                                        <button name="currency" value="RUB" type="submit" class="dropdown-item">Russian Rouble (RUB)</button>
                                        <button name="currency" value="CAD" type="submit" class="dropdown-item">Canadian Dollar (CAD)</button>
                                        <button name="currency" value="BRL" type="submit" class="dropdown-item">Brazilian Real (BRL)</button>
                                        <button name="currency" value="GBP" type="submit" class="dropdown-item">British Pound (GBP)</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown lang">
                                <button class="btn dropdown-toggle font-weight-bold" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ session()->get('locale') ? session()->get('locale') : 'EN'}}
                                </button>
                                <div class="dropdown-menu dropdown-menu-lg-right dropdown-menu-left">
                                    <form action="{{ route('changeLang') }}" method="get">
                                        @csrf
                                        <button name="lang" value="en" type="submit" class="dropdown-item">En</button>
                                        <button name="lang" value="fr" type="submit" class="dropdown-item">FR</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

        </header>

        @if (session('error'))
            <div class="container-fluid p-3">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')

        <footer>
            <div class="container-fluid">
                <div class="row mx-0">
                    <div class="col-12 px-0">
                        <div class="row mx-0">
                            <div class="col-12 col-sm-12 col-md-4 pl-md-0 pr-md-2 px-sm-0 px-0">
                                @if (isset($sitefooter) && $sitefooter->first_section_text != "")
                                    <p class="pre-wrap">{!! session()->get('locale') == 'fr' ? $sitefooter->first_section_text_fr : $sitefooter->first_section_text !!}</p>
                                @endif
                                <div class="dropdown">
                                    <button id="currencyChange" class="btn dropdown-toggle font-weight-bold" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ \Cookie::has('currency') ? \Cookie::get('currency') : 'EUR'}}
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-lg-right dropdown-menu-left" aria-labelledby="currencyChange">
                                        <form action="{{ route('change-currency') }}" method="post">
                                            @csrf
                                            <button name="currency" value="EUR" type="submit" class="dropdown-item">EUR</button>
                                            <button name="currency" value="USD" type="submit" class="dropdown-item">US Dollar (USD)</button>
                                            <button name="currency" value="RUB" type="submit" class="dropdown-item">Russian Rouble (RUB)</button>
                                            <button name="currency" value="CAD" type="submit" class="dropdown-item">Canadian Dollar (CAD)</button>
                                            <button name="currency" value="BRL" type="submit" class="dropdown-item">Brazilian Real (BRL)</button>
                                            <button name="currency" value="GBP" type="submit" class="dropdown-item">British Pound (GBP)</button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12 col-sm-12 mt-2 mt-sm-2 mt-md-0 col-md-4 px-md-2 px-sm-0 px-0">
                                @if (isset($sitefooter) && $sitefooter->second_section_text != "")
                                    <p class="pre-wrap">{!! session()->get('locale') == 'fr' ? $sitefooter->second_section_text_fr : $sitefooter->second_section_text !!}</p>
                                @endif
                                @if (isset($sitefooter) && $sitefooter->skype != "")
                                    <p><i class="fab fa-skype mr-2"></i>{{ $sitefooter->skype }}</p>
                                @endif
                                @if (isset($sitefooter) && $sitefooter->phone_number != "")
                                    <p><i class="fas fa-phone mr-2"></i>{{ $sitefooter->phone_number }}</p>
                                @endif
                                @if (isset($sitefooter) && $sitefooter->email != "")
                                    <p>
                                        <a href="mailto:{{ $sitefooter->email }}"><i class="far fa-envelope mr-2"></i>{{ $sitefooter->email }}</a>
                                    </p>
                                @endif
                                @if (isset($sitefooter) && $sitefooter->website_link != "")
                                    <p>
                                        <a href="/"><i class="fas fa-desktop mr-2"></i>{{ $sitefooter->website_link }}</a>
                                    </p>
                                @endif
                                @if (isset($sitefooter) && ($sitefooter->facebook_link != "" || $sitefooter->twitter_link != "" || $sitefooter->pinterest_link != "" || $sitefooter->instagram_link != ""))
                                    <ul>
                                        @if ($sitefooter->facebook_link != "")
                                            <li><a href="{{ $sitefooter->facebook_link }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                        @endif
                                        @if ($sitefooter->twitter_link != "")
                                            <li><a href="{{ $sitefooter->twitter_link }}" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                        @endif
                                        @if ($sitefooter->pinterest_link != "")
                                            <li><a href="{{ $sitefooter->pinterest_link }}" target="_blank"><i class="fab fa-pinterest-p"></i></a></li>
                                        @endif
                                        @if ($sitefooter->instagram_link != "")
                                            <li><a href="{{ $sitefooter->instagram_link }}" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 mt-2 mt-sm-2 mt-md-0 col-md-4 pl-md-2 pr-md-0 px-sm-0 px-0">
                                @if (count($footerMoreInfo) > 0)
                                    <h5>{!! __('messages.MORE INFORMATION') !!}</h5>
                                    <div class="row mx-0">
                                        @foreach ($footerMoreInfo as $moreInfoLinksEl)
                                            <div class="col-md-12 px-0">
                                                <p><a href="{{ $moreInfoLinksEl->link }}">{{ $moreInfoLinksEl->link_name }}</a></p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTFcG_V1xd0aVrQM4MohUz_CuQE2Dctew&libraries=places"></script>
        <script src="{{ asset('js/main.js') }}"></script>
        <script type="text/javascript">
            $(function () {
                $('.datepicker').datepicker({
                    format: "dd.mm.yy",
                    startDate: new Date(),
                    datesDisabled: {!! isset($property) ? $property->notAvailableDates() : "['']" !!},
                    autoclose: true,
                    orientation: 'bottom'
                });
                $('.datepicker.start').datepicker({
                    format: "dd.mm.yy",
                    startDate: new Date(),
                    datesDisabled: {!! isset($property) ? $property->notAvailableDates() : "['']" !!},
                    autoclose: true,
                    orientation: 'bottom'
                }).on('changeDate', function(e) {
                    $('.datepicker.end').datepicker('setStartDate',e.date);
                });
            });
        </script>

        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=true;
                s1.src='https://embed.tawk.to/5fb2fa521535bf152a5696fc/default';
                s1.charset='UTF-8';
                s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0);
            })();

            // var url = "{{ route('changeLang') }}";
            //
            // $(".changeLang").change(function(){
            //     window.location.href = url + "?lang="+ $(this).val();
            // });
        </script>
        <!--End of Tawk.to Script-->
        <div class="loader" style="display: none;"><img src="{{ asset('img/loader.gif') }}" alt="load"></div>
    </body>
</html>
