@extends('adminlte::page')

@section('title', ' | ' . __('messages.Pages') )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Pages') !!}</h1>
@stop

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <button type="button" id="search_button" class="btn btn-primary">{!! __('messages.Search') !!}</button>
        <a href="{{ url()->current() }}" class="btn btn-warning">{!! __('messages.Reset') !!}</a>
    </div>
    <div class="col-md-6 text-right">
        <a class="btn btn-primary" href="{{ route('admin/page-create') }}" role="button"><i class="fas fa-plus mr-2"></i>{!! __('messages.Add Page') !!}</a>
    </div>
</div>
<div class="table-grid">
    {!! grid_view([
            'dataProvider' => $dataProvider,
            'columnFields' => [
                [
                    'attribute' => 'title',
                    'label' => __('messages.Title'),
                ],
                [
                    'attribute' => 'slug',
                    'label' => __('messages.Slug'),
                    'value' => function ($row) use ($subdomain) {
                        if ($subdomain) {
                            return '<a href="'.route('agency-page-view',['slug'=>$row->slug,'subdomain' => $subdomain]).'" target="_blank">' .'/'.$row->slug. '</a>';
                        }
                        return '<a href="'.route('page-view',['slug'=>$row->slug]).'" target="_blank">' .'/'.$row->slug. '</a>';
                    },
                    'format' => [
                        'class' => Itstructure\GridView\Formatters\HtmlFormatter::class,
                    ]
                ],
                [
                    'label' => '',
                    'class' => Itstructure\GridView\Columns\ActionColumn::class,
                    'actionTypes' => [
                        'edit' => function ($data) {
                            return route('admin/page-settings', ['id' => $data->id]);
                        },
                        [
                            'class' => Itstructure\GridView\Actions\Delete::class,
                            'url' => function ($data) {
                                return $data->id;
                            }
                        ]
                    ]
                ]
            ]
        ])
    !!}
</div>
<div class="modal fade" id="removeEl" tabindex="-1" aria-labelledby="removeElLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="removeElLabel">{!! __('messages.Remove page') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete-page') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <h5>{!! __('messages.Are you sure you want to delete this page?') !!}</h5>
                    <input type="hidden" name="id" value="" id="data_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-danger">{!! __('messages.Remove') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
