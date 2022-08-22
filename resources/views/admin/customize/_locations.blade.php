<form action="{{ route('save-customizes') }}" method="post" enctype='multipart/form-data'>
    @csrf
    <input type="hidden" name="hash" value="">
    <div class="form-group">
        <h4>{!! __('messages.List of cities on the home page') !!}</h4>
    </div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#nameen">{!! __('messages.En') !!}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#namefr">{!! __('messages.Fr') !!}</a>
        </li>
    </ul>
    <div class="tab-content pt-3">
        <div id="nameen" class="tab-pane active">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cities_block_name">{!! __('messages.Block title') !!}</label>
                        <input type="text" name="website-home-city-list-settings&block_name" value="{!! old('website-home-city-list-settings&block_name') ? old('website-home-city-list-settings&block_name') : ($listCitiesSettings ? $listCitiesSettings->block_name : '') !!}" class="form-control" id="cities_block_name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cities_block_subname">{!! __('messages.Block subtitle') !!}</label>
                        <input type="text" name="website-home-city-list-settings&block_subname" value="{!! old('website-home-city-list-settings&block_subname') ? old('website-home-city-list-settings&block_subname') : ($listCitiesSettings ? $listCitiesSettings->block_subname : '') !!}" class="form-control" id="cities_block_subname">
                    </div>
                </div>
            </div>
        </div>
        <div id="namefr" class="tab-pane fade">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cities_block_name">{!! __('messages.Block title') !!}</label>
                        <input type="text" name="website-home-city-list-settings&block_name_fr" value="{!! old('website-home-city-list-settings&block_name_fr') ? old('website-home-city-list-settings&block_name_fr') : ($listCitiesSettings ? $listCitiesSettings->block_name_fr : '') !!}" class="form-control" id="cities_block_name_fr">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cities_block_subname">{!! __('messages.Block subtitle') !!}</label>
                        <input type="text" name="website-home-city-list-settings&block_subname_fr" value="{!! old('website-home-city-list-settings&block_subname_fr') ? old('website-home-city-list-settings&block_subname_fr') : ($listCitiesSettings ? $listCitiesSettings->block_subname_fr : '') !!}" class="form-control" id="cities_block_subname_fr">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        @for ($i = 0; $i < count($homePageListCities); $i++)
            <div class="row">
                <div class="col-md-5 d-flex items-end">
                    <div class="form-group w-100">
                        <label for="city_img_{{ $i }}">{!! __('messages.City photo') !!}</label>
                        <input type="file" name="website-home-city-list&photo&{{ $homePageListCities[$i]->id }}" class="form-control-file" id="city_img_{{ $i }}">
                    </div>
                    @if ($homePageListCities[$i]->photo != "")
                        <img src="{{ asset($homePageListCities[$i]->photo. '?' . time()) }}" class="img-fluid mb-5" alt="Responsive image" style="max-width: 200px;max-height: 200px;">
                    @endif
                </div>
                <div class="col-md-6 d-flex items-end">
                    <div class="form-group w-100">
                        <label for="city_{{ $i }}">{!! __('messages.City') !!}</label>
                        <input type="text" name="website-home-city-list&city&{{ $homePageListCities[$i]->id }}" value="{{ $homePageListCities[$i]->city }}" class="form-control" id="city_{{ $i }}">
                    </div>
                </div>
                <div class="col-md-1 d-flex items-end">
                    <div class="form-group">
                        <button class="btn btn-danger admin-delete-el w-100" data-id="{{ $homePageListCities[$i]->id }}" type="button"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
        @endfor
        <div class="text-left">
            <button type="button" class="btn btn-sm btn-primary mt-2" data-toggle="modal" data-target="#addCity">{!! __('messages.Add city') !!}</button>
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
    </div>
</form>
