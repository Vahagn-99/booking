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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="penalty">{!! __('messages.Penalty percentage') !!} (%)</label>
                                <input type="number" name="penalty" value="{{ old('penalty') ? old('penalty') : ($model ? $model->penalty : '') }}" class="form-control @error('penalty') is-invalid @enderror" id="penalty">
                                @error('penalty')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="eligible">{!! __('messages.Eligible days') !!}</label>
                                <input type="number" name="eligible" value="{{ old('eligible') ? old('eligible') : ($model ? $model->eligible : '') }}" class="form-control @error('eligible') is-invalid @enderror" id="eligible">
                                @error('eligible')
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
$(function () {
    if ($('.modal-ajax').find('#penalty').val() == '' || $('.modal-ajax').find('#eligible').val() == '') {
        $('.modal-ajax').find('.btn-success').prop('disabled',true);
    }
    $('.modal-ajax').find('#penalty').on('keyup keydown keypress', function(e){
        var button = $(this).closest('.modal-ajax').find('.btn-success');
        if ($(this).val() != '' && $('.modal-ajax').find('#eligible').val() != '') {
            button.prop('disabled',false);
        } else {
            button.prop('disabled',true);
        }
    })
    $('.modal-ajax').find('#eligible').on('keyup keydown keypress', function(e){
        var button = $(this).closest('.modal-ajax').find('.btn-success');
        if ($(this).val() != '' && $('.modal-ajax').find('#penalty').val() != '') {
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
