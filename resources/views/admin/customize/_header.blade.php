<form action="{{ route('save-customizes') }}" method="post" enctype='multipart/form-data'>
    @csrf
    <input type="hidden" name="hash" value="">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="logo_img">{!! __('messages.Logo') !!}</label>
                <input type="file" name="website-header-logo&photo" class="form-control-file" id="logo_img">
            </div>
            @if ($websiteHeaderLogo && $websiteHeaderLogo->photo != "")
                <img src="{{ asset($websiteHeaderLogo->photo. '?' . time()) }}" class="img-fluid mb-5" alt="Responsive image" style="max-width: 200px;max-height: 200px;">
            @endif
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="favicon_img">{!! __('messages.Favicon') !!}</label>
                <input type="file" name="website-header-favicony&photo" class="form-control-file" id="favicon_img">
            </div>
            @if ($websiteHeaderFavicon && $websiteHeaderFavicon->photo != "")
                <img src="{{ asset($websiteHeaderFavicon->photo. '?' . time()) }}" class="img-fluid mb-5" alt="Responsive image" style="max-width: 200px;max-height: 200px;">
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <h4>{!! __('messages.Header background') !!}</h4>
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#titleen">{!! __('messages.En') !!}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#titlefr">{!! __('messages.Fr') !!}</a>
                </li>
            </ul>
            <div class="tab-content pt-3">
                <div id="titleen" class="tab-pane active">
                    <div class="form-group">
                        <label for="header_bg_title">{!! __('messages.Header background title') !!}</label>
                        <input type="text" name="website-header-background&header_title" value="{!! old('website-header-background&header_title') ? old('website-header-background&header_title') : ($websiteHeaderBack && $websiteHeaderBack->header_title ? $websiteHeaderBack->header_title : '') !!}"
                            class="form-control @error('website-header-background&header_title') is-invalid @enderror" id="header_bg_title">
                        @error('website-header-background&header_title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="header_subtitle">{!! __('messages.Header background subtitle') !!}</label>
                        <input type="text" name="website-header-background&header_subtitle" value="{!! old('website-header-background&header_subtitle') ? old('website-header-background&header_subtitle') : ($websiteHeaderBack && $websiteHeaderBack->header_subtitle ? $websiteHeaderBack->header_subtitle : '') !!}"
                            class="form-control @error('website-header-background&header_subtitle') is-invalid @enderror" id="header_subtitle">
                        @error('website-header-background&header_subtitle')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div id="titlefr" class="tab-pane fade">
                    <div class="form-group">
                        <label for="header_bg_title">{!! __('messages.Header background title') !!}</label>
                        <input type="text" name="website-header-background&header_title_fr" value="{!! old('website-header-background&header_title_fr') ? old('website-header-background&header_title_fr') : ($websiteHeaderBack && $websiteHeaderBack->header_title_fr ? $websiteHeaderBack->header_title_fr : '') !!}" class="form-control" id="header_bg_title_fr">
                    </div>
                    <div class="form-group">
                        <label for="header_subtitle">{!! __('messages.Header background subtitle') !!}</label>
                        <input type="text" name="website-header-background&header_subtitle_fr" value="{!! old('website-header-background&header_subtitle_fr') ? old('website-header-background&header_subtitle_fr') : ($websiteHeaderBack && $websiteHeaderBack->header_subtitle_fr ? $websiteHeaderBack->header_subtitle_fr : '') !!}" class="form-control" id="header_subtitle_fr">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="header_back_type">{!! __('messages.Background type') !!}</label>
                <select class="form-control" onchange="this.form.submit()" id="header_back_type" name="website-header-background&header_back_type">
                    <option value="photo" {{ old('website-header-background&header_back_type') && old('website-header-background&header_back_type') == 'photo' ? 'selected' : ($websiteHeaderBack && $websiteHeaderBack->header_back_type == 'photo' ? 'selected' : '') }}>Photo</option>
                    <option value="video" {{ old('website-header-background&header_back_type') && old('website-header-background&header_back_type') == 'video' ? 'selected' : ($websiteHeaderBack && $websiteHeaderBack->header_back_type == 'video' ? 'selected' : '') }}>Video</option>
                    <option value="slide" {{ old('website-header-background&header_back_type') && old('website-header-background&header_back_type') == 'slide' ? 'selected' : ($websiteHeaderBack && $websiteHeaderBack->header_back_type == 'slide' ? 'selected' : '') }}>Slide</option>
                </select>
            </div>
            <div class="form-video {{ old('website-header-background&header_back_type') && (old('website-header-background&header_back_type') == 'photo' || old('website-header-background&header_back_type') == 'slide') ? 'd-none' : (!old('website-header-background&header_back_type') && $websiteHeaderBack && ($websiteHeaderBack->header_back_type == 'photo' || $websiteHeaderBack->header_back_type == 'slide') ? 'd-none' : '') }}">
                <div class="form-group">
                    <label for="header_back_video_link">{!! __('messages.Paste link') !!}</label>
                    <input type="text" name="website-header-background&header_back_video_link" value="{{ old('website-header-background&header_back_video_link') ? old('website-header-background&header_back_video_link') : ($websiteHeaderBack && $websiteHeaderBack->header_back_video_link ? $websiteHeaderBack->header_back_video_link : '') }}" class="form-control" id="header_back_video_link">
                </div>
                <div class="form-group">
                    <label for="header_back_audio_status">{!! __('messages.Video audio') !!}</label>
                    <select class="form-control" id="header_back_audio_status" name="website-header-background&header_back_audio_status">
                        <option value="mute" {{ old('website-header-background&header_back_audio_status') && old('website-header-background&header_back_audio_status') == 'mute' ? 'selected' : ($websiteHeaderBack && $websiteHeaderBack->header_back_audio_status == 'mute' ? 'selected' : '') }}>Mute</option>
                        <option value="unmute" {{ old('website-header-background&header_back_audio_status') && old('website-header-background&header_back_audio_status') == 'unmute' ? 'selected' : ($websiteHeaderBack && $websiteHeaderBack->header_back_audio_status == 'unmute' ? 'selected' : '') }}>Unmute</option>
                    </select>
                </div>
            </div>
            <div class="form-photo {{ old('website-header-background&header_back_type') && (old('website-header-background&header_back_type') == 'video' || old('website-header-background&header_back_type') == 'slide') ? 'd-none' : (!old('website-header-background&header_back_type') && $websiteHeaderBack && ($websiteHeaderBack->header_back_type == 'video' || $websiteHeaderBack->header_back_type == 'slide') ? 'd-none' : '') }}">
                <div class="form-group">
                    <label for="back_img">{!! __('messages.Header background photo') !!}</label>
                    <input type="file" name="website-header-background&photo" class="form-control-file" id="back_img">
                </div>
                @if ($websiteHeaderBack && $websiteHeaderBack->photo != "")
                    <img src="{{ asset($websiteHeaderBack->photo. '?' . time()) }}" class="img-fluid mb-5" alt="Responsive image" style="max-width: 300px;max-height: 300px;">
                @endif
            </div>
            <div class="form-slide {{ old('website-header-background&header_back_type') && (old('website-header-background&header_back_type') == 'video' || old('website-header-background&header_back_type') == 'photo') ? 'd-none' : (!old('website-header-background&header_back_type') && $websiteHeaderBack && ($websiteHeaderBack->header_back_type == 'video' || $websiteHeaderBack->header_back_type == 'photo') ? 'd-none' : '') }}">
                <div class="form-group">
                    <div class="text-left">
                        <button type="button" class="btn btn-sm btn-primary mt-2" data-toggle="modal" data-target="#addphoto">{!! __('messages.Add slide photo') !!}</button>
                    </div>
                </div>
                <div class="row mb-3" id="add_photos" data-action="{{ route('change-ord') }}">
                    @foreach ($slidephotos as $photo)
                        <div class="col-md-4 mb-2">
                            <div class="photo-block list-group-item" data-id="{{ $photo->id }}">
                                @if ($photo)
                                    <img src="{{ asset($photo->photo) }}" class="img-fluid" alt="Responsive image">
                                @endif
                                <button type="button" class="btn btn-sm btn-danger photo-delete-el"
                                    data-title="{!! __('messages.Remove slide photo') !!}" data-id="{{ $photo->id }}"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <h4>{!! __('messages.Header menu') !!}</h4>
            <div class="form-group">
                @for ($i = 0; $i < count($websiteHeaderMenu); $i++)
                    <div class="row">
                        @if ($websiteHeaderMenu[$i]->link_type == "custom")
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="link_name_{{ $i }}">{!! __('messages.Link title') !!}</label>
                                    <input type="text" name="website-header-menu&link_name&{{ $websiteHeaderMenu[$i]->id }}" value="{!! $websiteHeaderMenu[$i]->link_name !!}" class="form-control" id="link_name_{{ $i }}">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="link_{{ $i }}">{!! __('messages.Link URL') !!}</label>
                                    <input type="text" name="website-header-menu&link&{{ $websiteHeaderMenu[$i]->id }}" value="{!! $websiteHeaderMenu[$i]->link !!}" class="form-control" id="link_{{ $i }}">
                                </div>
                            </div>
                        @elseif ($websiteHeaderMenu[$i]->link_type == "city")
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="link_name_{{ $i }}">{!! __('messages.Link title') !!}</label>
                                    <input type="text" name="website-header-menu&link_name&{{ $websiteHeaderMenu[$i]->id }}" value="{!! $websiteHeaderMenu[$i]->link_name !!}" class="form-control" id="link_name_{{ $i }}">
                                </div>
                            </div>
                        @endif
                        <div class="col-md-1 d-flex items-end">
                            <div class="form-group">
                                <button class="btn btn-danger admin-delete-el w-100" data-id="{{ $websiteHeaderMenu[$i]->id }}" type="button"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                @endfor
                <div class="text-left">
                    <button type="button" class="btn btn-sm btn-primary mt-2 admin-add-el" data-name="website-header-menu">{!! __('messages.Add link') !!}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right">
        <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
    </div>
</form>
