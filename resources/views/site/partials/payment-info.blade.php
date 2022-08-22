<h3>{!! __('messages.Reservation details') !!}</h3>
<div class="row">
    <div class="col-md-4">
        <img src="{{ $property->main_photo ? $property->main_photo->photo : asset('img/placeholder.jpg') }}" class="img-fluid" alt="Responsive image">
    </div>
    <div class="col-md-8">
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-form-label font-weight-bold">{!! __('messages.Property') !!}</label>
            <div class="col-sm-8">
                <p class="mb-0 col-form-label">{!! session()->get('locale') == 'fr' ? ($property->name_fr ? $property->name_fr : $property->name) : $property->name !!}</p>
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-form-label font-weight-bold">{!! __('messages.Reservations dates') !!}</label>
            <div class="col-sm-8">
                <p class="mb-0 col-form-label">{!! $data['reservation_check_in'] . ' - ' . $data['reservation_check_out'] . ' (' . $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['nights'] . ' ' . __('messages.nights') . ')'!!}</p>
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-form-label font-weight-bold">{!! __('messages.Adults') !!}</label>
            <div class="col-sm-8">
                <p class="mb-0 col-form-label">{{ $data['reservation_adults'] }}</p>
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-form-label font-weight-bold">{!! __('messages.Children') !!}</label>
            <div class="col-sm-8">
                <p class="mb-0 col-form-label">{{ isset($data['reservation_children']) ? $data['reservation_children'] : 0 }}</p>
            </div>
        </div>
        <hr>
        <h4>{!! __('messages.Payment') !!}</h4>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label font-weight-bold">{!! __('messages.Apartment price') !!}</label>
            <div class="col-sm-8">
                <p class="mb-0 col-form-label">{!! $property->currency_sign . $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['subtotal'] !!}</p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label font-weight-bold">{!! __('messages.Tax & Fee') !!}</label>
            <div class="col-sm-8">
                <p class="mb-0 col-form-label">{!! $property->currency_sign . $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['tax_fee'] !!}</p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label font-weight-bold">{!! __('messages.Total price') !!}</label>
            <div class="col-sm-8">
                <p class="mb-0 col-form-label">{!! $property->currency_sign . $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['total'] !!}</p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label font-weight-bold">{!! __('messages.To pay') !!}</label>
            <div class="col-sm-8">
                @if(Carbon\Carbon::today()->diffInDays(Carbon\Carbon::createFromFormat('d.m.y',$data['reservation_check_in'])->format('Y-m-d') ) > 30 )
                    <p class="mb-0 col-form-label">{!! $property->currency_sign . $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['subtotal'] * $property->downpayment/100 !!}</p>
                @else
                    <p class="mb-0 col-form-label">{!! $property->currency_sign . $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['total'] !!}</p>
                @endif
            </div>
        </div>
    </div>
</div>
