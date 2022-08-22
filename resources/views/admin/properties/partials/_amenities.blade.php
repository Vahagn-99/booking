@foreach ($categories as $c)
    <h4>{{ $c }}</h4>
    <div class="mb-5 c-4">
        @foreach (\App\Models\Amenity::getByCat($c) as $a)
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="amenity_{{ $a->id }}" name="amenity[]" value="{{ $a->id }}"
                    {{ old('amenity') && in_array($a->id,old('amenity')) ? 'checked' : ($property && in_array($a->id,$property->amenities->pluck('amenity_id')->toArray()) ? 'checked' : '') }}>
                <label class="custom-control-label" for="amenity_{{ $a->id }}">{!! __('messages.'.$a->title) !!}</label>
            </div>
        @endforeach
    </div>
@endforeach
<div class="text-right">
    <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
</div>
