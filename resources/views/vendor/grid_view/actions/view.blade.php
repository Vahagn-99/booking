<div class="col-lg-{!! $bootstrapColWidth-1 !!}">
    <a class="btn btn-success btn-sm" href="{{ route('admin/calendar', ['id' => $url]) }}" @if(!empty($htmlAttributes)) {!! $htmlAttributes !!} @endif  title="Calendar">
        <i class="far fa-calendar-alt"></i>
    </a>
</div>
<div class="col-lg-{!! $bootstrapColWidth-1 !!}">
    <a class="btn btn-warning btn-sm" href="{{ route('admin/property-reservations', ['id' => $url]) }}" title="Reservations">
        <i class="fas fa-history"></i>
    </a>
</div>
