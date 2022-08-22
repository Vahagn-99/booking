@if ($url)
    <div class="col-lg-{!! $bootstrapColWidth-1 !!}">
        <a class="btn btn-danger btn-sm admin-delete-el align-self-center" href="#" title="Delete"
            data-id="{!! is_array($url) ? $url['url'] : $url !!}" @if(!empty($htmlAttributes)) {!! $htmlAttributes !!} @endif >
            <i class="fas fa-trash-alt"></i>
        </a>
    </div>
@endif
