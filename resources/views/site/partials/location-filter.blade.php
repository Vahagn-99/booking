<div class="col-12 p-2 pt-5 pb-5" id="apartments-list-wrapper">
    <div class="row mx-0 filter-container">
        <div class="col-12 col-lg-5">
            <div id="filter-locations">
                <form class="search-form" action="{{ !$agencydomain ? route('locations') : route('agency-locations', ['city' => '', 'subdomain' => $agencydomain]) }}" method="get">
                    <div class="mx-0">
                        <div class=" pl-0 pr-1 px-sm-0 pl-md-0 pr-md-1 mb-2">
                            <div class="row mx-0">
                                <div class="col-sm-12 px-0 mb-2 pl-2 bg-white rounded form-item form-place {{ $agencydomain ? 'd-none' : '' }}" id="filter-locations-place">
                                    <h1><input id="place_autocomplete" name="city" value="{!! \Request::has('city') ? filter_var(\Request::get('city'), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : (\Request::has('place') ? filter_var(\Request::get('place'), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '') !!}"
                                        type="text" class="{{ !$agencydomain ? 'required' : '' }} form-control pl-4 border-0" placeholder="{!! __('messages.Where do you want to go') !!}?"></h1>
                                </div>
                                <div class="col-sm-12 pr-0 px-sm-0 pr-md-0 filter-location-dates">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 px-0 pl-sm-0 pr-sm-1 mb-2">
                                            <div id="filter-locations-check-in" class="bg-white rounded pl-2 form-item form-date">
                                                <input type="text" name="checkin" value="{{ \Request::has('checkin') ? \Request::get('checkin') : '' }}" class="form-control pl-4 border-0 datepicker start" readonly placeholder="{!! __('messages.Check in') !!}">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 px-0 pl-sm-1 pr-sm-1 mb-2">
                                            <div id="filter-locations-check-out" class="bg-white rounded pl-2 form-item form-date">
                                                <input type="text" name="checkout" value="{{ \Request::has('checkout') ? \Request::get('checkout') : '' }}" class="form-control pl-4 border-0 datepicker end" readonly placeholder="{!! __('messages.Check out') !!}">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 px-0 mb-2 pl-sm-1">
                                            <div id="filter-locations-types" class="bg-white rounded pl-2 form-item">
                                                <select class="form-control pl-4 border-0">
                                                    <option disabled selected>{!! __('messages.All types') !!}</option>
                                                    <option value="">{!! __('messages.any') !!}</option>
                                                    @foreach ($propTypes as  $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 px-0 pl-sm-0 pr-sm-1 mb-2">
                                    <div id="filter-locations-guest" class="bg-white rounded pl-2 form-item form-guest">
                                        <select name="guests" class="required form-control pl-4 border-0">
                                            <option disabled selected>{!! __('messages.Guest') !!}</option>
                                            @for($x = 1; $x <= $sleeps_count; $x++)
                                                <option value="{{ $x }}" {{ \Request::has('guests') && \Request::get('guests') == $x ? 'selected' : '' }}>{{ $x }} {{ $x == 1 ? __('messages.guest') : __('messages.guests') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 px-0 pl-sm-1 pr-sm-1 mb-2">
                                    <div id="filter-locations-bedrooms" class="bg-white rounded pl-2 form-item form-room">
                                        <select name="bedrooms" class="required form-control pl-4 border-0">
                                            <option disabled selected>{!! __('messages.Bedrooms') !!}</option>
                                            @for ($j=1; $j<=$bed_count; $j++)
                                                <option value="{{ $j }}" {{ \Request::has('bedrooms') && \Request::get('bedrooms') == $j ? 'selected' : '' }}>{{ $j }} {{ $j == 1 ? __('messages.bedroom') : __('messages.bedrooms') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 px-0 pl-sm-1 pr-sm-0">
                                    <div id="filter-locations-baths" class="bg-white rounded pl-2 form-item form-bath">
                                        <select class="form-control pl-4 border-0">
                                            <option disabled selected>{!! __('messages.Baths') !!}</option>
                                            <option>{!! __('messages.any') !!}</option>
                                            @for ($i=1; $i<=14; $i++)
                                                <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? __('messages.bath') : __('messages.baths') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 px-0 pl-sm-1 pr-sm-0">
                                    <button type="button" class="filter btn-search btn-main btn text-white px-4 font-weight-bold w-100 mt-sm-2 mt-lg-0">{!! __('messages.Search') !!}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @include('site.partials.location-map')
        </div>

        <div class="col-12 col-lg-7 mx-0 ">
            <div class="row">
                <div class="col-sm-12">
                    <div class="filtered-properties">
                        <input type="hidden" id="map-locations-currency" value="{!! \Cookie::has('currency') ? $currenciesSign[\Cookie::get('currency')] : $currenciesSign['EUR'] !!}">
                        <div id="apartments-list-inner-wrapper" class="pb-5 align-items-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
