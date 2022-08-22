@if (count($websiteHomeCityList) > 0)
    <div class="col-md-12 px-0">
        <h2 class="text-center">{!! $cityListName ? (session()->get('locale') == 'fr' ? $cityListName->block_name_fr : $cityListName->block_name) : __('messages.Find the best place to stay') !!}</h2>
        @if ($cityListName)
            <h4 class="text-center">{!! (session()->get('locale') == 'fr' ? $cityListName->block_subname_fr : $cityListName->block_subname) !!}</h4>
        @endif
    </div>
@endif

@for ($i = 0; $i < count($websiteHomeCityList); $i++)
    <div class="{{ $className[$i] }}">
        <div class="row mx-0">
            <div class="col-md-12 px-0 home-gallery-wrapper" style="background-image: url('<?php echo $websiteHomeCityList[$i]->photo."?". time(); ?>');">
                <h5 class="card-title mb-0">
                    <a href="{{ !$agencydomain ? route('locations', ['city' => $websiteHomeCityList[$i]->city]) : route('agency-locations', ['city' => $websiteHomeCityList[$i]->city,'subdomain' => $agencydomain]) }}">{!! $websiteHomeCityList[$i]->city !!}</a>
                </h5>
                <div class="home-gallery-overlay"></div>
            </div>
        </div>
    </div>
@endfor
