@extends('adminlte::page')

@section('title', ' | ' . __('messages.Properties'))

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Properties') !!}</h1>
@stop

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <button type="button" id="search_button" class="btn btn-primary">{!! __('messages.Search') !!}</button>
        <a href="{{ url()->current() }}" class="btn btn-warning">{!! __('messages.Reset') !!}</a>
    </div>
    <div class="col-md-6 text-right">
        <a class="btn btn-primary" href="{{ route('admin/property-settings') }}" role="button"><i class="fas fa-plus mr-2"></i>{!! __('messages.Add property') !!}</a>
        @if($user->is_agency())
            <a class="btn btn-primary" href="{{ route('admin/properties-list') }}" role="button"><i class="fas fa-plus mr-2"></i>{!! __('messages.Add property from list') !!}</a>
        @endif
    </div>
</div>
<div class="table-grid prop-table">

    @if (!$user->is_agency())
        {!! grid_view([
                'dataProvider' => $dataProvider,
                'columnFields' => [
                    [
                        'attribute' => 'main_photo',
                        'label' => __('messages.Main Image'),
                        'value' => function ($row) {
                            return $row->main_photo ? $row->main_photo->photo : asset('img/placeholder.jpg');
                        },
                        'filter' => false,
                        'sort' => false,
                        'format' => [
                            'class' => Itstructure\GridView\Formatters\ImageFormatter::class,
                            'htmlAttributes' => [
                                'width' => '150'
                            ]
                        ]
                    ],
                    [
                        'attribute' => 'name',
                        'label' => __('messages.Property Name'),
                        'sort' => false,
                        'value' => function ($row) {
                            $v = null; $checked = 'checked';
                            if ($row->show_on_main != 'yes') {
                                $v = 'yes'; $checked = '';
                            }
                            $show ='<div class="custom-control custom-switch mt-3">
                                <input type="checkbox" class="custom-control-input switch-show" id="show_'.$row->id.'" data-id="'.$row->id.'" data-value="'.$v.'"
                                 '.$checked.'>
                                <label class="custom-control-label small" for="show_'.$row->id.'">'. __('messages.Show on main website') .'</label>
                            </div>';

                            return '<h6>' . $row->name .'</h6>' . $row->info() . $show;
                        },
                        'format' => [
                            'class' => Itstructure\GridView\Formatters\HtmlFormatter::class,
                        ]
                    ],
                    [
                        'attribute' => 'country',
                        'label' => __('Location'),
                        'sort' => false,
                        'value' => function ($row) {
                            return $row->country.", ".$row->city;
                        },
                    ],
                    [
                        'attribute' => 'owner',
                        'label' => __('messages.Owner'),
                        'filter' => [
                            'class' => Itstructure\GridView\Filters\DropdownFilter::class,
                            'data' => \App\Models\Properties::ownersList()
                        ],
                        'sort' => false,
                        'value' => function ($row) {
                            return
                                $row->ownerInfo ? '<a href="'.route('edit-user',['id'=>$row->owner]).'">' . $row->ownerInfo->fullName() . '</a>'
                                : '' ;
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
                                return route('admin/property-settings', ['id' => $data->id]);
                            },
                            'view' => function ($data) {
                                return $data->id;
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
    @else
        {!! grid_view([
            'dataProvider' => $dataProvider,
            'columnFields' => [
                [
                    'attribute' => 'main_photo',
                    'label' => __('messages.Main Image'),
                    'value' => function ($row) {
                        return $row->main_photo ? $row->main_photo->photo : asset('img/placeholder.jpg');
                    },
                    'filter' => false,
                    'sort' => false,
                    'format' => [
                        'class' => Itstructure\GridView\Formatters\ImageFormatter::class,
                        'htmlAttributes' => [
                            'width' => '150'
                        ]
                    ]
                ],
                [
                    'attribute' => 'name',
                    'label' => __('messages.Property Name'),
                    'sort' => false,
                    'value' => function ($row) use ($user) {
                        $v = null; $checked = 'checked';
                        if ($row->show_on_main != 'yes') {
                            $v = 'yes'; $checked = '';
                        }
                        $show ='<div class="custom-control custom-switch mt-3">
                            <input type="checkbox" class="custom-control-input switch-show" id="show_'.$row->id.'" data-id="'.$row->id.'" data-value="'.$v.'"
                             '.$checked.'>
                            <label class="custom-control-label small" for="show_'.$row->id.'">'. __('messages.Show on main website') .'</label>
                        </div>';
                        if ($row->owner == $user->id) {
                            return '<h6>' . $row->name .'</h6>' . $row->info() . $show;
                        }
                        return '<h6>' . $row->name .'</h6>' . $row->info();
                    },
                    'format' => [
                        'class' => Itstructure\GridView\Formatters\HtmlFormatter::class,
                    ]
                ],
                [
                    'attribute' => 'country',
                    'label' => __('Location'),
                    'sort' => false,
                    'value' => function ($row) {
                        return $row->country.", ".$row->city;
                    },
                ],
                [
                    'label' => '',
                    'class' => Itstructure\GridView\Columns\ActionColumn::class,
                    'actionTypes' => [
                        'edit' => function ($data) {
                            return route('admin/property-settings', ['id' => $data->id]);
                        },
                        'view' => function ($data) {
                            return $data->id;
                        },
                        'delete' => function ($data) use ($user) {
                            if ($data->owner == $user->id) {
                                $deleteButton = [];
                                $deleteButton['class'] = Itstructure\GridView\Actions\Delete::class;
                                $deleteButton['url'] = $data->id;
                                return $deleteButton;
                            }
                            return false;
                        },
                    ]
                ]
            ]
        ])
    !!}
    @endif

</div>
<div class="modal fade" id="removeEl" tabindex="-1" aria-labelledby="removeElLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="removeElLabel">{!! __('messages.Remove property') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete-property') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <h5>{!! __('messages.Are you sure you want to delete this property?') !!}</h5>
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
<form action="{{ route('show-property') }}" method="post" class="d-none" id="switch_form">
    @csrf
    <input type="hidden" name="id" value="" id="switch_id">
    <input type="hidden" name="show_on_main" value="" id="switch_val">
</form>
@stop
