@extends('layouts.admin.skeleton')
@section('title', __('Ratings'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-star text-muted',
        'title' => $exam->title . ' - ' . __('Ratings'),
        'breadcrumbs' => [
            route('admin.exams.index') => __(key: 'Exams'),
            '#' => __('Ratings')
        ],
        'links' => [
            lms_can_access('admin.exams.index') ? generate_link_element([
                'action' => 'back',
                'route' => 'admin.exams.index'
            ]) : false,
        ]
    ])
@endsection

@section('content')

<div class="card">
    <div class="card-block">
        <div class="dt-responsive table-responsive dt-fixed-controls">
            <table id="listing-data-table" class="g-datatable table compact table-striped table-hover table-bordered table-header-dark nowrap">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Rating</th>
                        <th>Message</th>
                        <th>Rated on</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ratings as $item) 
                        <tr>
                            <td>{{$item->user->full_name}}</td>
                            <td>
                                <div class="stars">
                                    <span>{{$item->rating}} - </span>
                                    <i class="text-yellow fa fa-star"></i>
                                    <i class="text-yellow fa fa-star{{$item->rating > 1 ? '' : '-o'}}"></i>
                                    <i class="text-yellow fa fa-star{{$item->rating > 2 ? '' : '-o'}}"></i>
                                    <i class="text-yellow fa fa-star{{$item->rating > 3 ? '' : '-o'}}"></i>
                                    <i class="text-yellow fa fa-star{{$item->rating > 4 ? '' : '-o'}}"></i>
                                </div>
                            </td>                            
                            <td>{{str($item->message)->words(10)}}</td>
                            <td>{{lms_format_date($item->created_at)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{$ratings->links()}}
    </div>
</div>
<script>
    $('.menu_exams').addClass('active');
</script>
@endsection