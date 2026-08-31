@extends('layouts.frontend.skeleton')
@section('title', 'Job Posts')
@php
    $total_courses = $jobPosts->total();
@endphp

@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jstree.min.css') }}">
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="{{ asset('assets/js/jstree.min.js') }}"></script>

    <script>
        $(document).ready(function (){
            $('#html').jstree({
                'core': {
                    'themes': {
                        'responsive': false
                    },
                    'check_callback': true,
                },
                'types': {
                    'default': {
                        'icon': 'd-none'
                    },
                    'file': {
                        'icon': 'd-none'
                    }
                },
                'search': {
                    'case_insensitive': true,
                    'show_only_matches': true,
                    'show_only_matches_children': true
                },
                'plugins': ['types', 'checkbox', 'search']
            });

            $('#listingView .nav-item .nav-link').click(function (){
                var viewType = this.id == 'list-view-tab' ? 'list' : 'grid';
                if('{{$viewType}}' == viewType) return false;
                $(this).html('<i class="fa fa-spin fa-circle-notch"></i> <span> Loading...</span>');
                $.post('{{route('set.cookie')}}', { viewType, _token: '{{ csrf_token() }}' }, function(){
                    location.reload();
                });
            });

            $('.wishlist-save').click(function() {
                try {
                    @if (auth()->check())
                        @if (!lms_is_student())
                            notify({title: 'Only student can do this action'});
                            return false;
                        @endif
                        var ths = $(this);
                        ths.find('i').toggleClass('fa-spin fa-circle-notch fa-bookmark');
                        $.post(`{{route('ajax.wishlist_save')}}/${this.dataset.type}/${this.dataset.id}`, { _token: $('meta[name="csrf-token"]').attr('content') }, function (result) {
                            if(result.status == 'error'){
                                notify({title: result.message});
                                return false;
                            }
                            if(ths.data('refresh')){
                                window.location.reload();
                            }
                            ths.find('i').toggleClass('fa-spin fa-circle-notch fa-bookmark');
                            result.added ? ths.addClass('added') : ths.removeClass('added');
                            notify({
                                title: result.message,
                                type: 'success'
                            });
                        }, 'JSON');
                    @else
                        window.location.href = '{{route('login')}}';
                    @endif
                }
                catch(err) {

                }
            });
        });
    </script>
@endsection

@section('content')
<style>
    .rts-single-course.course-list img {
        height: 240px;
    }
    .rts-single-course .tags-area-wrapper{
        margin-top: 0px;
    }
    .single-filter-left-wrapper .filter-body{
        margin-bottom: 20px;
        padding-bottom: 20px;
    }
    .single-course-style-three .thumbnail{
        box-shadow: 1px 1px 10px #c7c7c7;
    }
    /* .bg_image{
        background-image: url({{asset('assets/images/breadcrumb/job-banner.png')}});
    } */
</style>
<!-- bread crumb area -->
<div class="rts-bread-crumbarea-1 rts-section-gap bg_image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main-wrapper">
                    <h1 class="title">Job Posts</h1>
                    <!-- breadcrumb pagination area -->
                    <div class="pagination-wrapper">
                        <a href="{{url('')}}">Home</a>
                        <i class="fa-regular fa-chevron-right"></i>
                        <a class="active">All Job Posts</a>
                    </div>
                    <!-- breadcrumb pagination area end -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- bread crumb area end -->

