<form action="{{ route('save-cancellation-policy') }}" method="post">
    @csrf
    <input type="hidden" name="hash" value="">
    <div class="form-group">
        <label for="title">{!! __('messages.Title') !!}</label>
        <input class="form-control" type="text" name="title" value="{!! old('$content->title') ? old('title') : ($content ? $content->title : '') !!}">
    </div>
    <div class="form-group">
        <label for="content">{!! __('messages.Content') !!}</label>
        <textarea name="content" class="form-control editor" rows="10">{!! old('content') ? old('content') : ($content ? $content->content : '') !!}</textarea>
    </div>
    <div class="text-right">
        <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
    </div>
</form>
