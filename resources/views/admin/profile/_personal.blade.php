<div class="row mb-5">
    <div class="col-md-6">
        <h4>{!! __('messages.Personal information') !!}</h4>
        <div class="form-group">
            <label for="first_name">{!! __('messages.First name') !!}</label>
            <input type="text" name="first_name" value="{!! old('first_name') ? old('first_name') : $user->first_name !!}" class="form-control @error('first_name') is-invalid @enderror" id="first_name">
            @error('first_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="last_name">{!! __('messages.Last name') !!}</label>
            <input type="text" name="last_name" value="{!! old('last_name') ? old('last_name') : $user->last_name !!}" class="form-control @error('last_name') is-invalid @enderror" id="last_name">
            @error('last_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">{!! __('messages.Email') !!}</label>
            <input type="text" name="email" value="{!! old('email') ? old('email') : $user->email !!}" class="form-control @error('email') is-invalid @enderror" id="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
            <small class="form-text text-muted">{!! __('messages.Please, provide email which we can use to contact with you') !!}.</small>
        </div>
        @if (!$user->is_admin())
            <div class="form-group">
                <label for="subdomain">{!! __('messages.Subdomain') !!} {{ $user->subdomain_status ? '('.$user->subdomain_status.')' : '' }}</label>
                <div class="input-group">
                    <div>
                        <input type="text" name="subdomain" value="{{ old('subdomain') ? old('subdomain') : $user->subdomain }}" class="form-control @error('subdomain') is-invalid @enderror" id="subdomain">
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text">.{{ config('app.mainURL') }}</span>
                    </div>
                </div>
                @error('subdomain')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <h4>{!! __('messages.Booking settings') !!}</h4>
        <div class="form-group">
            <label for="default_check_in">{!! __('messages.Default check-in start time') !!}</label>
            <input type="text" name="default_check_in" value="{{ old('default_check_in') ? old('default_check_in') : $user->default_check_in }}" class="form-control @error('default_check_in') is-invalid @enderror" id="default_check_in">
            @error('default_check_in')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="default_check_out">{!! __('messages.Default check-out time') !!}</label>
            <input type="text" name="default_check_out" value="{{ old('default_check_out') ? old('default_check_out') : $user->default_check_out }}" class="form-control @error('default_check_out') is-invalid @enderror" id="default_check_out">
            @error('default_check_out')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="default_language">{!! __('messages.Default communication language') !!}</label>
            <select class="form-control @error('default_language') is-invalid @enderror" id="default_language" name="default_language">
                <option value="English" {{ old('default_language') && old('default_language') == 'English' ? 'selected' : ($user->default_language == 'English' ? 'selected' : '') }}>English</option>
                <option value="Fran&#231;ais" {{ old('default_language') && old('default_language') != 'English' ? 'selected' : ($user->default_language != 'English' ? 'selected' : '') }}>Fran&#231;ais</option>
            </select>
            @error('default_language')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
