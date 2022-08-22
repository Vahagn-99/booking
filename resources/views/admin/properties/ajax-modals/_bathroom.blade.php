<div class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-ajax">
            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('save-item') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="{{ $hash }}">
                    <input type="hidden" name="model_name" value="{{ $model_name }}">
                    <input type="hidden" name="property" value="{{ $property->id }}">
                    <input type="hidden" name="id" value="{{ $model_id }}">

                    <div class="form-group">
                        <label for="model_name">{!! __('messages.Name') !!}</label>
                        <input type="text" name="item_name" value="{{ old('item_name') ? old('item_name') : ($model ? $model->name : '') }}" class="form-control @error('name') is-invalid @enderror" id="model_name">
                        @error('item_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bath">{!! __('messages.Bath') !!}</label>
                                <input type="number" name="bath" value="{{ old('bath') ? old('bath') : ($model ? $model->bath : '') }}" class="form-control @error('bath') is-invalid @enderror" id="bath">
                                @error('bath')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="shower">{!! __('messages.Shower') !!}</label>
                                <input type="number" name="shower" value="{{ old('shower') ? old('shower') : ($model ? $model->shower : '') }}" class="form-control @error('shower') is-invalid @enderror" id="shower">
                                @error('shower')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="wc">{!! __('messages.WC') !!}</label>
                                <input type="number" name="wc" value="{{ old('wc') ? old('wc') : ($model ? $model->wc : '') }}" class="form-control @error('wc') is-invalid @enderror" id="wc">
                                @error('wc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
if ($('.modal-ajax').find('#model_name').val() == '') {
    $('.modal-ajax').find('.btn-success').prop('disabled',true);
}
$('.modal-ajax').find('#model_name').on('keyup keydown keypress', function(e){
    var button = $(this).closest('.modal-ajax').find('.btn-success');
    if ($(this).val() != '') {
        button.prop('disabled',false);
    } else {
        button.prop('disabled',true);
    }
})
$('.modal-ajax').find('form').on('keyup keydown keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});
</script>
