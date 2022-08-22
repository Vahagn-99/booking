@if (count($websiteHomeFeaturedProperties) > 0)
    <div class="col-md-12 px-0 my-5" id="home-slider">
        <h2 class="mb-5">{!! $featuredListingName ? (session()->get('locale') == 'fr' ? $featuredListingName->block_name_fr : $featuredListingName->block_name) : __('messages.Featured listing') !!}</h2>
        @if (isset($featuredListingName) && $featuredListingName->block_subname)
            <h4 class="text-center">{!! (session()->get('locale') == 'fr' ? $featuredListingName->block_subname_fr : $featuredListingName->block_subname) !!}</h4>
        @endif

        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach ($websiteHomeFeaturedProperties as $adminFeaturedProperty)
                    @if ($adminFeaturedProperty->propertyInfo() && $adminFeaturedProperty->propertyInfo()->show_on_main == "yes")
                    <div class="swiper-slide">
                        @include('site.partials._property-item',['property' => $adminFeaturedProperty->propertyInfo()])
                    </div>
                    @endif
                @endforeach
                </div>
                @if ($featuredListingName && $featuredListingName->navigation_type != "" && $featuredListingName->navigation_type != "no" && $featuredListingName->navigation_type != "bullets")
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                @elseif ($featuredListingName && $featuredListingName->navigation_type != "" && $featuredListingName->navigation_type != "no" && $featuredListingName->navigation_type != "arrows")
                    <div class="swiper-pagination"></div>
                @endif
            </div>
        </div>
    </div>
@endif
