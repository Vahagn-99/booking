<div class="row mb-5">
    <div class="col-md-7">
        <h4>{!! __('messages.About your business') !!}</h4>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="business_name">{!! __('messages.Business name') !!}</label>
                    <input type="text" name="business_name" value="{!! old('business_name') ? old('business_name') : $user->business_name !!}" class="form-control @error('business_name') is-invalid @enderror" id="business_name">
                    @error('business_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="email_business">{!! __('messages.Business Email') !!}</label>
                    <input type="text" name="email_business" value="{{ old('email_business') ? old('email_business') : $user->email_business }}" class="form-control @error('email_business') is-invalid @enderror" id="email_business">
                    @error('email_business')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                    <small id="emailBusinessHelp" class="form-text text-muted">{!! __('messages.Please, provide email which clients can use to contact with you') !!}.</small>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="phone">{!! __('messages.Phone') !!}</label>
                    <input type="text" name="phone" value="{{ old('phone') ? old('phone') : $user->phone }}" class="form-control @error('phone') is-invalid @enderror" id="phone">
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="mobile">{!! __('messages.Mobile') !!}</label>
                    <input type="text" name="mobile" value="{{ old('mobile') ? old('mobile') : $user->mobile }}" class="form-control @error('mobile') is-invalid @enderror" id="mobile">
                    @error('mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="fax">{!! __('messages.Fax') !!}</label>
                    <input type="text" name="fax" value="{{ old('fax') ? old('fax') : $user->fax }}" class="form-control @error('fax') is-invalid @enderror" id="fax">
                    @error('fax')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="country">{!! __('messages.Country') !!}</label>
                    <input type="text" name="country" value="{{ old('country') ? old('country') : $user->country }}" class="form-control @error('country') is-invalid @enderror" id="country">
                    @error('country')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="state">{!! __('messages.State') !!}</label>
                    <input type="text" name="state" value="{{ old('state') ? old('state') : $user->state }}" class="form-control @error('state') is-invalid @enderror" id="state">
                    @error('state')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="city">{!! __('messages.City') !!}</label>
                    <input type="text" name="city" value="{{ old('city') ? old('city') : $user->city }}" class="form-control @error('city') is-invalid @enderror" id="city">
                    @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="address">{!! __('messages.Address') !!}</label>
                    <input type="text" name="address" value="{{ old('address') ? old('address') : $user->address }}" class="form-control @error('address') is-invalid @enderror" id="address">
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="zip">{!! __('messages.Zip') !!}</label>
                    <input type="text" name="zip" value="{{ old('zip') ? old('zip') : $user->zip }}" class="form-control @error('zip') is-invalid @enderror" id="zip">
                    @error('zip')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="place_lat">{!! __('messages.Latitude') !!}</label>
                    <input type="text" name="place_lat" value="{{ old('place_lat') ? old('place_lat') : $user->place_lat }}" class="form-control @error('place_lat') is-invalid @enderror" id="place_lat">
                    @error('place_lat')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="place_lng">{!! __('messages.Longitude') !!}</label>
                    <input type="text" name="place_lng" value="{{ old('place_lng') ? old('place_lng') : $user->place_lng }}" class="form-control @error('place_lng') is-invalid @enderror" id="place_lng">
                    @error('place_lng')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div id="map"></div>
    </div>
</div>
