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
                        <label for="model_name">{!! __('messages.Name') !!}*</label>
                        <input type="text" name="item_name" value="{{ old('item_name') ? old('item_name') : ($model ? $model->name : '') }}" class="form-control @error('name') is-invalid @enderror" id="model_name">
                        @error('item_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kingsize">{!! __('messages.Kingsize Beds') !!}</label>
                                <input type="number" name="kingsize" value="{{ old('kingsize') ? old('kingsize') : ($model ? $model->kingsize : '') }}" class="form-control @error('kingsize') is-invalid @enderror" id="kingsize">
                                @error('kingsize')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="queensize">{!! __('messages.Queensize Beds') !!}</label>
                                <input type="number" name="queensize" value="{{ old('queensize') ? old('queensize') : ($model ? $model->queensize : '') }}" class="form-control @error('queensize') is-invalid @enderror" id="queensize">
                                @error('queensize')
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
                                <label for="double">{!! __('messages.Double Beds') !!}</label>
                                <input type="number" name="double" value="{{ old('double') ? old('double') : ($model ? $model->double : '') }}" class="form-control @error('double') is-invalid @enderror" id="double">
                                @error('double')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="single">{!! __('messages.Single Beds') !!}</label>
                                <input type="number" name="single" value="{{ old('single') ? old('single') : ($model ? $model->single : '') }}" class="form-control @error('single') is-invalid @enderror" id="single">
                                @error('single')
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
                                <label for="bunk">{!! __('messages.Bunk Beds') !!}</label>
                                <input type="number" name="bunk" value="{{ old('bunk') ? old('bunk') : ($model ? $model->bunk : '') }}" class="form-control @error('bunk') is-invalid @enderror" id="bunk">
                                @error('bunk')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sofa">{!! __('messages.Sofa') !!}</label>
                                <input type="number" name="sofa" value="{{ old('sofa') ? old('sofa') : ($model ? $model->sofa : '') }}" class="form-control @error('sofa') is-invalid @enderror" id="sofa">
                                @error('sofa')
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
