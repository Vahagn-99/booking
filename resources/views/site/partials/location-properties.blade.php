@if (count($properties) > 0)
    <?php $countMap = 0; ?>
    @foreach ($properties as $property)
        <div class="pb-2 apartments-list" data-map="gmimap{{ $countMap }}">
            <input type="hidden" class="map-locations-lat" value="{{ $property->lat }}">
            <input type="hidden" class="map-locations-lng" value="{{ $property->lng }}">
            <input type="hidden" class="map-locations-property" value="{{ $property->id }}">
            <input type="hidden" class="map-locations-img" value="{{ $property->main_photo ? asset($property->main_photo->photo) : asset('img/placeholder.jpg') }}">
            <input type="hidden" class="map-locations-name" value="{{ $property->name }}">
            <input type="hidden" class="map-locations-type" value="{{ $property->rentalType->title }}">
            <input type="hidden" class="map-locations-bed" value="{{ $property->beds_count }}">
            <input type="hidden" class="map-locations-people" value="{{ $property->sleeps_max }}">
            <input type="hidden" class="map-locations-price" value="{!! $property->price_per_night !!}">

            <div class="card h-100 col-12 col-md-12 col-sm-12 py-2">
                <div class="row w-100">
                    <div class="col-lg-4">
                        <a href="{{ !$agencydomain ? route('property', ['id' => $property->id,'checkin' => $checkin,'checkout' => $checkout,'guests' => $guest,'bedrooms' => $bedroom]) : route('agency-property', ['id' => $property->id,'subdomain' => $agencydomain,'checkin' => $checkin,'checkout' => $checkout,'guests' => $guest,'bedrooms' => $bedroom]) }}">
                            @if(strpos($property->main_photo->photo,"/upload") === false)
                                @if(strpos($property->main_photo->photo, "youtube.com") > 0 || strpos($property->main_photo->photo, "youtu.be") > 0)
                                    <div class="video-wrap">
                                        <iframe class="w-100" style="min-height:180px" src="{{ strpos($property->main_photo->photo, 'youtu.be') > 0 ? str_replace('youtu.be','www.youtube.com/embed',$property->main_photo->photo) : 'https://www.youtube.com/embed/' . substr($property->main_photo->photo, intval(strpos($property->main_photo->photo, 'v=')) + 2, 11) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                                    </div>
                                @else
                                    <video class="w-100">
                                        <source src="{{ asset($property->main_photo->photo) }}" type="video/mp4">
                                    </video>
                                @endif
                            @else
                                <img src="{{ $property->main_photo ? $property->main_photo->photo : asset('img/placeholder.jpg') }}"  class="card-img-top prop-img" alt="{{ $property->name }}">
                            @endif
                        </a>
                    </div>
                    <div class="card-body p-0 col-lg-5">
                        <a class="prop-name" href="{{ !$agencydomain ? route('property', ['id' => $property->id,'checkin' => $checkin,'checkout' => $checkout,'guests' => $guest,'bedrooms' => $bedroom]) : route('agency-property', ['id' => $property->id,'subdomain' => $agencydomain,'checkin' => $checkin,'checkout' => $checkout,'guests' => $guest,'bedrooms' => $bedroom]) }}">
                            <p class="card-text home-property-name px-3 font-weight-bold">{!! session()->get('locale') == 'fr' ? ($property->name_fr ? $property->name_fr : $property->name) : $property->name !!}</p>
                        </a>
                        <p class="card-text home-property-place px-3 mb-1"><i class="fas fa-map-marker-alt mr-2"></i><span>{!! $property->country.", ".$property->city !!}</span></p>
                        <p class="card-text home-property-type px-3 mb-1"><i class="fas fa-home mr-2"></i><span>{!! $property->rentalType->title !!} </span></p>
                        <p class="card-text home-property-bed px-3 mb-1"><i class="fas fa-bed mr-2"></i><span>{!! $property->beds_count . " bedroom" . ($property->beds_count > 1 ? "s" : "") !!} </span></p>
                        <p class="card-text home-property-bed px-3 mb-1"><i class="fas fa-bed mr-2"></i><span>{!! $property->sleeps_max . " sleep" . ($property->sleeps_max > 1 ? "s" : "") !!} </span></p>
                        @if ($property->livings_count > 0)
                            <p class="card-text home-property-livings px-3"><i class="fas fa-couch mr-2"></i><span>{!! $property->livings_count . " living room" . ($property->livings_count > 1 ? "s" : "") !!} </span></p>
                        @endif
                    </div>
                    <div class="prop-price col-lg-3">
                        <h5 class="card-title p-1 mb-0">
                            @if ($checkin && $checkout)
                                <p class="font-weight-bold mb-0">{!! $property->currency_sign . $property->price_quote($checkin, $checkout, $bedroom ? $property->getRoomId($bedroom) : 0)['total'] !!}</p>
                                <p class="property-similar-price-for font-weight-normal">{!! __('messages.includes taxes and charges') !!}</p>
                            @else
                                <span class="font-weight-bold mb-0">{!! $property->price_per_night !!}</span>
                                <span class="property-similar-price-for font-weight-normal"><br/>{!! __('messages.per night') !!}</span>
                            @endif
                        </h5>
                        <a class="btn btn-main w-100 mb-1" href="{{ !$agencydomain ? route('property', ['id' => $property->id,'checkin' => $checkin,'checkout' => $checkout,'guests' => $guest,'bedrooms' => $bedroom]) : route('agency-property', ['id' => $property->id,'subdomain' => $agencydomain,'checkin' => $checkin,'checkout' => $checkout,'guests' => $guest,'bedrooms' => $bedroom]) }}">{!! __('messages.View details') !!}</a>
                        @if ($checkin && $checkout)
                            @if($guest > $bedroom)
                                <a href="#" data-toggle="modal" data-target="#warning-message{{ $property->id }}" class="btn btn-success w-100 mb-1">{!! __('messages.Book now') !!}</a>
                                <div class="modal fade warning-message" id="warning-message{{ $property->id }}" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>{!! __('messages.Attention') !!}</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{!! __('messages.Max bed message') !!}</p>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <a class="btn btn-main w-100 mb-1" href="{{ !$agencydomain ? route('property', ['id' => $property->id,'checkin' => $checkin,'checkout' => $checkout,'guests' => $guest,'bedrooms' => $bedroom]) : route('agency-property', ['id' => $property->id,'subdomain' => $agencydomain,'checkin' => $checkin,'checkout' => $checkout,'guests' => $guest,'bedrooms' => $bedroom]) }}#availability">{!! __('messages.View details') !!}</a>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <form action="/payment" method="post">
                                                            @csrf
                                                            <input type="hidden" name="property" value="{{ $property->id }}">
                                                            <input type="hidden" name="currency" value="{{ \Cookie::has('currency') ? \Cookie::get('currency') : $property->currency }}">
                                                            <input type="hidden" name="room_id" value="{{ $bedroom ? $property->getRoomId($bedroom) : 0 }}">
                                                            <input type="hidden" name="reservation_check_in" value="{{ $checkin }}">
                                                            <input type="hidden" name="reservation_check_out" value="{{ $checkout }}">
                                                            <input type="hidden" name="reservation_adults" value="{{ $guest }}">
                                                            <input type="hidden" name="reservation_children" value="0">
                                                            <button type="submit" class="btn btn-success w-100 mb-1">{!! __('messages.Book now') !!}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <form action="/payment" method="post">
                                    @csrf
                                    <input type="hidden" name="property" value="{{ $property->id }}">
                                    <input type="hidden" name="currency" value="{{ \Cookie::has('currency') ? \Cookie::get('currency') : $property->currency }}">
                                    <input type="hidden" name="room_id" value="{{ $bedroom ? $property->getRoomId($bedroom) : 0 }}">
                                    <input type="hidden" name="reservation_check_in" value="{{ $checkin }}">
                                    <input type="hidden" name="reservation_check_out" value="{{ $checkout }}">
                                    <input type="hidden" name="reservation_adults" value="{{ $guest }}">
                                    <input type="hidden" name="reservation_children" value="0">
                                    <button type="submit" class="btn btn-success w-100 mb-1">{!! __('messages.Book now') !!}</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <?php $countMap++; ?>
    @endforeach
@elseif ($page == 1)
    <h2>{!! __('messages.No properties has been found') !!}.</h2>
@endif
