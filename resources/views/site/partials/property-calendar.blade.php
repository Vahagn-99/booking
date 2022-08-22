<div id="reservation-property-wrapper-property" class="bg-white my-4">
    <h2 class="property-description-title">{!! __('messages.Availability') !!}</h2>
    <div class="row mx-0" id="property-reservation-calendar" data-property="{{ $property->id }}">
        <div class="col-12 px-0">
            <div class="row mx-0 mb-3 align-items-center">
                <div class="col-12 order-2 order-sm-1 col-sm-6 px-0 month-name-year mt-2 mt-sm-0">{!! __('messages.August 2020') !!}</div>
                <div class="col-12 order-1 order-sm-2 col-sm-6 px-0 text-sm-right">
                    <button class="prev-month btn btn-primary" type="button">{!! __('messages.Previous month') !!}</button>
                    <button class="next-month btn btn-primary ml-2" type="button">{!! __('messages.Next month') !!}</button>
                </div>
            </div>
            <div class="row mx-0">
                <div class="col px-0" id="d1">{!! __('messages.Mon') !!}</div>
                <div class="col px-0" id="d2">{!! __('messages.Tue') !!}</div>
                <div class="col px-0" id="d3">{!! __('messages.Wed') !!}</div>
                <div class="col px-0" id="d4">{!! __('messages.Thu') !!}</div>
                <div class="col px-0" id="d5">{!! __('messages.Fri') !!}</div>
                <div class="col px-0" id="d6">{!! __('messages.Sat') !!}</div>
                <div class="col px-0" id="d0">{!! __('messages.Sun') !!}</div>
            </div>
            <div class="row mx-0">
                <div class="col-12 px-0 reservation-calendar-dates">
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-0 mt-2">
        <div class="col-12 px-0">
            <p><i class="fas fa-square-full mr-2 align-middle"></i><span class="align-middle">{!! __('messages.booked') !!}</span></p>
        </div>
    </div>
</div>