<!-- course area start -->
<div class="rts-course-default-area rts-section-gap">
    <div class="container">
        <div id="html" class="demo d-none"></div>
        
        <div class="row g-5">
            <div class="col-lg-3">
                <form action="">
                    <input type="hidden" name="search" value="true">
                    <input type="hidden" names="page" value="{{request('page')}}">
                    <!-- course-filter-area start -->
                    <div class="rts-course-filter-area">
                        <!-- single filter wized -->
                        <div class="single-filter-left-wrapper">
                            <h6 class="title">Search</h6>
                            <div class="search-filter filter-body">
                                <div class="input-wrapper">
                                    <input type="text" name="query" value="{{$filter['query']}}" placeholder="Search Jobs...">
                                    <i class="fa-light fa-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                        <!-- single filter wized end -->
                        <!-- single filter wized -->
                        <div class="single-filter-left-wrapper">
                            <h6 class="title">Category</h6>
                            <div class="checkbox-filter filter-body">
                                <div class="checkbox-wrapper">
                                    @foreach ($categories as $key => $item)
                                        <!-- single check box -->
                                        <div class="single-checkbox-filter">
                                            <div class="check-box">
                                                <input type="checkbox" name="category[]" {{in_array($item->id, $filter['category']) ? 'checked' : ''}} value="{{$item->id}}" id="category-{{$item->id}}">
                                                <label for="category-{{$item->id}}">{{$item->title}}</label><br>
                                            </div>
                                            <span class="number">({{$item->job_posts_count }})</span>
                                        </div>
                                        @if (!empty($item->children))
                                            <div style="padding-left: 1rem;">
                                                @foreach ($item->children as $child)
                                                    <div class="single-checkbox-filter">
                                                        <div class="check-box">
                                                            <input type="checkbox" {{in_array($child->id, $filter['category']) ? 'checked' : ''}} name="category[]" value="{{$child->id}}" id="category-{{$child->id}}">
                                                            <label for="category-{{$child->id}}">{{$child->title}}</label><br>
                                                        </div>
                                                        <span class="number">({{$child->total_courses}})</span>
                                                    </div>
                                                    <div style="padding-left: 1rem;">
                                                        @if (!empty($child->children))
                                                            @foreach ($child->children as $subChild)
                                                                <div class="single-checkbox-filter">
                                                                    <div class="check-box">
                                                                        <input type="checkbox" {{in_array($subChild->id, $filter['category']) ? 'checked' : ''}} name="category[]" value="{{$subChild->id}}" id="category-{{$subChild->id}}">
                                                                        <label for="category-{{$subChild->id}}">{{$subChild->title}}</label><br>
                                                                    </div>
                                                                    <span class="number">({{$subChild->total_courses}})</span>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        <!-- single check box end -->
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- single filter wized end -->
                        <!-- single filter wized -->
                        <div class="single-filter-left-wrapper">
                            <h6 class="title">Pricing</h6>
                            <div class="checkbox-filter filter-body">
                                <div class="checkbox-wrapper">
                                    @foreach (lms_job_post_pricing() as $key => $is_paid)
                                        <!-- single check box -->
                                        <div class="single-checkbox-filter">
                                            <div class="check-box">
                                                <input type="checkbox" {{in_array($key, $filter['is_paid']) ? 'checked' : ''}} name="is_paid[]" value="{{$key}}" id="is_paid-{{$is_paid}}">
                                                <label for="is_paid-{{$is_paid}}">{{$is_paid}}</label><br>
                                            </div>
                                            <span class="number">({{$counts["is_paid_" . $key]}})</span>
                                        </div>
                                        <!-- single check box end -->
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- single filter wized end -->

                        <button type="submit" class="rts-btn btn-primary m-auto mt-5"><i class="fa-regular fa-filter"></i> Apply Filters</button>
                        @if (request('search', false))
                            <a href="{{route('job-posts')}}" class="rts-btn btn-border"><i class="fa-regular fa-x"></i> Clear All Filters</a>
                        @endif                        
                    </div>
                    <!-- course-filter-area end -->
                </form>
            </div>
            <div class="col-lg-9">
                <!-- filter top-area  -->
                <div class="filter-small-top-full">
                    <div class="left-filter">
                        <span>{{$total_courses}} job {{lms_plural('post', $total_courses)}} found</span>
                    </div>
                    <div class="right-filter">
                        
                        <ul class="nav nav-tabs" id="listingView" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{$viewType == 'grid' ? 'active' : ''}}" id="grid-view-tab" data-bs-toggles="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="false">
                                    <i class="fa-light fa-grid-2"></i>
                                    <span>Grid</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{$viewType == 'list' ? 'active' : ''}}" id="list-view-tab" data-bs-toggles="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                    <i class="fa-light fa-list"></i>
                                    <span> List</span>
                                </button>
                            </li>
                        </ul>

                    </div>
                </div>
                <!-- filter top-area end -->
                <div class="tab-content" id="listingViewContent">
                    @if ($viewType == 'grid')
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="grid-view-tab">
                            <div class="row mt-0 g-5">
                                @foreach ($jobPosts as $item)
                                    @php
                                        $lesson_count = 0;
                                        $url = lms_job_post_slug($item);
                                    @endphp
                                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                                        <!-- single course style two -->
                                        <div class="single-course-style-three wishlist-icon">                                                
                                            <a href="{{$url}}" class="thumbnail">
                                                <img src="{{lms_image($item->thumbnail)}}" alt="{{ $item->title }}">
                                                <div class="tag-thumb">
                                                    <span>{{ $item->category->title }}</span>
                                                </div>
                                            </a>
                                            <div class="body-area">
                                                <div class="course-top">
                                                    @if ($item->enroll())
                                                        <div class="tags border border-light rounded"><i class="fa fa-check-circle text-success"></i> Enrolled</div>
                                                    @endif
                                                    @if ($item->discount_flag)
                                                        <div class="price">{!!lms_show_price($item, 'discounted_price')!!}</div>
                                                        <s class="text-muted">
                                                            {!!lms_show_price($item)!!}
                                                        </s>
                                                    @else
                                                        <div class="price">
                                                            {!!lms_show_price($item)!!}
                                                        </div>
                                                    @endif
                                                </div>
                                                <a href="{{$url}}">
                                                    <h5 class="title mb-0">{{ $item->title }}</h5>
                                                </a>
                                                <div class="teacher small">By <span>{{ $item->user->first_name }}</span></div>
                                                <div class="d-flex justify-content-between mt-3">
                                                    <ul class="stars ps-0 mt-0">
                                                        <span class="text-dark fw-bold">{{lms_decimal_points($item->rating_average())}}</span>
                                                        <div class="stars-area">
                                                            <div class="stars-background">
                                                                {!!str_repeat('<i class="fa-regular fa-star"></i>', 5)!!}
                                                            </div>
                                                            <div class="stars-overlay" style="width: {{lms_rating_to_percentage($item->rating_average())}}%;">
                                                                {!!str_repeat('<i class="fa fa-star"></i>', 5)!!}
                                                            </div>
                                                        </div>
                                                    </ul>
                                                    <div class="tags border border-light rounded"><i class="fa-light fa-users"></i> {{$item->enroll_count()}} Enrolled</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- single course style two end -->
                                    </div>
                                @endforeach
                                
                            </div>
                        </div>
                    @else
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="list-view-tab">
                            <div class="row mt-0 g-5">
                                <div class="col-lg-12">
                                    @foreach ($jobPosts as $item)
                                        <!-- rts single course -->
                                        <div class="rts-single-course course-list">
                                            <a href="{{lms_course_slug($item)}}" class="thumbnail shadow-lg">
                                                <img src="{{lms_image($item->thumbnail)}}" alt="{{ $item->title }}">
                                            </a>
                                            <div class="information-inner">
                                                <a href="{{lms_course_slug($item)}}">
                                                    <h5 class="title">{{ $item->title }}</h5>
                                                </a>
                                                <p class="teacher mt-0 mb-3">By {{ $item->user->first_name }}</p>
                                                <div class="disc">{!! \Illuminate\Support\Str::limit(strip_tags($item->description), 150) !!}</div>
                                                <div class="rating-and-price">
                                                    <div class="rating-area">
                                                        <span class="text-dark fw-bold">{{lms_decimal_points($item->rating_average())}}</span>
                                                        <div class="stars">
                                                            <div class="stars-area">
                                                                <div class="stars-background">
                                                                    {!!str_repeat('<i class="fa-regular fa-star"></i>', 5)!!}
                                                                </div>
                                                                <div class="stars-overlay" style="width: {{lms_rating_to_percentage($item->rating_average())}}%;">
                                                                    {!!str_repeat('<i class="fa fa-star"></i>', 5)!!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="price-area">
                                                        @if ($item->discount_flag)
                                                            <div class="not price">
                                                                {!!lms_show_price($item)!!}
                                                            </div>
                                                            <div class="price">
                                                                {!!lms_show_price($item, 'discounted_price')!!}
                                                            </div>
                                                        @else
                                                            <div class="price">
                                                                {!!lms_show_price($item)!!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="tags-area-wrapper mt-2">
                                                    <div class="single-tag">
                                                        <span>{{ $item->category->title }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- rts single course end -->
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt--0">
                        <div class="col-lg-12">
                            <div class="custom-pagination">
                                {{$jobPosts->links()}}
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- course area end -->

@endsection