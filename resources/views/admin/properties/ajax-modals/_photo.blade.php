<div class="modal" tabindex="-1" aria-hidden="true" id="photouploadModal">
    <div class="modal-dialog">
        <div class="modal-content modal-ajax">
            <div class="modal-header">
                <h4 class="modal-title">{!! $title !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('save-item') }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="{{ $hash }}">
                    <input type="hidden" name="model_name" value="{{ $model_name }}">
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <input type="hidden" name="id" value="{{ $model_id }}">
                    <input type="hidden" name="is_main" value="0">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="photo">{!! __('messages.Add_photo') !!}</label>
                                <input type="file" name="photo" class="form-control" id="photo">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="photo">{!! __('messages.Additional video') !!}</label>
                                <input type="text" name="photo" class="form-control" id="video">
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
