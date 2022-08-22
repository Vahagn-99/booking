<form action="{{ route('save-customizes') }}" method="post">
    @csrf
    <input type="hidden" name="hash" value="">
    <div class="form-group">
        <h4>{!! __('messages.Featured properties slider settings') !!}</h4>
    </div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#featured_en">{!! __('messages.En') !!}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#featured_fr">{!! __('messages.Fr') !!}</a>
        </li>
    </ul>
    <div class="tab-content pt-3">
        <div id="featured_en" class="tab-pane active">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="futured_block_name">{!! __('messages.Block title') !!}</label>
                        <input type="text" name="website-home-featured-properties-settings&block_name" value="{!! old('website-home-featured-properties-settings&block_name') ? old('website-home-featured-properties-settings&block_name') : ($featuredPropertiesSettings ? $featuredPropertiesSettings->block_name : '') !!}" class="form-control" id="futured_block_name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="futured_block_subname">{!! __('messages.Block subtitle') !!}</label>
                        <input type="text" name="website-home-featured-properties-settings&block_subname" value="{!! old('website-home-featured-properties-settings&block_subname') ? old('website-home-featured-properties-settings&block_subname') : ($featuredPropertiesSettings ? $featuredPropertiesSettings->block_subname : '') !!}" class="form-control" id="futured_block_subname">
                    </div>
                </div>
            </div>
        </div>
        <div id="featured_fr" class="tab-pane fade">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="futured_block_name">{!! __('messages.Block title') !!}</label>
                        <input type="text" name="website-home-featured-properties-settings&block_name_fr" value="{!! old('website-home-featured-properties-settings&block_name_fr') ? old('website-home-featured-properties-settings&block_name_fr') : ($featuredPropertiesSettings ? $featuredPropertiesSettings->block_name_fr : '') !!}" class="form-control" id="futured_block_name_fr">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="futured_block_subname">{!! __('messages.Block subtitle') !!}</label>
                        <input type="text" name="website-home-featured-properties-settings&block_subname_fr" value="{!! old('website-home-featured-properties-settings&block_subname_fr') ? old('website-home-featured-properties-settings&block_subname_fr') : ($featuredPropertiesSettings ? $featuredPropertiesSettings->block_subname_fr : '') !!}" class="form-control" id="futured_block_subname_fr">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="slides_per_row">{!! __('messages.Slides per row') !!}</label>
                <input type="text" name="website-home-featured-properties-settings&slides_per_row" value="{!! old('website-home-featured-properties-settings&slides_per_row') ? old('website-home-featured-properties-settings&slides_per_row') : ($featuredPropertiesSettings ? $featuredPropertiesSettings->slides_per_row : '') !!}" class="form-control" id="slides_per_row">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="autoplay_speed">{!! __('messages.Autoplay speed (seconds)') !!}</label>
                <input type="text" name="website-home-featured-properties-settings&autoplay_speed" value="{{ old('website-home-featured-properties-settings&autoplay_speed') ? old('website-home-featured-properties-settings&autoplay_speed') : ($featuredPropertiesSettings ? $featuredPropertiesSettings->autoplay_speed : '') }}" class="form-control" id="autoplay_speed">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="navigation_type">{!! __('messages.Navigation type') !!}</label>
                <select class="form-control" id="navigation_type" name="website-home-featured-properties-settings&navigation_type">
                    <option value="no" {{ old('website-home-featured-properties-settings&navigation_type') && old('website-home-featured-properties-settings&navigation_type') == 'no' ? 'selected' : ($featuredPropertiesSettings && $featuredPropertiesSettings->navigation_type == 'no' ? 'selected' : '') }}>{!! __('messages.No navigation') !!}</option>
                    <option value="arrows" {{ old('website-home-featured-properties-settings&navigation_type') && old('website-home-featured-properties-settings&navigation_type') == 'arrows' ? 'selected' : ($featuredPropertiesSettings && $featuredPropertiesSettings->navigation_type == 'arrows' ? 'selected' : '') }}>{!! __('messages.Arrows') !!}</option>
                    <option value="bullets" {{ old('website-home-featured-properties-settings&navigation_type') && old('website-home-featured-properties-settings&navigation_type') == 'bullets' ? 'selected' : ($featuredPropertiesSettings && $featuredPropertiesSettings->navigation_type == 'bullets' ? 'selected' : '') }}>{!! __('messages.Bullets') !!}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            @for ($i = 0; $i < count($featuredProperties); $i++)
                <div class="col-md-11 d-flex items-end">
                    <div class="form-group w-100">
                        <label for="property_{{ $i }}">{!! __('messages.Property') !!}</label>
                        <input type="text" name="website-home-featured-properties&property&{{ $featuredProperties[$i]->id }}" value="{!! $featuredProperties[$i]->propertyInfo() ? $featuredProperties[$i]->propertyInfo()->name : '' !!}" class="form-control" id="property_{{ $i }}" disabled>
                    </div>
                </div>
                <div class="col-md-1 d-flex items-end">
                    <div class="form-group">
                        <button class="btn btn-danger admin-delete-el w-100" data-id="{{ $featuredProperties[$i]->id }}" type="button"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            @endfor
        </div>
        <div class="text-left">
            <button type="button" class="btn btn-sm btn-primary mt-2" data-toggle="modal" data-target="#addProperty">{!! __('messages.Add property') !!}</button>
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
    </div>
</form>
