<h4>{!! __('messages.Work schedule') !!}</h4>
<div class="form-group mb-4">
    <label for="work_schedule_type">{!! __('messages.Work schedule type') !!}</label>
    <select class="form-control @error('work_schedule_type') is-invalid @enderror" id="work_schedule_type" name="work_schedule_type">
        <option value="same" {{ old('work_schedule_type') && old('work_schedule_type') == 'same' ? 'selected' : (!old('work_schedule_type') && $user->work_schedule_type == 'same' ? 'selected' : '') }}>{!! __('messages.There is the same work time from Monday to Friday') !!}</option>
        <option value="different" {{ old('work_schedule_type') && old('work_schedule_type') == 'different' ? 'selected' : (!old('work_schedule_type') && $user->work_schedule_type == 'different' ? 'selected' : '') }}>{!! __('messages.There is the different work time from Monday to Friday') !!}</option>
    </select>
    @error('work_schedule_type')
        <span class="invalid-feedback" role="alert">
            <strong>{!! $message !!}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-4">
    <h5 class="mb-0">{!! __('messages.Monday') !!}</h5>
    <small class="form-text text-muted">{!! __('messages.If you do not work on this day, leave these fields empty.') !!}</small>
    <input type="hidden" name="mon_work_time" value="{{ $user->mon_work_time }}">
    <div class="row">
        <div class="col-md-6">
            <label class="mt-2">{!! __('messages.Start work day') !!}</label>
            <input type="text" value="{{ $user->mon_work_time && count(explode(' - ',$user->mon_work_time)) > 0 ? explode(' - ',$user->mon_work_time)[0] : '' }}" class="form-control start_time">
        </div>
        <div class="col-md-6">
            <label class="mt-2">{!! __('messages.End work day') !!}</label>
            <input type="text" value="{{ $user->mon_work_time && count(explode(' - ',$user->mon_work_time)) > 1 ? explode(' - ',$user->mon_work_time)[1] : '' }}" class="form-control end_time">
        </div>
    </div>
</div>
<div class="different-block {{ old('work_schedule_type') && old('work_schedule_type') == 'same' ? 'd-none' : (!old('work_schedule_type') && $user->work_schedule_type == 'same' ? 'd-none' : '') }}">
    <div class="form-group mb-4">
        <h5 class="mb-0">{!! __('messages.Tuesday') !!}</h5>
        <small class="form-text text-muted">{!! __('messages.If you do not work on this day, leave these fields empty.') !!}</small>
        <input type="hidden" name="tue_work_time" value="{{ $user->tue_work_time }}">
        <div class="row">
            <div class="col-md-6">
                <label class="mt-2">{!! __('messages.Start work day') !!}</label>
                <input type="text" value="{{ $user->tue_work_time && count(explode(' - ',$user->tue_work_time)) > 0 ? explode(' - ',$user->tue_work_time)[0] : '' }}" class="form-control start_time">
            </div>
            <div class="col-md-6">
                <label class="mt-2">{!! __('messages.End work day') !!}</label>
                <input type="text" value="{{ $user->tue_work_time && count(explode(' - ',$user->tue_work_time)) > 1 ? explode(' - ',$user->tue_work_time)[1] : '' }}" class="form-control end_time">
            </div>
        </div>
    </div>
    <div class="form-group mb-4">
        <h5 class="mb-0">{!! __('messages.Wednesday') !!}</h5>
        <small class="form-text text-muted">{!! __('messages.If you do not work on this day, leave these fields empty.') !!}</small>
        <input type="hidden" name="wed_work_time" value="{{ $user->wed_work_time }}">
        <div class="row">
            <div class="col-md-6">
                <label class="mt-2">{!! __('messages.Start work day') !!}</label>
                <input type="text" value="{{ $user->wed_work_time && count(explode(' - ',$user->wed_work_time)) > 0 ? explode(' - ',$user->wed_work_time)[0] : '' }}" class="form-control start_time">
            </div>
            <div class="col-md-6">
                <label class="mt-2">{!! __('messages.End work day') !!}</label>
                <input type="text" value="{{ $user->wed_work_time && count(explode(' - ',$user->wed_work_time)) > 1 ? explode(' - ',$user->wed_work_time)[1] : '' }}" class="form-control end_time">
            </div>
        </div>
    </div>
    <div class="form-group mb-4">
        <h5 class="mb-0">{!! __('messages.Thursday') !!}</h5>
        <small class="form-text text-muted">{!! __('messages.If you do not work on this day, leave these fields empty.') !!}</small>
        <input type="hidden" name="thu_work_time" value="{{ $user->thu_work_time }}">
        <div class="row">
            <div class="col-md-6">
                <label class="mt-2">{!! __('messages.Start work day') !!}</label>
                <input type="text" value="{{ $user->thu_work_time && count(explode(' - ',$user->thu_work_time)) > 0 ? explode(' - ',$user->thu_work_time)[0] : '' }}" class="form-control start_time">
            </div>
            <div class="col-md-6">
                <label class="mt-2">{!! __('messages.End work day') !!}</label>
                <input type="text" value="{{ $user->thu_work_time && count(explode(' - ',$user->thu_work_time)) > 1 ? explode(' - ',$user->thu_work_time)[1] : '' }}" class="form-control end_time">
            </div>
        </div>
    </div>
    <div class="form-group mb-4">
        <h5 class="mb-0">{!! __('messages.Friday') !!}</h5>
        <small class="form-text text-muted">{!! __('messages.If you do not work on this day, leave these fields empty.') !!}</small>
        <input type="hidden" name="fri_work_time" value="{{ $user->fri_work_time }}">
        <div class="row">
            <div class="col-md-6">
                <label class="mt-2">{!! __('messages.Start work day') !!}</label>
                <input type="text" value="{{ $user->fri_work_time && count(explode(' - ',$user->fri_work_time)) > 0 ? explode(' - ',$user->fri_work_time)[0] : '' }}" class="form-control start_time">
            </div>
            <div class="col-md-6">
                <label class="mt-2">{!! __('messages.End work day') !!}</label>
                <input type="text" value="{{ $user->fri_work_time && count(explode(' - ',$user->fri_work_time)) > 1 ? explode(' - ',$user->fri_work_time)[1] : '' }}" class="form-control end_time">
            </div>
        </div>
    </div>
