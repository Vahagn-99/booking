<div class="property-shadow-item card h-100 {{ isset($similar) ? 'col-12 px-0' : '' }}">
    <a href="{{ !$agencydomain ? route('property', ['id' => $property->id]) : route('agency-property', ['id' => $property->id,'subdomain' => $agencydomain]) }}">
        @if($property->main_photo->photo && strpos($property->main_photo->photo,"/upload") === false)
            @if(strpos($property->main_photo->photo, "youtube.com") > 0 || strpos($property->main_photo->photo, "youtu.be") > 0)
                <div class="video-wrap">
                    <iframe class="w-100" style="min-height:300px" src="{{ strpos($property->main_photo->photo, 'youtu.be') > 0 ? str_replace('youtu.be','www.youtube.com/embed',$property->main_photo->photo) : 'https://www.youtube.com/embed/' . substr($property->main_photo->photo, intval(strpos($property->main_photo->photo, 'v=')) + 2, 11) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                </div>
            @else
                <video class="w-100">
                    <source src="{{ asset($property->main_photo->photo) }}" type="video/mp4">
                </video>
            @endif
        @else
            <img src="{{ $property->main_photo ? $property->main_photo->photo : asset('img/placeholder.jpg') }}"  class="card-img-top" alt="{{ $property->name }}">
        @endif
    </a>
    <div class="card-body p-0">
        <h5 class="card-title bg-dark text-right p-1 font-weight-bold mb-0">
            <span class="property-similar-price-for font-weight-normal">{!! __('messages.from') !!}</span>
            <span class="font-weight-bold">{!! $property->price_per_night !!}</span>
            <span class="property-similar-price-for font-weight-normal">/{!! __('messages.night') !!}</span>
        </h5>
        <a class="prop-name" href="{{ !$agencydomain ? route('property', ['id' => $property->id]) : route('agency-property', ['id' => $property->id,'subdomain' => $agencydomain]) }}">
            <p class="card-text home-property-name pt-3 px-3 font-weight-bold">{!! session()->get('locale') == 'fr' ? ($property->name_fr ? $property->name_fr : $property->name) : $property->name !!}</p>
        </a>
        <p class="card-text home-property-place px-3 mb-1"><i class="fas fa-map-marker-alt mr-2"></i><span>{!! $property->country.", ".$property->city !!}</span></p>
        <p class="card-text home-property-type pb-3 px-3"><i class="fas fa-home mr-2"></i><span>{!! $property->rentalType->title !!} </span></p>
    </div>
</div>
