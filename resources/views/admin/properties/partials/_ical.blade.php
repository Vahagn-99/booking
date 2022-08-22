<h5>{!! __('messages.iCal export') !!}</h5>
<p>{{ $property ? route('export-calendar', ['id' => $property ? $property->id : '']) : '' }}</p>
<hr>
<h5>{!! __('messages.iCal import') !!}</h5>
<div class="col-12 px-0">
    @if ($icals && count($icals) > 0)
        @foreach ($icals as $ical)
            <div class="row mx-0 align-items-end mb-4">
                <div class="col-4 pl-0 pr-2">
                    <div class="">
                        <label>{!! __('messages.Name') !!}</label>
                        <input name="deleteIcal[{{$loop->index}}][name]" value="{{$ical->name}}" type="text" class="form-control ical-name" readonly>
                    </div>
                </div>
                <div class="col-4 pl-0 pr-2">
                    <div class="">
                        <label>{!! __('messages.Link') !!}</label>
                        <input name="deleteIcal[{{$loop->index}}][link]" value="{{$ical->link}}" type="text" class="form-control ical-link" readonly>
                    </div>
                </div>
                <div class="col-2">
                    <div></div>
                    <button name="deleteIcal[{{$loop->index}}][delete]" class="btn btn-danger delete-ical">{!! __('messages.Delete') !!}</button>
                </div>
            </div>
        @endforeach
    @endif
    <div class="row mx-0 align-items-end">
        <div class="col-4 pl-0 pr-2">
            <div class="">
                <label>{!! __('messages.Name') !!}</label>
                <input name="icalName" value="" type="text" class="form-control ical-name">
            </div>
        </div>
        <div class="col-4 pl-0 pr-2">
            <div class="">
                <label>{!! __('messages.Link') !!}</label>
                <input name="icalLink" value="" type="text" class="form-control ical-link">
            </div>
        </div>
        <div class="col-2">
            <div></div>
            <button name="icalAdd" class="btn btn-primary import-ical">{!! __('messages.Import') !!}</button>
        </div>
    </div>
</div>
<div class="text-right">
    <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
</div>
