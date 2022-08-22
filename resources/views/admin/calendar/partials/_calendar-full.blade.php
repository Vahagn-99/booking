<div class="row mx-0 align-items-center mb-4">
    <div class="col-2"></div>
    <div class="col-10 px-4 scroll-div">
        <div class="row text-center weekDaysName">
            @foreach ($weekDays as $day)
                <div class="col px-2 text-center">{!! $day !!}</div>
            @endforeach
        </div>
    </div>
</div>
<div class="list-group mb-3">
    @foreach ($properties as $property)
        <div class="list-group-item list-group-item-action" data-property_id="{{ $property->id }}">

            <div class="row mx-0">
                <div class="col-12 px-0 mb-3">
                    <h5>
                        <a href="{{ route('admin/property-settings', ['id' => $property->id]) }}" target="_blank">{!! $property->name !!}</a>
                        <a href="{{ route('admin/calendar', ['id' => $property->id]) }}" class="float-right btn btn-sm btn-success" title="{!! __('messages.Open individual calendar') !!}"><i class="far fa-calendar-alt"></i> {!! __('messages.Open calendar') !!}</a>
                    </h5>
                </div>
                <div class="col-2 px-0 fixed-rooms">
                    <div class="row mx-0 rooms">
                        <div class="col-12 px-0">
                            <small class="text-bold h6 mb-0">{!! __('messages.Rooms') !!}</small>
                        </div>
                        <div class="col-12 px-0">{{ $property->min_bed }} {!! $property->min_bed > 1 ? __('messages.Bedrooms') : __('messages.Bedroom') !!}</div>
                        @for ($j = 0; $j < $property->propertybedrooms->count(); $j++)
                            <div class="col-12 px-0">{!! $property->propertybedrooms[$j]->bed_name() !!}</div>
                        @endfor
                    </div>
                </div>
                <div class="col-10 px-0 scroll-div">
                    <div class="row mx-0 text-center calendarDaysNum" data-property="{{ $property->id }}">
                        @foreach ($dates as $day)
                            @if ($property->reservation($day->format('Y-m-d')))
                                <div {{ $user->is_admin() || $property->owner == $user->id || $property->reservation($day->format('Y-m-d'))->added_by_agency == $user->id ? 'onclick=openCalendarForm(this)' : ''}}  class="col px-0 text-center date-wrapper-new {{ $property->reservation($day->format('Y-m-d'))->reservation_check_out == $day->format('d.m.Y') ? 'half half-out' : ($property->reservation($day->format('Y-m-d'))->reservation_check_in == $day->format('d.m.Y') ? 'half half-in' : '') }} bg-{{ ($property->reservation($day->format('Y-m-d'))->added_by_agency == null || $property->reservation($day->format('Y-m-d'))->added_by_agency == '') ? 'primary' : 'info' }}"
                                    data-date="{{ $day->format('d') }}" data-currdate="{{ $day->format('d.m.Y') }}" data-property="{{ $property->id }}"
                                    data-reservation="{{ $property->reservation($day->format('Y-m-d'))->id }}" data-toggle="tooltip" title="{{ $property->reservation($day->format('Y-m-d'))->reservationInfo() }}">{{ $day->format('d') }}</div>
                            @elseif ($property->closed($day->format('Y-m-d')))
                                <div {{ $user->is_admin() || $property->owner == $user->id || $property->closed($day->format('Y-m-d'))->added_by_agency == $user->id ? 'onclick="openCalendarForm(this)"' : ''}} class="col px-0 text-center date-wrapper-new bg-warning {{ $property->closed($day->format('Y-m-d'))->reservation_check_out == $day->format('d.m.Y') ? 'half half-out' : ($property->closed($day->format('Y-m-d'))->reservation_check_in == $day->format('d.m.Y') ? 'half half-in' : '') }}"
                                    data-date="{{ $day->format('d') }}" data-currdate="{{ $day->format('d.m.Y') }}" data-property="{{ $property->id }}"
                                    data-reservation="{{ $property->closed($day->format('Y-m-d'))->id }}" data-toggle="tooltip" title="{{ $property->closed($day->format('Y-m-d'))->reservationInfo() }}">{{ $day->format('d') }}</div>
                            @else
                                <div onclick="openCalendarForm(this)" class="col px-0 text-center date-empty-wrapper"
                                    data-date="{{ $day->format('d') }}" data-reservation="" data-currdate="{{ $day->format('d.m.Y') }}" data-property="{{ $property->id }}">{{ $day->format('d') }}</div>
                            @endif
                        @endforeach
                    </div>

                    <div class="row mx-0 text-center calendarDaysPrice" data-pricecount="-1" data-property="{{ $property->id }}">
                        @foreach ($dates as $day)
                            <div class="col px-0 text-center dates-prices-field" data-date="{{ $day->format('d') }}">{!! $property->default_currency ? $currenciesSign[$property->default_currency] .' '. $property->pricePerPeriod($day->format('Y-m-d')) : $currenciesSign['EUR'] .' '. $property->pricePerPeriod($day->format('Y-m-d')) !!}</div>
                        @endforeach
                    </div>
                    @for ($i = 0; $i < $property->propertybedrooms->count(); $i++)
                        <div class="row mx-0 text-center calendarDaysPrice" data-pricecount="{{ $i }}" data-property="{{ $property->id }}">
                            @foreach ($dates as $day)
                                <div class="col px-0 text-center dates-prices-field" data-date="{{ $day->format('d') }}">{!! $property->default_currency ? $currenciesSign[$property->default_currency] .' '. $property->pricebedPerPeriod($day->format('Y-m-d')) : $currenciesSign['EUR'] .' '. $property->pricebedPerPeriod($day->format('Y-m-d')) !!}</div>
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    @endforeach
</div>
