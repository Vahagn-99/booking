<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#enbase">{!! __('messages.En') !!}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#frbase">{!! __('messages.Fr') !!}</a>
    </li>
</ul>
<div class="row">
    <div class="col-md-4">
        <div class="tab-content">
            <div id="enbase" class="tab-pane active">
                <div class="form-group">
                    <label for="name">{!! __('messages.Name') !!}</label>
                    <input type="text" name="name" value="{{ old('name') ? old('name') : ($property ? $property->name : '') }}" class="form-control @error('name') is-invalid @enderror" id="name">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div id="frbase" class="tab-pane fade">
                <div class="form-group">
                    <label for="name_fr">{!! __('messages.Name') !!}</label>
                    <input type="text" name="name_fr" value="{{ old('name_fr') ? old('name_fr') : ($property ? $property->name_fr : '') }}" class="form-control @error('name_fr') is-invalid @enderror" id="name_fr">
                    @error('name_fr')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="rental_type">{!! __('messages.Rental Type') !!}</label>
            <select id="rental_type" class="form-control @error('rental_type') is-invalid @enderror" name="rental_type">
                <option value="">{!! __('messages.Choose') !!}...</option>
                @foreach ($rentalTypes as $key => $value)
                    <option value="{{ $value }}" {{ old('rental_type') && old('rental_type') == $value ? 'selected' : ($property && $property->rental_type == $value ? 'selected' : '') }}>{{ $key }}</option>
                @endforeach
            </select>
            @error('rental_type')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="name">{!! __('messages.Residency Category') !!}</label>
            <select id="residency_category" class="form-control @error('residency_category') is-invalid @enderror" name="residency_category">
                <option value="">{!! __('messages.Choose') !!}...</option>
                <option value="Primary residence" {{ old('residency_category') && old('residency_category') == "Primary residence" ? 'selected' : ($property && $property->residency_category == "Primary residence" ? 'selected' : '') }}>{!! __('messages.Primary residence') !!}</option>
                <option value="Secondary residence" {{ old('residency_category') && old('residency_category') == "Secondary residence" ? 'selected' : ($property && $property->residency_category == "Secondary residence" ? 'selected' : '') }}>{!! __('messages.Secondary residence') !!}</option>
                <option value="Non residential" {{ old('residency_category') && old('residency_category') == "Non residential" ? 'selected' : ($property && $property->residency_category == "Non residential" ? 'selected' : '') }}>{!! __('messages.Non residential') !!}</option>
            </select>
            @error('residency_category')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="sleeps">{!! __('messages.Sleeps') !!}</label>
            <input type="number" name="sleeps" value="{{ old('sleeps') ? old('sleeps') : ($property ? $property->sleeps : '') }}" class="form-control @error('sleeps') is-invalid @enderror" id="sleeps">
            @error('sleeps')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="sleeps_max">{!! __('messages.Sleeps Max') !!}</label>
            <input type="number" name="sleeps_max" value="{{ old('sleeps_max') ? old('sleeps_max') : ($property ? $property->sleeps_max : '') }}" class="form-control @error('sleeps_max') is-invalid @enderror" id="sleeps_max">
            @error('sleeps_max')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="area">{!! __('messages.Area') !!}</label>
            <input type="number" name="area" value="{{ old('area') ? old('area') : ($property ? $property->area : '') }}" class="form-control @error('area') is-invalid @enderror" id="area">
            @error('area')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="area_unit">{!! __('messages.Area Unit') !!}</label>
            <select id="area_unit" class="form-control @error('area_unit') is-invalid @enderror" name="area_unit">
                <option value="">{!! __('messages.Choose') !!}...</option>
                <option value="m" {{ old('area_unit') && old('area_unit') == "m" ? 'selected' : ($property && $property->area_unit == "m" ? 'selected' : '') }}>m&sup2;</option>
                <option value="ft" {{ old('area_unit') && old('area_unit') == "ft" ? 'selected' : ($property && $property->area_unit == "ft" ? 'selected' : '') }}>ft&sup2;</option>
            </select>
            @error('area_unit')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="stories">{!! __('messages.Stories') !!}</label>
            <input type="number" name="stories" value="{{ old('stories') ? old('stories') : ($property ? $property->stories : '') }}" class="form-control @error('stories') is-invalid @enderror" id="stories">
            @error('stories')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="floor_number">{!! __('messages.Floor Number') !!}</label>
            <input type="number" name="floor_number" value="{{ old('floor_number') ? old('floor_number') : ($property ? $property->floor_number : '') }}" class="form-control @error('floor_number') is-invalid @enderror" id="floor_number">
            @error('floor_number')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="licence">{!! __('messages.Permit/Licence/Registration Number') !!}</label>
            <input type="text" name="licence" value="{{ old('licence') ? old('licence') : ($property ? $property->licence : '') }}" class="form-control @error('licence') is-invalid @enderror" id="licence">
            @error('licence')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="certifications">{!! __('messages.Certifications') !!}</label>
            <input type="text" name="certifications" value="{{ old('certifications') ? old('certifications') : ($property ? $property->certifications : '') }}" class="form-control @error('certifications') is-invalid @enderror" id="certifications">
            @error('certifications')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<hr>
