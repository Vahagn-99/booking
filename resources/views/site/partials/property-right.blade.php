<div class="col-12 col-sm-12 col-lg-4 pr-0 px-sm-0 px-0 pl-lg-5">
    <div class="row mx-0 mb-3">
        <div class="col-12 p-3" id="property-right-price-base">
            @if (\Request::has('checkin') && \Request::has('checkout'))
                <h4 class="mb-0 text-center text-white">
                    {!! $property->currency_sign .
                        $property->price_quote(
                            \Request::get('checkin'),
                            \Request::get('checkout'),
                            \Request::has('bedrooms') ? $property->getRoomId(\Request::get('bedrooms')) : 0,
                        )['total'] !!}
                    <span class="property-similar-price-for font-weight-normal"><br />{!! __('messages.including taxes and charges') !!}</span>
                </h4>
            @else
                <h4 class="mb-0 text-center text-white"><span
                        class="property-similar-price-for font-weight-normal">{!! __('messages.from') !!} </span>
                    {!! $property->price_per_night !!}
                    <span class="property-similar-price-for font-weight-normal">/{!! __('messages.night') !!}</span>
                </h4>
            @endif
        </div>
    </div>
    <form action="/payment" method="post">
        @csrf
        <div id="book-wrapper"
            class="bg-white p-3 mb-3 {{ \Request::has('checkin') && \Request::has('checkout') ? 'd-block' : 'd-none' }}">
            <div id="book-property">
                <input type="hidden" name="property" id="property_id" value="{{ $property->id }}" {{-- @dd($property->room) --}}
                    data-sleep-max="{{ $property->propertybedrooms->count() }}">
                <input type="hidden" name="room_id" id="room_id"
                    value="{{ \Request::has('bedrooms') ? $property->getRoomId(\Request::get('bedrooms')) : 0 }}">
                <h5 id="book-property-title" class="text-center font-weight-bold">{!! __('messages.Book the') !!}
                    {!! session()->get('locale') == 'fr'
                        ? ($property->name_fr
                            ? $property->name_fr
                            : $property->name)
                        : $property->name !!}</h5>
                <div class="form-group mb-3">
                    <label class="property-list-el-wrapper font-weight-bold">{!! __('messages.Check in') !!}</label>
                    <input type="text" name="reservation_check_in"
                        class="form-control form-data optional datepicker start get-checkout" readonly
                        id="property-check-in"
                        value="{{ \Request::has('checkin') && \Request::has('checkout') ? \Request::get('checkin') : '' }}">
                </div>
                <div class="form-group mb-3">
                    <label class="property-list-el-wrapper font-weight-bold">{!! __('messages.Check out') !!}</label>
                    <input data-minstay="" name="reservation_check_out" type="text"
                        class="form-control form-data optional datepicker" readonly id="property-check-out"
                        value="{{ \Request::has('checkin') && \Request::has('checkout') ? \Request::get('checkout') : '' }}">
                </div>
                <div class="form-group mb-3">
                    <label class="property-list-el-wrapper font-weight-bold">{!! __('messages.Adults') !!}</label>
                    <select class="form-control form-data optional" name="reservation_adults"
                        data-maxpeople="{{ $property->sleeps_max }}" id="property-adults">
                        @for ($i = 0; $i <= $property->sleeps_max; $i++)
                            <option value="{{ $i }}"
                                {{ \Request::has('guests') && \Request::get('guests') == $i ? 'selected' : '' }}>
                                {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="property-list-el-wrapper font-weight-bold">{!! __('messages.Children') !!}</label>
                    <select class="form-control form-data optional" name="reservation_children" id="property-children">
                        <option selected value="0">0</option>
                    </select>
                </div>
                <button type="button" class="btn btn-success w-100" id="book-property-button"
                    onclick="reservationCalc(this)"
                    {{ \Request::has('checkin') && \Request::has('checkout') ? '' : 'disabled' }}>{!! __('messages.Book now') !!}</button>
            </div>
            <div id="book-property-data" class="text-center border-bottom universal-underline pb-3"
                style="display: none;">
                <h5 id="book-property-data-title" class="font-weight-bold">{!! __('messages.Check price and availability for the') !!}
                    {!! session()->get('locale') == 'fr'
                        ? ($property->name_fr
                            ? $property->name_fr
                            : $property->name)
                        : $property->name !!}</h5>
                <p class="property-list-el-wrapper" id="book-property-data-check-in">{!! __('messages.Check in') !!}:
                    <span></span>
                </p>
                <p class="property-list-el-wrapper" id="book-property-data-check-out">{!! __('messages.Check out') !!}:
                    <span></span>
                </p>
                <p class="property-list-el-wrapper" id="book-property-data-adults">{!! __('messages.Adults') !!}:
                    <span></span>
                </p>
                <p class="property-list-el-wrapper" id="book-property-data-children">{!! __('messages.Children') !!}:
                    <span></span>
                </p>
                <button type="button" class="btn btn-primary w-100"
                    id="book-property-button-change">{!! __('messages.Change search') !!}</button>
            </div>
            <div id="book-property-price" class="pt-3" style="display: none">
                <h5 class="property-list-el-wrapper text-center">{!! __('messages.The') !!} {!! session()->get('locale') == 'fr'
                    ? ($property->name_fr
                        ? $property->name_fr
                        : $property->name)
                    : $property->name !!}
                    {!! __('messages.is available at chosen dates') !!}</h5>
                <div class="row mx-0">
                    <div class="col-12 px-0">
                        <h6 class="property-list-el-wrapper text-center">
                            <span class="font-weight-bold">{!! $property->currency_sign !!}</span>
                            <span id="sub_total_price"></span> / {!! __('messages.price for') !!} <span
                                id="book-property-fields-nights"></span> {!! __('messages.nights') !!}
                        </h6>
                        <h6 class="property-list-el-wrapper text-center">
                            {!! __('messages.Tax & Fee') !!}: <span class="font-weight-bold">{!! $property->currency_sign !!}</span>
                            <span id="tax_fee"></span>
                        </h6>
                        <h6 class="property-list-el-wrapper text-center">
                            {!! __('messages.Total price') !!}: <span class="font-weight-bold">{!! $property->currency_sign !!}</span>
                            <span id="total_price"></span>
                        </h6>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100">{!! __('messages.Book now') !!}</button>
            </div>
        </div>
    </form>
    <div class="row mx-0 mb-3">
        <div class="col-12 px-0">
            <button class="btn btn-primary btn-lg rounded-0 w-100" type="button"
                id="property-rates-availability">{!! __('messages.AVAILABILITY & DAILY RATES') !!}</button>
        </div>
    </div>
    <div class="row mx-0" id="closest-properties-right">
        <div class="col-sm-12 px-0">
            <h2 class="property-description-title">{!! __('messages.Similar Listings') !!}</h2>
        </div>
        @for ($i = 2; $i < 5; $i++)
            @if ($property->similar($i) && ($prop = $property->similar($i)->similar))
                <div class="col-sm-12 px-0 mb-3">
                    <div class="row mx-0 h-100">
                        @include('site.partials._property-item', ['property' => $prop, 'similar' => true])
                    </div>
                </div>
            @endif
        @endfor
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function($) {
        let sleep_max = 0;
        let adults = 0;
        let children = 0

        $(document).on('click', '.tb-main-content-book-button, .tb-main-content-book button',
            function() {
                sleep_max = getBedroomCout(this)
            });

        $(document).on('change', "#property-adults", function() {
            adults = $(this).val()
            ifPeopleCountMoreThenBedroomNeed()
        })

        $(document).on('change', "#property-children", function() {
            children = $(this).val()
            ifPeopleCountMoreThenBedroomNeed()
        })

        function getBedroomCout(_this) {
            return parseInt($(_this).attr('data-pbedroom'));
        }

        function ifPeopleCountMoreThenBedroomNeed() {
            const itmes = $(".data-aditional").closest('#tb-main-template').find('ul li');
            if ((parseInt(children) + parseInt(adults) >= 3) && (sleep_max == 1)) {
                $(".data-aditional").trigger('click');
                itmes.css('cursor', 'not-allowed');
                itmes.find('a').css('pointer-events', 'none')

            } else {
                itmes.css('cursor', 'pointer');
                itmes.find('a').css('pointer-events', 'all')
            }
            let ayEs = $('button[data-pbedroom="Additional Sofa Bed"]');
            showBookingForm(ayEs[0], true);
        }
    })
</script>
