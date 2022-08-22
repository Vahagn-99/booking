@if(count($websiteHomeFeaturedProperties) > 0)
<script type="text/javascript">
    // Home slider
    if($("#home-slider .swiper-container").length) {
        var myswiper = new Swiper('#home-slider .swiper-container', {
            autoHeight: true,
            disableOnInteraction: true,
            slidesPerView: {{ $featuredListingName ? $featuredListingName->slides_per_row : '3' }},
            spaceBetween: 30,
            freeMode: true,
            speed: 2000,
            autoplay: {
                delay: {{ $featuredListingName ? (intval($featuredListingName->autoplay_speed) * 1000) : '0' }},
            },
            loop: true,
            pagination: {!!
                ($featuredListingName && $featuredListingName->navigation_type != "" && $featuredListingName->navigation_type != "no" && $featuredListingName->navigation_type != "arrows")
                ? '{
                    el: "#home-slider .swiper-pagination",
                    clickable: true,
                }'
                : 'false'
            !!},
            navigation: {!!
                ($featuredListingName && $featuredListingName->navigation_type != "" && $featuredListingName->navigation_type != "no" && $featuredListingName->navigation_type != "bullets")
                ? '{
                    nextEl: "#home-slider .swiper-button-next",
                    prevEl: "#home-slider .swiper-button-prev",
                }'
                : 'false'
            !!},
            breakpoints: {
                // when window width is >= 320px
                320: {
                    slidesPerView: 1,
                },
                // when window width is >= 640px
                680: {
                    spaceBetween: 30,
                    slidesPerView: 2
                },
                800: {
                    slidesPerView: {{ $featuredListingName ? $featuredListingName->slides_per_row : '3' }},
                    spaceBetween: 30
                }
            }
        });
        $("#home-slider .swiper-container").mouseenter(function() {
            myswiper.autoplay.stop();
        });

        $("#home-slider .swiper-container").mouseleave(function() {
            myswiper.autoplay.start();
        });
    }
</script>
@endif
