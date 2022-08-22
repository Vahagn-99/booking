@extends('layouts.pdf')

@section('content')
    <div class="d-table header">
        <div class="d-tr">
            <div class="d-tc" style="background-color:#5a9ed5;width:70%;">
                <h3 style="margin:0;font-size:20px;">{{ $reservation->propertyInfo->ownerName() }}</h3>
            </div>
            <div class="d-tc" style="background-color:rgba(199,181,7,0.3);width:30%">
                <img style="max-width:180px;max-height:50px;" src="{{  $reservation->propertyInfo->ownerLogo() }}" alt="Logo" />
            </div>
        </div>
    </div>
    <div class="d-table m-10">
        <div class="d-tr">
            <div class="d-tc">
                <p>{!! $owner->address !!}</p>
            </div>
            <div class="d-tc"></div>
        </div>
        <div class="d-tr">
            <div class="d-tc">
                <p>{{ $owner->zip }} {!! $owner->city !!}</p>
            </div>
            <div class="d-tc">
                <p style="text-align:right;">{!! $owner->phone ? __('messages.Phone') . ': ' . $owner->phone : '' !!}</p>
            </div>
        </div>
        <div class="d-tr">
            <div class="d-tc">
                <p>{!! $owner->state !!}</p>
            </div>
            <div class="d-tc">
                <p style="text-align:right;">{!! $owner->website ? __('messages.Website') . ': ' . $owner->website->website_link : '' !!}</p>
            </div>
        </div>
        <div class="d-tr">
            <div class="d-tc">
                <p>{!! $owner->country !!}</p>
            </div>
            <div class="d-tc">
                <p style="text-align:right;">{!! $owner->email_business ? __('messages.Email') . ': ' . $owner->email_business : '' !!}</p>
            </div>
        </div>
        <div class="d-tr">
            <div class="d-tc"></div>
            <div class="d-tc">
                <p style="text-align:right;">{{ date("D d M Y") }}</p>
            </div>
        </div>
    </div>
    <div class="bg-green bg-title">
        <h3>
            <img style="width:20px;margin-left:10px;margin-top:4px" src="https://bookingfwi.com/img/email-info.png" alt="Info" /><span style="margin-left:10px;font-size:18px;">{!! __('messages.Reservation Contract') !!}</span>
        </h3>
    </div>
    <div class="m-10">
        <p>{!! __('messages.Reference') !!}: {{ \App\Models\Countrestoday::resReference() }}</p>
        <p>{!! $reservation->contact_name !!}</p>
        <p>{!! __('messages.Address') !!}: {!! $reservation->full_address !!}</p>
        <p>{!! __('messages.Phone') !!}: {{ $reservation->contact_phone }}</p>
        <p>{!! __('messages.Email') !!}: {{ $reservation->contact_email }}</p>
        <p>{!! __('messages.Dear') !!} {!! $reservation->contact_name !!},</p>
        <p style="font-weight:400;font-size:15px;">
            {!! __('messages.We are pleased to confirm your reservation for') !!} <strong>{!! $reservation->propertyInfo->name !!}</strong> {!! __('messages.regarding your stay') !!}
            <strong>
                {!! \Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_in)->format('D').", ".\Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_in)->format('d F Y') ." - ".
                \Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_out)->format('D').", ".\Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_out)->format('d F Y') !!}
            </strong>
        </p>
    </div>
    <div class="m-10">
        <p style="font-size:15px">{!! __('messages.Reservation information') !!}</p>
        <div class="d-table bg-green">
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! $reservation->propertyInfo->name !!}:</p>
                </div>
                <div class="d-tc">
                    <p>
                        {!! $reservation->room_name !!}, {{ $reservation->propertyInfo->baths_count }} {{ $reservation->propertyInfo->bathrooms = 1 ? ' Bathroom' : '
                        Bathrooms'}}, Sleeps {{ $reservation->propertyInfo->sleeps ." - ". $reservation->propertyInfo->sleeps_max}}, {{ $reservation->propertyInfo->area .' '. $reservation->propertyInfo->area_unit }}
                    </p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.Address') !!}:</p>
                </div>
                <div class="d-tc">
                    <p>{!! $reservation->propertyInfo->full_address !!}</p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.Check-In') !!}:</p>
                 </div>
                <div class="d-tc">
                    <p>
                        {!! \Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_in)->format('D').", ".\Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_in)->format('d F Y') !!}, {{
                        $owner->default_check_in }}
                    </p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.Check-Out') !!}:</p>
                </div>
                <div class="d-tc">
                    <p>
                        {{ \Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_out)->format('D').", ".\Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_out)->format('d F Y') }}, {{
                        $owner->default_check_out }}
                    </p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.Nights')!!}:</p>
                </div>
                <div class="d-tc">
                    <p>{{ $reservation->nightsNum() }}</p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.People')!!}:</p>
                </div>
                <div class="d-tc">
                    <p>{{ $reservation->reservation_adults + $reservation->reservation_children }}</p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.Rental Price') !!}:</p>
                </div>
                <div class="d-tc">
                    <p>{!! $currenciesSign[$reservation->reservation_currency] !!} <span>{{ $reservation->reservation_rental_price }}</span></p>
                </div>
            </div>
            @if ($reservation->discount_amount && $reservation->discount_amount != '' && $reservation->discount_amount != 0)
                <div class="d-tr">
                    <div class="d-tc">
                        <p>{!! __('messages.Discount') !!}:</p>
                    </div>

                    <div class="d-tc">
                        <p>- {!! $currenciesSign[$reservation->reservation_currency] !!} <span>{{ $reservation->discount_amount }}</span>
                    </div>
                </div>
            @endif
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.Fees/Services')!!}:</p>
                </div>
                <div class="d-tc">
                    <p>{!! $currenciesSign[$reservation->reservation_currency] !!} <span>{{ $reservation->tax_fee }}</span></p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.Total') !!}:</p>
                </div>
                <div class="d-tc">
                    <p>{!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->total_price }}</p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.Damage Deposit') !!}:</p>
                </div>
                <div class="d-tc">
                    <p>{!! $reservation->propertyInfo->price_damage_deposit !!}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="m-10">
        <p style="font-size:15px;">{!! __('messages.Included Fees/Services') !!}</p>
        <div class="d-table bg-green">
            <div class="d-tr ">
                <div class="d-tc">
                    <p>{!! __('messages.Fee/Service Name') !!}</p>
                </div>
                <div class="d-tc">

                </div>
                <div class="d-tc">
                    <p style="text-align:right;">{!! __('messages.Unit Price') !!}</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right;">{!! __('messages.Total price') !!}</p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.City Tax') !!}</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right;">1</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right;">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->reservation_city_tax }}</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right;">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->reservation_city_tax }}</p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.Service Charge') !!}</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right;">1</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right;">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->reservation_service_tax }}</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right;">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->reservation_service_tax }}</p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc">
                    <p>{!! __('messages.Cleaning fee') !!}</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right;">1</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right;">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->reservation_cleaning_fee }}</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right;">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->reservation_cleaning_fee }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="m-10">
        <div class="d-table bg-green">
            <div class="d-tr">
                <div class="d-tc"></div>
                <div class="d-tc"></div>
                <div class="d-tc">
                    <p style="text-align:right">{!! __('messages.Paid') !!} {{ date("D d M Y") }}:</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->paid == "" ? '0.00' : $reservation->paid }}</p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc"></div>
                <div class="d-tc"></div>
                <div class="d-tc">
                    <p style="text-align:right">{!! __('messages.Balance due before') !!} {!! \Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_in)->format('d F Y') !!}:</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right">{!! $currenciesSign[$reservation->reservation_currency] !!} <span>{{ \App\Models\Currency::priceFormat((float)$reservation->balance_due) }}</span></p>
                </div>
            </div>
            <div class="d-tr">
                <div class="d-tc"></div>
                <div class="d-tc"></div>
                <div class="d-tc">
                    <p style="text-align:right">{!! __('messages.Damage deposit due upon arrival') !!}:</p>
                </div>
                <div class="d-tc">
                    <p style="text-align:right">{!! $reservation->propertyInfo->price_damage_deposit !!}</p>
                </div>
            </div>
        </div>
    </div>
    @if ($reservation->propertyInfo->agreement_en && $reservation->propertyInfo->agreement_en != '')
        <hr />
        <div class="bg-green bg-title">
            <h3>
                <img style="width:20px;margin-left:10px;margin-top:4px" src="https://bookingfwi.com/img/email-info.png" alt="Info" /><span style="margin-left:10px;font-size:18px;">{!! __('messages.Rental Agreement') !!}</span>
            </h3>
        </div>
        <p class="m-10" style="font-size:12px;color:inherit;font-family:Arial;font-weight:400;white-space:pre-wrap;">{!! $reservation->propertyInfo->agreement_en !!}</p>
    @endif
@stop