</div>
<div class="form-group mb-4">
    <h5 class="mb-0">{!! __('messages.Saturday') !!}</h5>
    <small class="form-text text-muted">{!! __('messages.If you do not work on this day, leave these fields empty.') !!}</small>
    <input type="hidden" name="sat_work_time" value="{{ $user->sat_work_time }}">
    <div class="row">
        <div class="col-md-6">
            <label class="mt-2">{!! __('messages.Start work day') !!}</label>
            <input type="text" value="{{ $user->sat_work_time && count(explode(' - ',$user->sat_work_time)) > 0 ? explode(' - ',$user->sat_work_time)[0] : '' }}" class="form-control start_time">
        </div>
        <div class="col-md-6">
            <label class="mt-2">{!! __('messages.End work day') !!}</label>
            <input type="text" value="{{ $user->sat_work_time && count(explode(' - ',$user->sat_work_time)) > 1 ? explode(' - ',$user->sat_work_time)[1] : '' }}" class="form-control end_time">
        </div>
    </div>
</div>
<div class="form-group">
    <h5 class="mb-0">{!! __('messages.Sunday') !!}</h5>
    <small class="form-text text-muted">{!! __('messages.If you do not work on this day, leave these fields empty.') !!}</small>
    <input type="hidden" name="sun_work_time" value="{{ $user->sun_work_time }}">
    <div class="row">
        <div class="col-md-6">
            <label class="mt-2">{!! __('messages.Start work day') !!}</label>
            <input type="text" value="{{ $user->sun_work_time && count(explode(' - ',$user->sun_work_time)) > 0 ? explode(' - ',$user->sun_work_time)[0] : '' }}" class="form-control start_time">
        </div>
        <div class="col-md-6">
            <label class="mt-2">{!! __('messages.End work day') !!}</label>
            <input type="text" value="{{ $user->sun_work_time && count(explode(' - ',$user->sun_work_time)) > 1 ? explode(' - ',$user->sun_work_time)[1] : '' }}" class="form-control end_time">
        </div>
    </div>
</div>
