<h5 class="font-weight-bold mt-3">{!! __('messages.Sleeping Situation') !!}</h5>
<div class="py-3 row sleeping-situation-options border-bottom universal-underline">
    <div class="col-md-4 mb-2">
        @if ($property->bedrooms->count() > 0)
            <h6>{!! __('messages.Bedrooms') !!}</h6>
            <div class="list-group">
                @foreach ($property->bedrooms as $bed)
                    <div class="list-group-item list-group-item-action">
                        <div class="float-left">
                            <p>{{ $bed->name }}</p>
                            <i>{{ $bed->infoText() }}</i>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="col-md-4 mb-2">
        @if ($property->baths_count > 0)
            <h6>{!! __('messages.Bathrooms') !!}</h6>
            <div class="list-group">
                @foreach ($property->bathrooms as $bath)
                    <div class="list-group-item list-group-item-action">
                        <div class="float-left">
                            <p>{{ $bath->name }}</p>
                            <i>{{ $bath->infoText() }}</i>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="col-md-4">
        @if ($property->livingrooms->count() > 0)
            <h6>{!! __('messages.Living rooms') !!}</h6>
            <div class="list-group">
                @foreach ($property->livingrooms as $living)
                    <div class="list-group-item list-group-item-action">
                        <div class="float-left">
                            <p>{{ $living->name }}</p>
                            <i>{{ $living->infoText() }}</i>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
<h6 class="font-weight-bold mt-3">{!! __('messages.Property Address') !!}</h6>
<div class="row mx-0 border-bottom universal-underline">
    <div class="col-12 col-sm-6 pl-sm-0 pr-sm-2 px-0">
        <p class="property-list-el-wrapper"><b>{!! __('messages.Address') !!}: </b>{!! __('messages.Exact location information is provided after a booking is confirmed') !!}.</p>
        <p class="property-list-el-wrapper"><b>{!! __('messages.Zip') !!}: </b>{{ $property->zip }}</p>
    </div>
    <div class="col-12 col-sm-6 pl-sm-2 pr-sm-0 px-0">
        <p class="property-list-el-wrapper"><b>{!! __('messages.City') !!}: </b>{!! $property->city !!}</p>
        <p class="property-list-el-wrapper"><b>{!! __('messages.Area') !!}: </b>{!! $property->city.", ".$property->country !!}</p>
        <p class="property-list-el-wrapper"><b>{!! __('messages.State') !!}: </b>{!! $property->state !!}</p>
        <p class="property-list-el-wrapper"><b>{!! __('messages.Country') !!}: </b>{!! $property->country !!}</p>
    </div>
</div>
<h6 class="font-weight-bold mt-3">{!! __('messages.Property Details') !!}</h6>
<div class="row mx-0 pt-2">
    <div class="col-12 col-sm-6 pl-sm-0 pr-sm-2 px-0">
        <p class="property-list-el-wrapper"><b>{!! __('messages.Property ID') !!} : </b>{{ $property->id }}</p>
        <p class="property-list-el-wrapper"><b>{!! __('messages.Rooms') !!}: </b>{{ $property->beds_count + $property->livings_count }}</p>
        <p class="property-list-el-wrapper"><b>{!! __('messages.Bathrooms') !!}: </b>{{ $property->baths_count }}</p>
        <p class="property-list-el-wrapper"><b>{!! __('messages.Check out Hour') !!}: </b>{{ $property->ownerInfo && $property->ownerInfo->default_check_out ? $property->ownerInfo->default_check_out : '11:00 AM'}}</p>
        @if ($property->ownerInfo)
            <p class="property-list-el-wrapper"><b>{!! __('messages.Owner language') !!}: </b>{{ $property->ownerInfo->default_language }}</p>
        @endif
        <p class="property-description-subtitle">
            <span class="pre-wrap d-block">{!! session()->get('locale') == 'fr' ? $property->small_canc_fr : $property->small_canc_en !!}</span>
            <a href="#" data-toggle="modal" data-target="#property-cancellation-show" class="more-link font-weight-bold">{!! __('messages.View more') !!}</a>
        </p>
    </div>
    <div class="col-12 col-sm-6 pl-sm-2 pr-sm-0 px-0">
        @if ($property->area)
            <p class="property-list-el-wrapper"><b>{!! __('messages.Property size') !!}: </b>{{ $property->area.' '.$property->area_unit }}<sup>2</sup></p>
        @endif
        <p class="property-list-el-wrapper"><b>{!! __('messages.Bedrooms') !!}: </b>{{ $property->beds_count}}</p>
        <p class="property-list-el-wrapper"><b>{!! __('messages.Check-In Hour') !!}: </b>{{ $property->ownerInfo && $property->ownerInfo->default_check_in ? $property->ownerInfo->default_check_in : '3:00 PM'}}</p>
        <p class="property-description-subtitle">
            <span class="pre-wrap d-block">{!! session()->get('locale') == 'fr' ? $property->small_agreement_fr : $property->small_agreement_en !!}</span>
            <a href="#" data-toggle="modal" data-target="#property-agreement-show" class="more-link font-weight-bold">{!! __('messages.View more') !!}</a>
        </p>
    </div>
</div>
<h6 class="font-weight-bold mt-3">{!! __('messages.Property Features') !!}</h6>
<div class="row mx-0 pt-2">
    <div class="col-12 px-0">
        <ul class="row mx-0">
            @foreach ($property->amenities as $item)
                <li class="col-6 px-0">{!! __('messages.'.$item->amenity->title) !!}</li>
            @endforeach
        </ul>
    </div>
</div>
<div id="map-property-wrapper" class="p-1 p-sm-3 bg-white mb-2">
    <h2 class="property-description-title">{!! __('messages.On the map') !!}</h2>
    <div id="map"></div>
</div>
<input type="hidden" id="place_lat" value="{{ $property->lat }}">
<input type="hidden" id="place_lng" value="{{ $property->lng }}">
