@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}

    <p style="margin-top:25px;margin-bottom:25px;font-size:22px;letter-spacing:-1px;color:#757575">{!! __('messages.Reservation information') !!}</p>
    <h3 style="font-size:20px;font-weight:bold;margin-bottom:17px;line-height:1.4">{!! __('messages.Dear') !!} {!! $to_name !!},</h3>
    @if ($type == 'visitor')
        <h3 style="font-size:20px;font-weight:bold;margin-bottom:17px;line-height:1.4">{!! __('messages.We are pleased to confirm your reservation for') !!} {!! $reservation->propertyInfo->name !!} {!! __('messages.regarding your stay') !!}.</h3>
    @else
        <h3 style="font-size:20px;font-weight:bold;margin-bottom:17px;line-height:1.4">{!! $user ? __('messages.The new reservation was added by') : __('messages.The new reservation was added') !!} {!! $user ? $user->first_name . ' ' . $user->last_name : '' !!} {!! __('messages.for property') !!} {!! $reservation->propertyInfo->name !!}.</h3>
    @endif
    <p align="left" style="background-color:rgba(199,181,7,0.3);padding:11px 11px 11px 11px;width:100%;max-width:540px;border-radius:2px;font-size:15px">{!! __('messages.Source / OTA') !!}: <b> {!! config('app.name') !!}</b></p>
    <p align="left" style="background-color:rgba(199,181,7,0.3);padding:11px 11px 11px 11px;width:100%;max-width:540px;border-radius:2px;font-size:15px">{!! __('messages.Reservation Number') !!}:<b> {{ $reservation->id }}</b></p>
    <p style="font-size:18px;font-weight:bold;padding-bottom:17px;border-bottom:1px solid #dbdee3">{!! __('messages.Booking summary') !!}</p>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:10px;border-bottom:1px solid #dbdee3">
        <tbody>
            <tr>
                <td align="left" cellpadding="0 0 0 0" style="padding-right:13px;vertical-align:top;width:33.333%">
                    <p style="font-size:13px;letter-spacing:0.6px;color:#333333;margin-bottom:10px">{!! __('messages.Check-In') !!}</p>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tbody>
                            <tr>
                                <td align="left" cellpadding="0 0 0 0" style="vertical-align:top;width:24px"><img src="{{ env('APP_URL') . '/img/check-out.png' }}" style="width:24px;height:24px;float:left" alt="Checkin" class="CToWUd"></td>
                                <td align="left" cellpadding="0 0 0 0">
                                    <p style="font-size:15px;font-weight:bold;letter-spacing:0.44px;color:#494949;margin:0 0 0 5px">{{ \Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_in)->format('l'); }} <br>{{ $reservation->reservation_check_in }}<br></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td align="left" cellpadding="0 0 0 0" style="border-left:1px solid #dbdee3;border-right:1px solid #dbdee3;padding:0 13px;vertical-align:top;width:33.333%">
                    <p style="font-size:13px;letter-spacing:0.6px;color:#333333;margin-bottom:10px">{!! __('messages.Nights') !!}</p>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tbody>
                            <tr>
                                <td align="left" cellpadding="0 0 0 0" style="vertical-align:top;width:24px"><img src="{{ env('APP_URL') . '/img/night.png' }}" style="width:24px;height:24px;float:left" alt="Checkin" class="CToWUd"></td>
                                <td align="left" cellpadding="0 0 0 0"><p style="font-size:48px;color:#494949;line-height:0.7;margin-left:5px">{{ $reservation->nightsNum() }}</p></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td align="left" cellpadding="0 0 0 0" style="padding-right:13px;vertical-align:top;width:33.333%">
                    <p style="font-size:13px;letter-spacing:0.6px;color:#333333;margin-bottom:10px">{!! __('messages.Check-Out') !!}</p>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tbody>
                            <tr>
                                <td align="left" cellpadding="0 0 0 0" style="vertical-align:top;width:24px"><img src="{{ env('APP_URL') . '/img/check-in.png' }}" style="width:24px;height:24px;float:left" alt="Checkin" class="CToWUd"></td>
                                <td align="left" cellpadding="0 0 0 0">
                                    <p style="font-size:15px;font-weight:bold;letter-spacing:0.44px;color:#494949;margin:0 0 0 5px">{{ \Carbon\Carbon::createFromFormat('d.m.Y',$reservation->reservation_check_out)->format('l'); }} <br>{{ $reservation->reservation_check_out}}<br></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                <td align="left">
                  <p style="line-height:1.43;letter-spacing:0.27px;color:rgba(0,0,0,0.6);padding:5px 0;font-size:14px">{!! __('messages.Guests') !!}</p>
                </td>
                <td align="right">
                  <p style="letter-spacing:0.44px;color:#494949;text-align:right;font-size:15px;font-weight:bold;padding:5px 0">{{ (int)$reservation->reservation_adults + (int)$reservation->reservation_children }} {!! __('messages.Adults') !!}</p>
                </td>
            </tr>
        </tbody>
    </table>
    @if($type != 'owner')
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:10px">
            <tbody>
                <tr style="border-top:1px solid #dbdee3">
                    <td align="left" style="padding:5px 0">
                        <p style="font-size:15px;font-weight:bold;line-height:1.43;letter-spacing:0.27px;color:rgba(0,0,0,0.6)">{{ (int)$reservation->propertyInfo->min_bed < 10?(int)$reservation->propertyInfo->min_bed:((int)$reservation->propertyInfo->min_bed%10)}} {{ (int)$reservation->propertyInfo->min_bed = 1 ? ' Bedroom' : ' Bedrooms'}} {{ $reservation->propertyInfo->name }}</p>
                        <p style="font-size:15px;line-height:1.43;letter-spacing:0.27px;color:rgba(0,0,0,0.6);margin-top:4px">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ \App\Models\Currency::priceFormat((float)$reservation->reservation_rental_price / (int)$reservation->nightsNum()) }} <span style="color:rgba(0,0,0,0.3)">×</span> {{ (int)$reservation->nightsNum() }} nights</p>
                    </td>
                    <td align="right" style="vertical-align:top;padding:5px 0;width:200px;">
                        <p style="text-align:right;letter-spacing:0.44px;font-size:15px">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ floatval($reservation->reservation_rental_price) }}</p>
                    </td>
                </tr>
                <tr style="border-top:1px solid #dbdee3">
                    <td align="left" style="padding:2px 0">
                        <p style="letter-spacing:0.44px;font-size:15px">{!! __('messages.Tax & Fee') !!}</p>
                    </td>
                    <td align="right" style="vertical-align:top;padding:2px 0;width:200px;">
                        <p style="text-align:right;color:#363b3d;font-size:15px;font-weight:bold">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->tax_fee }}</p>
                    </td>
                </tr>
                @if ($reservation->discount_amount && $reservation->discount_amount != '' && $reservation->discount_amount != 0)
                    <tr style="border-top:1px solid #dbdee3">
                        <td align="left" style="padding:2px 0">
                            <p style="font-weight:bold;letter-spacing:0.44px;font-size:15px">{!! __('messages.Discount') !!}</p>
                        </td>
                        <td align="right" style="vertical-align:top;padding:2px 0;width:200px;">
                            <p style="text-align:right;color:#363b3d;font-size:15px;font-weight:bold">- {!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->discount_amount }}</p>
                        </td>
                    </tr>
                @endif
                <tr style="border-top:1px solid #dbdee3">
                    <td align="left" style="padding:2px 0">
                        <p style="font-weight:bold;letter-spacing:0.44px;font-size:15px">{!! __('messages.Total') !!}</p>
                    </td>
                    <td align="right" style="vertical-align:top;padding:2px 0;width:200px;">
                        <p style="text-align:right;color:#363b3d;font-size:15px;font-weight:bold">{!! $currenciesSign[$reservation->reservation_currency] !!} {{ $reservation->total_price }}</p>
                    </td>
                </tr>
            </tbody>
        </table>
    @endif
    <table>
        <tbody>
            <tr>
                <td align="left" cellpadding="0 0 0 0">
                    <p style="font-size:18px;font-weight:bold;padding-bottom:17px;padding-right:15px;border-bottom:1px solid #dbdee3">{!! __('messages.Customer') !!}:</p>
                </td>
                <td align="left" style="vertical-align:top;padding:5px 0;font-size:15px">{!! $reservation->contact_name !!}</td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:20px;"><small>{!! __('messages.The reservation was created on') !!} {{ date("d.m.Y h:i a") }}.</small></p>

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot
@endcomponent
