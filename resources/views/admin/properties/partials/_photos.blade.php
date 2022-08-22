<h5>{!! __('messages.Main photo') !!}</h5>
<button type="button" class="btn btn-sm btn-primary open-modal my-2"
        data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Upload main photo') !!}"
        data-model_name="PropertyPhoto" data-modal="_mainphoto" data-model_id="" {{ !$property ? 'disabled' : '' }}
        >{!! __('messages.Upload main photo') !!}</button>
<div class="row mb-3" id="main_photo">
    @if($property && $property->main_photo)
        <div class="col-md-3 mb-2">
            <div class="photo-block list-group-item" data-id="{{ $property->main_photo->id }}">
                @if(strpos($property->main_photo->photo,"/upload") === false)
                    @if(strpos($property->main_photo->photo, "youtube.com") > 0 || strpos($property->main_photo->photo, "youtu.be") > 0)
                        <div class="video-wrap">
                            <iframe src="{{ strpos($property->main_photo->photo, 'youtu.be') > 0 ? str_replace('youtu.be','www.youtube.com/embed',$property->main_photo->photo) : 'https://www.youtube.com/embed/' . substr($property->main_photo->photo, intval(strpos($property->main_photo->photo, 'v=')) + 2, 11) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                    @else
                        <video class="w-100">
                            <source src="{{ asset($property->main_photo->photo) }}" type="video/mp4">
                        </video>
                    @endif
                @else
                    <img src="{{ asset($property->main_photo->photo) }}" alt="">
                @endif
                <button type="button" class="btn btn-sm btn-danger open-modal"
                    data-title="{!! __('messages.Remove main photo') !!}" data-modal="_delete" data-property="{{ $property->id }}"
                    data-model_name="PropertyPhoto" data-model_id="{{ $property->main_photo->id }}"
                    ><i class="fas fa-trash-alt"></i></button>
            </div>
        </div>
    @endif
</div>
<hr>
<h5>{!! __('messages.Additional photos') !!}</h5>
<button type="button" class="btn btn-sm btn-primary open-modal my-2"
        data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Upload photo') !!}"
        data-model_name="PropertyPhoto" data-modal="_photo" data-model_id="" {{ !$property ? 'disabled' : '' }}
        >{!! __('messages.Upload photo') !!}</button>
<div class="row mb-3" id="additional_photos" data-action="{{ route('change-order') }}">
    @if($property && $property->photos->count() > 0)
        @foreach ($property->photos as $item)
            <div class="col-md-3 mb-2">
                <div class="photo-block list-group-item" data-id="{{ $item->id }}">
                    @if(strpos($item->photo,"/upload") === false)
                        @if(strpos($item->photo, "youtube.com") > 0 || strpos($item->photo, "youtu.be") > 0)
                            <div class="video-wrap">
                                <iframe src="{{ strpos($item->photo, 'youtu.be') > 0 ? str_replace('youtu.be','www.youtube.com/embed',$item->photo) : 'https://www.youtube.com/embed/' . substr($item->photo, intval(strpos($item->photo, 'v=')) + 2, 11) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                            </div>
                        @else
                            <video class="w-100">
                                <source src="{{ asset($item->photo) }}" type="video/mp4">
                            </video>
                        @endif
                    @else
                        <img src="{{ asset($item->photo) }}" alt="">
                    @endif
                    <button type="button" class="btn btn-sm btn-danger open-modal"
                        data-title="{!! __('messages.Remove main photo') !!}" data-modal="_delete" data-property="{{ $property->id }}"
                        data-model_name="PropertyPhoto" data-model_id="{{ $item->id }}"
                        ><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
        @endforeach
    @endif
</div>
