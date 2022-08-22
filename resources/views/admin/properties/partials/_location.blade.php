<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label for="country">{!! __('messages.Country') !!}</label>
            <input type="text" name="country" value="{{ old('country') ? old('country') : ($property ? $property->country : '') }}" class="form-control @error('country') is-invalid @enderror" id="country">
            @error('country')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="state">{!! __('messages.State') !!}</label>
            <input type="text" name="state" value="{{ old('state') ? old('state') : ($property ? $property->state : '') }}" class="form-control @error('state') is-invalid @enderror" id="state">
            @error('state')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="city">{!! __('messages.City') !!}</label>
            <input type="text" name="city" value="{{ old('city') ? old('city') : ($property ? $property->city : '') }}" class="form-control @error('city') is-invalid @enderror" id="city">
            @error('city')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="zip">{!! __('messages.Zip') !!}</label>
            <input type="number" name="zip" value="{{ old('zip') ? old('zip') : ($property ? $property->zip : '') }}" class="form-control @error('zip') is-invalid @enderror" id="zip">
            @error('zip')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="address">{!! __('messages.Address') !!}</label>
            <input type="text" name="address" value="{{ old('address') ? old('address') : ($property ? $property->address : '') }}" class="form-control @error('address') is-invalid @enderror" id="address">
            @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-7">
        <div id="map"></div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="place_lat">{!! __('messages.Latitude') !!}</label>
                    <input type="text" name="lat" value="{{ old('lat') ? old('lat') : ($property ? $property->lat : '') }}" class="form-control @error('lat') is-invalid @enderror" id="place_lat">
                    @error('lat')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="place_lng">{!! __('messages.Longitude') !!}</label>
                    <input type="text" name="lng" value="{{ old('lng') ? old('lng') : ($property ? $property->lng : '') }}" class="form-control @error('lng') is-invalid @enderror" id="place_lng">
                    @error('lng')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-right">
    <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
</div>
