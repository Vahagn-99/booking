<input type="hidden" id="calendarMonthToday" value="{{ $currentMonth->format('F Y') }}">
<div class="row">
    @foreach ($monthsArray as $dt)
        <div class="col-md-4 mb-3 mt-3">
            <div class="row mb-3">
                <div class="col-sm-12 month-name-year-admin">{!! $dt->format('F Y') !!}</div>
            </div>
            {!! $property->renderCalendar($dt) !!}
        </div>
    @endforeach
</div>
