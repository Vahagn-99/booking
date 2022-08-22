<div class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-ajax">
            <div class="modal-header">
                <h4 class="modal-title">{!! $title !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete-item') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="{{ $hash }}">
                    <h5>{!! __('messages.Are you sure you want to delete this element?') !!}</h5>
                    <input type="hidden" name="model_name" value="{{ $model_name }}">
                    <input type="hidden" name="id" value="{{ $model_id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-danger">{!! __('messages.Remove') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