<h5 class="mb-3">{!! __('messages.Similar properties') !!}</h5>
<i><b>{!! __('messages.At the bottom of the page') !!}</b></i>
<div class="row">
    @for ($i = 0; $i < 2; $i++)
        <div class="col-md-6">
            <div class="form-group">
                <label for="similar_{{ $i }}">{!! __('messages.Property') . ' #' . ($i+1) !!}</label>
                <select id="similar_{{ $i }}" class="form-control" name="similar_{{ $i }}">
                    <option value="">{!! __('messages.Choose') !!}...</option>
                    @foreach ($properties as $key => $value)
                        <option value="{{ $value }}" {{ old('similar_'.$i) && old('similar_'.$i) == $value ? 'selected' : ($property && $property->similar($i) && $property->similar($i)->similar_id == $value ? 'selected' : '') }}>{{ $key }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endfor
</div>
<i><b>{!! __('messages.Right side of the page') !!}</b></i>
<div class="row">
    @for ($i = 2; $i < 5; $i++)
        <div class="col-md-6">
            <div class="form-group">
                <label for="similar_{{ $i }}">{!! __('messages.Property') . ' #' . ($i+1) !!}</label>
                <select id="similar_{{ $i }}" class="form-control" name="similar_{{ $i }}">
                    <option value="">{!! __('messages.Choose') !!}...</option>
                    @foreach ($properties as $key => $value)
                        <option value="{{ $value }}" {{ old('similar_'.$i) && old('similar_'.$i) == $value ? 'selected' : ($property && $property->similar($i) && $property->similar($i)->similar_id == $value ? 'selected' : '') }}>{{ $key }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endfor
</div>
<div class="text-right">
    <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
</div>
<hr>
<div class="row mt-3 mb-3">
    <div class="col-sm-4">
        <h5>{!! __('messages.Bedrooms') !!}</h5>
        <button type="button" class="btn btn-sm btn-primary open-modal my-2"
                data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Add bedroom') !!}"
                data-model_name="Bedrooms" data-modal="_bedroom" data-model_id="" {{ !$property ? 'disabled' : '' }}
                >{!! __('messages.Add bedroom') !!}</button>
        @if ($property && $property->bedrooms->count() > 0)
            <div class="list-group">
                @foreach ($property->bedrooms as $bed)
                    <div class="list-group-item list-group-item-action">
                        <div class="float-left">
                            <p>{{ $bed->name }}</p>
                            <i>{{ $bed->infoText() }}</i>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-sm btn-success open-modal"
                                data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Edit bedroom') !!}"
                                data-model_name="Bedrooms" data-modal="_bedroom" data-model_id="{{ $bed->id }}"
                                ><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-danger open-modal"
                                data-title="{!! __('messages.Remove bedroom') !!}" data-property="{{ $property ? $property->id :'' }}" data-modal="_delete"
                                data-model_name="Bedrooms" data-model_id="{{ $bed->id }}"
                                ><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="col-sm-4">
        <h5>{!! __('messages.Bathrooms') !!}</h5>
        <button type="button" class="btn btn-sm btn-primary open-modal my-2"
                data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Add bathroom') !!}"
                data-model_name="Bathrooms" data-modal="_bathroom" data-model_id="" {{ !$property ? 'disabled' : '' }}
                >{!! __('messages.Add bathroom') !!}</button>
        @if ($property && $property->baths_count > 0)
            <div class="list-group">
                @foreach ($property->bathrooms as $bath)
                    <div class="list-group-item list-group-item-action">
                        <div class="float-left">
                            <p>{{ $bath->name }}</p>
                            <i>{{ $bath->infoText() }}</i>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-sm btn-success open-modal"
                                data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Edit bathroom') !!}"
                                data-model_name="Bathrooms" data-modal="_bathroom" data-model_id="{{ $bath->id }}"
                                ><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-danger open-modal"
                                data-title="{!! __('messages.Remove bathroom') !!}" data-property="{{ $property ? $property->id :'' }}" data-modal="_delete"
                                data-model_name="Bathrooms" data-model_id="{{ $bath->id }}"
                                ><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="col-sm-4">
        <h5>{!! __('messages.Living rooms') !!}</h5>
        <button type="button" class="btn btn-sm btn-primary open-modal my-2"
                data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Add living room') !!}"
                data-model_name="Livings" data-modal="_living" data-model_id="" {{ !$property ? 'disabled' : '' }}
                >{!! __('messages.Add living room') !!}</button>
        @if ($property && $property->livingrooms->count() > 0)
            <div class="list-group">
                @foreach ($property->livingrooms as $living)
                    <div class="list-group-item list-group-item-action">
                        <div class="float-left">
                            <p>{{ $living->name }}</p>
                            <i>{{ $living->infoText() }}</i>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-sm btn-success open-modal"
                                data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Edit living room') !!}"
                                data-model_name="Livings" data-modal="_living" data-model_id="{{ $living->id }}"
                                ><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-danger open-modal"
                                data-title="{!! __('messages.Remove living room') !!}" data-property="{{ $property ? $property->id :'' }}" data-modal="_delete"
                                data-model_name="Livings" data-model_id="{{ $living->id }}"
                                ><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
