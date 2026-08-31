@extends('layouts.admin.skeleton')
@section('title', __('Edit Setting'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-pencil-simple text-muted',
        'title' => __('Edit Setting'),
        'breadcrumbs' => [
            route('admin.settings') => __('Settings'),
            '#' => __('Edit'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.settings',
            ]),
        ]
    ])
@endsection

@section('content')
<form id="saveForm" class="autoSubmit" action="{{ route('admin.settings.update', ['id' => $setting->id]) }}" enctype="multipart/form-data" method="POST" data-parsley-validate>
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Key', false)}}
                        <div>{{$setting->key}}</div>
                    </div>
                    <div class="form-group">
                        {{lms_form_label('Description', false)}}
                        <div>{{$setting->description}}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Value', $setting->is_required)}}
                        @if ($setting->type == 'file:image')
                            <input type="file" class="form-control-file" id="value" name="value" accept=".jpg,.jpeg,.png,.fig">
                            @if ($setting->value)
								<img src="{{runnwic_storage($setting->value)}}" class="img-thumbnail mt-3 bs-tt" title="Click to view" onclick="window.open(this.src);" style="height: 120px;" alt="">
                            @endif
                        @elseif ($setting->type == 'text')
                            <input type="text" class="form-control" name="value" value="{{ old('value', $setting->value)}}" placeholder="{{__('Enter Value')}}" {!!$setting->required ? 'required' : ''!!}>
                        @elseif ($setting->type == 'dropdown')
                            <select name="value" class="form-control" {{$setting->required ? 'required' : ''}}>
                                <option value="">Choose One</option>
                                @foreach ($setting->picklist as $val)
                                    <option {{ old('value', $setting->value) == $val ? 'selected' : ''}} value="{{$val}}">{{$val}}</option>
                                @endforeach
                            </select>
                        @endif
                        @error('value')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0 flex-1"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection