<form action="{{ route('save-customize') }}" method="post">
    @csrf
    <input type="hidden" name="hash" value="">
    <input type="hidden" name="name" value="website-seo">
    @if ($websiteMeta)
        <input type="hidden" name="id" value="{{ $websiteMeta->id }}">
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="website_title">{!! __('messages.Website name') !!}</label>
                <input type="text" name="website_title" value="{!! $websiteMeta ? $websiteMeta->website_title : '' !!}" class="form-control" id="website_title">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="title">{!! __('messages.Meta tag title') !!}</label>
                <input type="text" name="title" value="{!! $websiteMeta ? $websiteMeta->title : '' !!}" class="form-control" id="title">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">{!! __('messages.Meta tag description') !!}</label>
                <textarea name="description" class="form-control" rows="10" id="description">{!! $websiteMeta ? $websiteMeta->description : '' !!}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="keywords">{!! __('messages.Meta tag keywords') !!}</label>
                <input type="text" name="keywords" value="{{ $websiteMeta ? $websiteMeta->keywords : '' }}" class="form-control" id="keywords">
                <small class="form-text text-muted">{!! __('messages.Please, divide keywords with comma.') !!}</small>
            </div>
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
    </div>
</form>
