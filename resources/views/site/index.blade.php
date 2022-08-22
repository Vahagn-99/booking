@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" id="home-section-first">
    <div class="row mx-0 position-relative align-items-center">
        <!-- home video -->
        @include('site.partials.home-video')
    </div>
</div>
<div class="container-fluid mb-2" id="home-section-two">
    <div class="row mx-0 justify-content-between">
        <!-- home locations -->
        @include('site.partials.home-locations')

        <!-- home slider -->
        @include('site.partials.home-slider')

        <div class="col-md-12 px-0">
            <p class="user-select-none" style="font-size: 1px; color: #ffffff;">We are the BookingFWI website and we offer our clients residential properties, which are available for our guests in any time of a year. Also we give you an opportunity to rent villa or rent villas, and to visit marvelous and gorgeous resorts.
                Why BOOKINGFWI?
                1.  We have a huge experience of offering villas, which you can rent. Apartments, residences, houses, condoes and other types of rents are available on our booking website for a long time.
                2.  Confidentiality. Our base of clients is big and they know, that we deserve for their trust. If you want to stay anonymous, you can easily do that with our service of rent villas.
                3.  Huge selection. Our diversity of apartments and vacation rentals really impresses even well-experienced tourists. If you are a beginner in travelling world, you will find your best variant of spending holidays on our website bookingfwi.com.
                4.  We are a reliable website that has been offering our rental services for many years and also we have an enormous number of positive reviews and feedbacks from over the world.
                5.  Available service 24/7. We are working for you pretty hard and we are ready to improve. Everyone can book a luxury villa or rent a cozy cottage for a family for holidays in any time or a season of a year. It’s staggering and we are trying to improve ourselves, so we are doing our best!
                As we said, we appreciate confidence of our customers, but in some cases we are obliged to cancel our private policy rules. We need to do it if we need:
                •  To comply with a legal obligation.
                •  To protect and defend the rights or property of bookingfwi.com.
                •  To prevent or investigate possible wrongdoing in connection with the Service.
                •  To protect the personal safety of users of the Service or the public.
                •  To protect against legal liability.
                Also, we need to inform you of our Personal Data usage and rules.
                Personal Data
                While using our Service, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you. Personally identifiable information may include, but is not limited to:
                •  Email address
                •  First name and last name
                •  Phone number
                •  Address, State, Province, ZIP/Postal code, City
                We may use your Personal Data to contact you with newsletters, marketing or promotional materials and other information that may be of interest to you. You may opt out of receiving any, or all, of these communications from us by following the unsubscribe link or instructions provided in any email we send or by contacting us.
                Usage Data
                We may also collect information how the Service is accessed and used (“Usage Data”). This Usage Data may include information such as your computer’s Internet Protocol address (e.g. IP address), browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages, unique device identifiers and other diagnostic data.
                Location Data
                We may use and store information about your location if you give us permission to do so (“Location Data”). We use this data to provide features of our Service, to improve and customize our Service.
                You can enable or disable location services when you use our Service at any time, through your device settings.
                Tracking & Cookies Data
                We use cookies and similar tracking technologies to track the activity on our Service and hold certain information.
                Cookies are files with small amount of data which may include an anonymous unique identifier. Cookies are sent to your browser from a website and stored on your device. Tracking technologies also used are beacons, tags, and scripts to collect and track information and to improve and analyze our Service.
                You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.
                BOOKINGFWI.COM – best website, where you can easily rent villas, rent apartments, book residences and come to ideal islands of you dreams. We offer you best islands on Caribbean - Grand Cul de Sac, Flamands, Gustavia, Grand Case, Terres Basses, Gouverneur, La Baie Orientale, Vitet and Lorient. All of these marvelous islands ready to have guests and tourists, couples and families. It is a great decision to go to the island on big holidays too and spend here, on the islands of you dreams few days or maybe weeks.
                BOOKINGFWI.COM is a full-service villa rental company featuring the most luxurious and exclusive villa rentals. We specialize on villas with a minimum of 2 to up to 6 bedrooms, with a housekeepers and a concierge. Bookingfwi caters to the discerning traveler who wants the best modern amenities and features that are typically found in luxury resorts but offered in a more private, exclusive, and intimate setting.
                We also offer:
                •  St Barts Luxury Villas for Rent
                •  Sint Maarten Luxury Villas for Rent
                •  St Martin Luxury Villas for Rent
                Also, you should remember that the easiest way to find the perfect villa is to let our villa specialist know every single detail that we need to know about the configuration of your group and your plans when you are at the villa or in the destination. Then once we know what you need, we will help you find that perfect villa that will match according to your requirements. There are many villas in St Barts, Saint-Martin, and Sint Maarten that are kid friendly especially the ones that are built on a flat ground, mostly beachfront villas. For more information on how to know if a villa rental is child-friendly or not.
                rent villa,
                rent villas,
                villas to rent,
                Grand Cul de Sac,
                Flamands,
                Gustavia,
                vacation rentals</p>
        </div>
    </div>
</div>

<!-- home-featured-properties script -->
@include('site.partials.home-scripts')

@stop
