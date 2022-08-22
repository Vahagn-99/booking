<div class="col-md-12 px-0">
    @if (!isset($headerBack))
        <video class="w-100" autoplay muted loop>
            <source src="https://bookingfwi.com/img/trailer-Villas-Apartments-Rentals.mp4" type="video/mp4">
        </video>
    @else
        @if($headerBack->header_back_type == "video" && isset($video))
            @if(strpos($video, "youtube.com") > 0)
                <div class="videoWrapper">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ substr($video, intval(strpos($video, "v=")) + 2, 11) }}?&amp;autoplay=1&amp;controls=0&amp;loop=1&amp;playlist={{ substr($video, intval(strpos($video, "v=")) + 2, 11) }}&amp;modestbranding=1&amp;rel=0&amp;showinfo=0&amp;mute=1" frameborder="0"
                      allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            @else
                <video class="w-100" autoplay muted loop>
                    <source src="{{ $video }}" type="video/mp4">
                </video>
            @endif
        @elseif($headerBack->header_back_type == "photo" && isset($photo))
            <img src="{{ asset($photo) }}" class="img-fluid w-100" alt="Header image">
        @elseif($headerBack->header_back_type == "slide")
            <div class="headPhoto" id="head-slider">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach ($slidephotos as $p)
                            <div class="swiper-slide">
                                <img src="{{ asset($p->photo) }}" class="img-fluid w-100" alt="Header image">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        @endif
    @endif
</div>
<div class="col-md-12 px-0 text-center position-absolute" id="home-section-first-text-main">
    <h1 id="for-test">{!! isset($headerBack) && $headerBack->header_title != "" ? (session()->get('locale') == 'fr' ? $headerBack->header_title_fr : $headerBack->header_title) : __('messages.Come to Our Islands and cities') !!}</h1>
    <h4>{!! isset($headerBack) && $headerBack->header_subtitle != "" ? (session()->get('locale') == 'fr' ? $headerBack->header_subtitle_fr : $headerBack->header_subtitle) : __('messages.THE BEST HOLIDAYS HAPPEN HERE') !!}</h4>
</div>
<div class="col-md-12 px-0 text-center" id="home-search-form">
    <div class="container">
        <form action="{{ !$agencydomain ? route('locations') : route('agency-locations', ['city' => '', 'subdomain' => $agencydomain]) }}" method="get" class="search-form form-inline align-items-stretch justify-content-center">
            <div class="row mx-0 justify-content-center">
                <div class="col-lg-3 px-0 {{ $agencydomain ? 'd-none' : '' }}">
                    <div class="bg-white rounded mb-sm-2 mb-lg-0 pl-2 form-item form-place">
                        <input name="city" id="place_autocomplete" type="text" class="{{ !$agencydomain ? 'required' : '' }} form-control py-0 pr-2 pl-4 w-100" placeholder="{!! __('messages.Where do you want to go') !!}?">
                    </div>
                </div>
                <div class="{{ $agencydomain ? 'col-lg-12' : 'col-lg-9' }} px-0">
                    <div class="row mx-1">
                        <div class="col-lg-3 px-1">
                            <div class="bg-white rounded mb-sm-2 mb-lg-0 pl-lg-2 form-item form-date">
                                <input name="checkin" type="text" class="form-control py-0 pr-2 pl-4 w-100 datepicker start" placeholder="{!! __('messages.Check-In') !!}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 px-1">
                            <div class="bg-white rounded mb-sm-2 mb-lg-0 pl-lg-2 form-item form-date">
                                <input name="checkout" type="text" class="form-control py-0 pr-2 pl-4 w-100 datepicker end" placeholder="{!! __('messages.Check-Out') !!}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2 px-1">
                            <div class="bg-white rounded mb-sm-2 mb-lg-0 pl-lg-2 form-item form-guest">
                                <select name="guests" class="required form-control pl-3 py-0 pr-2 pl-4 w-100">
                                    <option value="" selected disabled>{!! __('messages.Guests') !!}</option>
                                    @for ($i=1; $i<=$sleeps_count; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? __('messages.guest') : __('messages.guests') }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 px-1">
                            <div class="bg-white rounded mb-sm-2 mb-lg-0 pl-lg-2 form-item form-room">
                                <select name="bedrooms" class="required form-control pl-3 py-0 pr-2 pl-4 w-100">
                                    <option value="" disabled selected>{!! __('messages.Bedrooms') !!}</option>
                                    @for ($j=1; $j<=$bed_count; $j++)
                                        <option value="{{ $j }}">{{ $j }} {{ $j == 1 ? __('messages.bedroom') : __('messages.bedrooms') }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 px-1">
                            <button type="button" class="btn-search btn-main btn text-white px-4 font-weight-bold w-100 mt-sm-2 mt-lg-0 h-100">{!! __('messages.Search') !!}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
