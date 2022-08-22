<div class="row">
    <div class="col-md-6">
        <h4>{!! __('messages.Owner details') !!}</h4>
        <div class="list-group">
            <p class="mb-0 list-group-item list-group-item-action">{!! __('messages.Full name') !!}: <i>{!! $property && $property->ownerInfo ? $property->ownerInfo->fullName() : 'not set' !!}</i></p>
            <p class="mb-0 list-group-item list-group-item-action">{!! __('messages.Email') !!}: <i>{!! $property && $property->ownerInfo ? $property->ownerInfo->email : 'not set' !!}</i></p>
        </div>
    </div>
    @if($user->is_admin())
        <div class="col-md-6">
            <h4>{!! __('messages.Select owner') !!}</h4>
            <div class="form-group">
                <label for="owner">{!! __('messages.List of possible owners') !!}</label>
                <select name="owner" class="form-control @error('owner') is-invalid @enderror" id="owner">
                    <option value="">{!! __('messages.Choose') !!}...</option>
                    @foreach (\App\Models\Properties::ownersList() as $key => $value)
                        <option value="{{ $key }}" {{ old('owner') && old('owner') == $key ? 'selected' : ($property && $property->owner == $key ? 'selected' : '') }}>{!! $value !!}</option>
                    @endforeach
                </select>
                @error('owner')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
        </div>
    @endif
</div>
<div class="text-right">
    <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
</div>
<div class="row">
    <div class="col-md-12 mt-5">
        <h4>{!! __('messages.Agencies') !!}</h4>
        @if ($property && $property->agencywish->count() > 0)
            @if ($property && $property->agencyproperties->count() > 0)
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-right mb-3">
                            <button type="button" class="btn btn-danger stop-all stop-agency" data-id="{{ $property->id }}">{!! __('messages.Stop to work with all agencies') !!}</button>
                        </div>
                        <div class="list-group">
                            @foreach ($property->agencyproperties as $pa)
                                @if ($pa->agensyInfo())
                                    <div class="list-group-item list-group-item-action">
                                        {{ $pa->agensyName() }}
                                        <button type="button" class="btn btn-warning float-right stop-agency" data-id="{{ $pa->id }}">{!! __('messages.Stop to work with agency') !!}</button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <p>{!! __('messages.No agencies have worked with this property yet') !!}</p>
            @endif
        @else
            <p>{!! __('messages.This property does not work with agencies') !!}</p>
        @endif
    </div>
</div>
