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
                        <label for="model_name">{!! __('messages.Bedroom type') !!}</label>
                        <select name="bed_id" class="form-control @error('bed_id') is-invalid @enderror" id="model_name">
                            <option value="">{!! __('messages.Choose') !!}...</option>
                            @foreach (\App\Models\Properties::RoomTypeList() as $key => $value)
                                <option value="{{ $key }}" {{ old('bed_id') && old('bed_id') == $key ? 'selected' : ($model && $model->bed_id == $key ? 'selected' : '') }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('bed_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
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
$(function () {
    if ($('.modal-ajax').find('#model_name').val() == '') {
        $('.modal-ajax').find('.btn-success').prop('disabled',true);
    }
    $('.modal-ajax').find('#model_name').on('change', function(e){
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
});
</script>
