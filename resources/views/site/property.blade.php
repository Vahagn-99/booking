@extends('layouts.app-inner')

@section('content')

    <div class="container-fluid pt-5 pb-3" id="property-wrapper">
        <div class="row mx-0">
            <div class="col-12 col-lg-8 px-0">
                <h1>{!! session()->get('locale') == 'fr'
                    ? ($property->name_fr
                        ? $property->name_fr
                        : $property->name)
                    : $property->name !!}</h1>
                <p id="property-location-st">
                    {{ $property->country . ', ' . $property->state . ', ' . $property->city . ', ' . $property->address }}
                </p>
                <div class="row mx-0">
                    <div class="col-12 px-0" id="property-images-slider">
                        <div class="swiper-container gallery-top">
                            <div class="swiper-wrapper">
                                @foreach ($property->allphotos as $item)
                                    <div class="swiper-slide swiper-lazy" data-background="{{ asset($item->photo) }}">
                                        @if (strpos($item->photo, '/upload') === false)
                                            @if (strpos($item->photo, 'youtube.com') > 0 || strpos($item->photo, 'youtu.be') > 0)
                                                <div class="video-wrap">
                                                    <iframe
                                                        src="{{ strpos($item->photo, 'youtu.be') > 0 ? str_replace('youtu.be', 'www.youtube.com/embed', $item->photo) : 'https://www.youtube.com/embed/' . substr($item->photo, intval(strpos($item->photo, 'v=')) + 2, 11) }}"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                                                </div>
                                            @else
                                                <video class="w-100">
                                                    <source src="{{ asset($item->photo) }}" type="video/mp4">
                                                </video>
                                            @endif
                                        @else
                                            <img src="{{ asset($item->photo) }}" alt="">
                                            <div class="swiper-lazy-preloader"></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-button-next swiper-button-white"></div>
                            <div class="swiper-button-prev swiper-button-white"></div>
                        </div>
                        <div class="swiper-container gallery-thumbs">
                            <div class="swiper-wrapper">
                                @foreach ($property->allphotos as $item)
                                    <div class="swiper-slide swiper-lazy" data-background="{{ asset($item->photo) }}">
                                        @if (strpos($item->photo, '/upload') === false)
                                            @if (strpos($item->photo, 'youtube.com') > 0 || strpos($item->photo, 'youtu.be') > 0)
                                                <div class="video-thumb">
                                                    <iframe
                                                        src="{{ strpos($item->photo, 'youtu.be') > 0 ? str_replace('youtu.be', 'www.youtube.com/embed', $item->photo) : 'https://www.youtube.com/embed/' . substr($item->photo, intval(strpos($item->photo, 'v=')) + 2, 11) }}"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                                                </div>
                                            @else
                                                <video class="w-100">
                                                    <source src="{{ asset($item->photo) }}" type="video/mp4">
                                                </video>
                                            @endif
                                        @else
                                            <img src="{{ asset($item->photo) }}" alt="">
                                            <div class="swiper-lazy-preloader"></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-12 px-0">
                        <ul id="property-tags" class="py-4 mb-0 border-bottom universal-underline row mx-0">
                            @if ($property->pool_features != '')
                                <li class="px-0 col-6 col-sm-3">
                                    <i class="fas fa-swimming-pool pr-2"></i><span>{!! __('messages.Pool') !!}</span>
                                </li>
                            @endif
                            <li class="col-6 col-sm-3 px-0">
                                <i class="far fa-building pr-2"></i>{!! $property->rentalType->title !!}
                            </li>
                            <li class="col-6 col-sm-3 px-0" id="guest-num">
                                <i class="far fa-user pr-2"></i><span id="max_guests">{{ $property->sleeps_max }}</span>
                            </li>
                            <li class="col-6 col-sm-3 px-0">
                                <i class="fas fa-bed pr-2"></i> {!! $property->beds_count == 1
                                    ? $property->beds_count . ' ' . __('messages.Bedroom')
                                    : $property->beds_count . ' ' . __('messages.Bedrooms') !!}
                            </li>
                            <li class="col-6 col-sm-3 px-0">
                                <img src="{{ asset('img/bathroom.png') }}" alt="baths" class="bathrooms">
                                {!! $property->baths_count == 1
                                    ? $property->baths_count . ' ' . __('messages.Bathroom')
                                    : $property->baths_count . ' ' . __('messages.Bathrooms') !!}
                            </li>
                        </ul>
                    </div>
                </div>
                <h2 class="mt-4 property-description-title">{!! __('messages.Property description') !!}</h2>
                <p class="property-description-subtitle">{!! session()->get('locale') == 'fr' ? $property->headline_fr : $property->headline_en !!}</p>
                <p class="property-description-title">{!! session()->get('locale') == 'fr' ? $property->summary_fr : $property->summary_en !!}</p>
                <p class="property-description-subtitle">
                    <span class="pre-wrap d-block">{!! session()->get('locale') == 'fr' ? $property->small_desc_fr : $property->small_desc_en !!}</span>
                    <a href="#" data-toggle="modal" data-target="#property-description-show"
                        class="more-link font-weight-bold">{!! __('messages.View more') !!}</a>
                </p>
                <h6 class="font-weight-bold">{!! __('messages.Property Price starting at') !!}</h6>
                <div class="row mx-0 pt-2 border-bottom universal-underline">
                    <div class="col-12 col-sm-6 pl-sm-0 pr-sm-2 px-0">
                        <p class="property-list-el-wrapper"><b>{!! __('messages.Price per night') !!}: </b>{!! $property->price_per_night !!}</p>
                        <p class="property-list-el-wrapper"><b>{!! __('messages.City Tax') !!}:
                            </b>{{ $property->city_tax }}{!! __('messages.% of price per night') !!}</p>
                        <p class="property-list-el-wrapper"><b>{!! __('messages.Security deposit') !!}: </b>{!! $property->price_damage_deposit !!}</p>
                    </div>
                    <div class="col-12 col-sm-6 pl-sm-2 pr-sm-0 px-0">
                        @if ($property->cleaning_fee != '')
                            <p class="property-list-el-wrapper"><b>{!! __('messages.Cleaning fee') !!}: </b>{!! $property->price_cleaning_fee !!}
                                {!! __('messages.Single Fee') !!}</p>
                        @endif
                        @if ($property->min_stay_base != '')
                            <p class="property-list-el-wrapper"><b>{!! __('messages.Minimum no of nights') !!}:
                                </b>{{ $property->min_stay_base }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-12 px-0 mt-4">
                    <h6 class="font-weight-bold">{!! __('messages.AVAILABILITY & DAILY RATES') !!} <span
                            style="font-size: 13px;">({!! __('messages.ALL TAXES INCLUDED') !!})</span></h6>
                    @if ($property->periods->count() > 0 || $property->propertybedrooms->count() > 0)
                        <div id="tb-main-template">
                            <div id="tb-main-buttons">
                                <ul class="nav nav-tabs">
                                    @if ($property->periods->count() > 0)
                                        <li id="room0">
                                            <a data-toggle="tab" class="tb-main-button active"
                                                href="#table-room0">{!! $property->beds_min_count . ' ' . __('messages.Bedroom') !!}</a>
                                        </li>
                                    @endif
                                    @foreach ($property->propertybedrooms as $pb)
                                        <li id="room{{ $pb->id }}">
                                            <a data-toggle="tab"
                                                class="tb-main-button @if ($pb->ifHaveAditionalSofaBed()) data-aditional @endif"
                                                href="#table-room{{ $pb->id }}">{!! $pb->bed_name() !!}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div id="tb-main-content" class="tab-content">
                                @if ($property->periods->count() > 0)
                                    <div id="table-room0" class="tab-pane fade in active show">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>{{ __('messages.Season') }}</th>
                                                    <th>{{ __('messages.from') }}</th>
                                                    <th>{{ __('messages.to') }}</th>
                                                    <th>{{ __('messages.Minimum stay') }}</th>
                                                    <th>{{ __('messages.Price') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($property->periods as $period)
                                                    <tr>
                                                        <td>{!! $period->name !!}</td>
                                                        <td>{{ $period->formated_start }}</td>
                                                        <td>{{ $period->formated_end }}</td>
                                                        <td>{{ $period->min_stay }} {!! __('messages.NIGHTS') !!}</td>
                                                        <td class="tb-currency-price">{!! $period->price_period !!}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <p class="mb-1 pre-wrap d-block"><small>{!! session()->get('locale') == 'fr'
                                            ? ($property->pbedrooms_rule_fr
                                                ? $property->pbedrooms_rule_fr
                                                : $property->pbedrooms_rule)
                                            : $property->pbedrooms_rule !!}</small></p>
                                        <div class="tb-main-content-book text-left">
                                            <button type="button" data-pbedroom="1 Bedroom" data-room_id="0"
                                                class="tb-main-content-book-button btn btn-sm btn-success">{!! __('messages.Book now') !!}</button>
                                        </div>
                                    </div>
                                @endif

                                @foreach ($property->propertybedrooms as $pbed)
                                    {{-- @if ($pbed->pricebedrooms->count() > 0) --}}
                                    <div id="table-room{{ $pbed->id }}" class="tab-pane fade">
                                        <table class="table table-stripped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('messages.Season') }}</th>
                                                    <th>{{ __('messages.from') }}</th>
                                                    <th>{{ __('messages.to') }}</th>
                                                    <th>{{ __('messages.Minimum stay') }}</th>
                                                    <th>{{ __('messages.Price') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pbed->pricebedrooms as $pricebedroom)
                                                    <tr>
                                                        <td>{!! $pricebedroom->name !!}</td>
                                                        <td>{{ $pricebedroom->formated_start }}</td>
                                                        <td>{{ $pricebedroom->formated_end }}</td>
                                                        <td>{{ $pricebedroom->min }} {!! __('messages.Nights') !!}</td>
                                                        <td class="tb-currency-price">{!! $pricebedroom->price_period !!}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <p class="mb-1 pre-wrap d-block"><small>{!! session()->get('locale') == 'fr'
                                            ? ($property->pbedrooms_rule_fr
                                                ? $property->pbedrooms_rule_fr
                                                : $property->pbedrooms_rule)
                                            : $property->pbedrooms_rule !!}</small></p>
                                        <div class="tb-main-content-book text-left">
                                            <button type="button" data-pbedroom="{!! $pbed->bed_name() !!}"
                                                data-room_id="{{ $pbed->id }}"
                                                class="tb-main-content-book-button btn btn-sm btn-success">{!! __('messages.Book now') !!}</button>
                                        </div>
                                    </div>
                                    {{-- @endif --}}
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @include('site.partials.property-calendar')
                </div>
                @include('site.partials.property-bottom')
            </div>
            @include('site.partials.property-right')
            <div class="col-12 col-lg-8 px-0">
                <div id="closest-properties" class="row mx-0 mt-3">
                    <div class="col-sm-12">
                        <h2 class="property-description-title">{!! __('messages.Similar Listings') !!}</h2>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            @for ($j = 0; $j < 2; $j++)
                                @if ($property->similar($j) && ($prop = $property->similar($j)->similar))
                                    <div class="col-md-6 mb-2">
                                        <div class="row mx-0 h-100">
                                            @include('site.partials._property-item', [
                                                'property' => $prop,
                                                'similar' => true,
                                            ])
                                        </div>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('site.partials.property-more')

    <script type="text/javascript">
        $(document).ready(function() {
            var checkin = document.getElementById("property-check-in").value;
            var checkout = document.getElementById("property-check-out").value;

            $("#property-check-in").blur(function() {
                document.getElementById("property-check-in").value = checkin;
            });
            $("#property-check-out").blur(function() {
                document.getElementById("property-check-out").value = checkout;
            });
            let deleteDates = {!! $property->notAvailableDates() !!};
            if ($("#reservation-property-wrapper-property").length) {
                function showCalendarProperty() {
                    // Count days in month
                    let numberCurrentMonth = new Date(currentYearProperty, currentMonthProperty + 1, 0).getDate();
                    for (let x = 1; x < 7; x++) {
                        $("#property-reservation-calendar .reservation-calendar-dates").append(
                            '<div class="row mx-0 dates-wrapper" id="w' + x + '"></div>');
                    }
                    let currentWeek = 1;
                    // Fill empty blocks in the start
                    if (new Date(currentYearProperty, currentMonthProperty, 1).getDay() > 1) {
                        for (let z = 0; z < new Date(currentYearProperty, currentMonthProperty, 1).getDay() -
                            1; z++) {
                            $("#property-reservation-calendar .reservation-calendar-dates #w1").append(
                                '<div class="col py-4 border"></div>');
                        }
                    } else if (new Date(currentYearProperty, currentMonthProperty, 1).getDay() === 0) {
                        for (let z = 0; z < 6; z++) {
                            $("#property-reservation-calendar .reservation-calendar-dates #w1").append(
                                '<div class="col py-4 border"></div>');
                        }
                    }
                    // Fill not empty blocks
                    for (let y = 1; y <= numberCurrentMonth; y++) {
                        let currentDayNumber = new Date(currentYearProperty, currentMonthProperty, y).getDay();
                        if (currentDayNumber === 1) {
                            currentWeek += 1;
                        }
                        let currentDateFormat = "";
                        let currentDateFormatMonth = "";
                        if (new Date(currentYearProperty, currentMonthProperty, y).getMonth() + 1 < 10) {
                            currentDateFormatMonth = "0" + (new Date(currentYearProperty, currentMonthProperty, y)
                                .getMonth() + 1);
                        } else if (new Date(currentYearProperty, currentMonthProperty, y).getMonth() + 1 >= 10) {
                            currentDateFormatMonth = (new Date(currentYearProperty, currentMonthProperty, y)
                                .getMonth() + 1);
                        }
                        if (y >= 10) {
                            currentDateFormat = y + "." + currentDateFormatMonth + "." + new Date(
                                currentYearProperty, currentMonthProperty, y).getFullYear();
                        } else {
                            currentDateFormat = "0" + y + "." + currentDateFormatMonth + "." + new Date(
                                currentYearProperty, currentMonthProperty, y).getFullYear();
                        }
                        if (deleteDates.includes(currentDateFormat)) {
                            $("#property-reservation-calendar .reservation-calendar-dates #w" + currentWeek).append(
                                '<div data-date="' + currentDateFormat +
                                '" class="col py-4 border date-wrapper" style="background-color:#ad7100;color:#fff;cursor:pointer;">' +
                                y + '</div>');
                        } else {
                            $("#property-reservation-calendar .reservation-calendar-dates #w" + currentWeek).append(
                                '<div data-date="' + currentDateFormat +
                                '" class="col py-4 border date-wrapper">' + y + '</div>');
                        }
                    }
                    // Fill empty block in the end
                    if ($("#w" + currentWeek + " div.col").length !== 7) {
                        for (let x = $("#w" + currentWeek + " div.col").length; x < 7; x++) {
                            $("#property-reservation-calendar .reservation-calendar-dates #w" + currentWeek).append(
                                '<div class="col py-4 border"></div>');
                        }
                    }
                }
                // Create calendar
                let todayProperty = new Date();
                let currentYearProperty = todayProperty.getFullYear();
                let currentMonthProperty = todayProperty.getMonth();
                let monthStorageProperty = ["January", "February", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"
                ];
                window.onload = function() {
                    $("#property-reservation-calendar .reservation-calendar-dates").empty();
                    if (currentMonthProperty === 11) {
                        currentMonthProperty = -1;
                        currentYearProperty += 1;
                    }
                    $("#property-reservation-calendar .month-name-year").text(monthStorageProperty[
                        currentMonthProperty] + " " + currentYearProperty);
                    showCalendarProperty();
                };
                // Next month
                $(document).on("click", ".next-month", function() {
                    $("#property-reservation-calendar .reservation-calendar-dates").empty();
                    if (currentMonthProperty === 11) {
                        currentMonthProperty = -1;
                        currentYearProperty += 1;
                    }
                    currentMonthProperty += 1;
                    $("#property-reservation-calendar .month-name-year").text(monthStorageProperty[
                        currentMonthProperty] + " " + currentYearProperty);
                    showCalendarProperty();
                });
                // Previous month
                $(document).on("click", ".prev-month", function() {
                    if (currentYearProperty === todayProperty.getFullYear()) {
                        if (currentMonthProperty !== todayProperty.getMonth()) {
                            $("#property-reservation-calendar .reservation-calendar-dates").empty();
                            if (currentMonthProperty === 0) {
                                currentMonthProperty = 12;
                                currentYearProperty -= 1;
                            }
                            currentMonthProperty -= 1;
                            $("#property-reservation-calendar .month-name-year").text(monthStorageProperty[
                                currentMonthProperty] + " " + currentYearProperty);
                            showCalendarProperty();
                        }
                    } else {
                        $("#property-reservation-calendar .reservation-calendar-dates").empty();
                        if (currentMonthProperty === 0) {
                            currentMonthProperty = 12;
                            currentYearProperty -= 1;
                        }
                        currentMonthProperty -= 1;
                        $("#property-reservation-calendar .month-name-year").text(monthStorageProperty[
                            currentMonthProperty] + " " + currentYearProperty);
                        showCalendarProperty();
                    }
                });
            }
        });
        $(window).on("load", function() {
            if (window.location.href.indexOf("#availability") > -1) {
                jQuery('html, body').animate({
                    scrollTop: jQuery("#tb-main-template").offset().top - 200
                }, 0);
            }
        });
    </script>
@stop
