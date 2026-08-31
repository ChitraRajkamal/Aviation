@extends('layouts.frontend.skeleton')
@section('title', $jobPost->title)
@php
    $meta_data = $jobPost->meta_data ?? [];
    $sections = [];
    $total_lessons = 0;
    $total_quizs = 0;
    $requirements = false;
    $outcomes = false;
    $faqs = false;
@endphp

@section('meta-data')
    <meta name="description" content="{{ $meta_data['description'] ?? '' }}">
    <meta name="robots" content="{{ $meta_data['robot'] ?? 'index, follow' }}">

    <!-- Open Graph (OG) Tags for Social Media -->
    <meta property="og:title" content="{{ $meta_data['og_title'] ?? $meta_data['title'] ?? $jobPost->title }}">
    <meta property="og:description" content="{{ $meta_data['og_description'] ?? $meta_data['description'] ?? '' }}">
    <meta property="og:image" content="{{ lms_storage($meta_data['og_image'] ?? '') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter Card (Optional for Twitter Sharing) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta_data['og_title'] ?? $meta_data['title'] ?? $jobPost->title }}">
    <meta name="twitter:description" content="{{ $meta_data['og_description'] ?? $meta_data['description'] ?? '' }}">
    <meta name="twitter:image" content="{{ lms_storage($meta_data['og_image'] ?? '') }}">
@endsection

@section('content')
<style>
    .module-wrapper:empty{
        display: none;
    }
    .accordion-button{
        font-size: 21px;
    }
    .course-content-wrapper-main .accordion .accordion-item .accordion-body .play-vedio-wrapper .left span{
        text-decoration-line: none;
    }
</style>
<!-- course details breadcrumb -->
<div class="course-details-breadcrumb-1 bg_image rts-section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="single-course-left-align-wrapper">
                    <div class="meta-area">
                        <a href="{{url('')}}">Home</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{route('job-posts')}}">Videos</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a class="active" href="#">{{$jobPost->title}}</a>
                    </div>
                    <h1 class="title">
                        {{$jobPost->title}}
                    </h1>
                    <div class="rating-area">
                        <div class="stars-area">
                            <span>{{lms_decimal_points($averageRating)}}</span>
                            <div class="stars-area">
                                <div class="stars-background">
                                    {!!str_repeat('<i class="fa-regular fa-star"></i>', 5)!!}
                                </div>
                                <div class="stars-overlay" style="width: {{lms_rating_to_percentage($averageRating)}}%;">
                                    {!!str_repeat('<i class="fa fa-star"></i>', 5)!!}
                                </div>
                            </div>
                        </div>
                        <div class="students">
                            <i class="fa-thin fa-users"></i>
                            <span>{{$jobPost->enroll_count()}} Enrolled</span>
                        </div>
                        <div class="calender-area-stars">
                            <i class="fa-light fa-calendar-lines-pen"></i>
                            <span>Last updated {{lms_format_date($jobPost->updated_at)}}</span>
                        </div>
                    </div>
                    <div class="author-area">
                        <div class="author">
                            <img src="{{asset('admin-assets/assets/images/profile-40.png')}}" alt="Instructor">
                            <div class="name fw-medium"><span>By</span> {{$jobPost->user->first_name}}.</div>
                        </div>
                        <p> <span>Category: </span> {{$jobPost->category->title}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- course details breadcrumb end -->

<!-- course details area start -->
<div class="rts-course-area rts-section-gap">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8 order-cl-1 order-lg-1 order-md-2 order-sm-2 order-2">
                <div class="course-content-wrapper">
                    @if ($jobPostEnrolled)
                        <h5 class="title mb-2">Description</h5>
                        <div class="disc">
                            {!!$jobPost->description!!}
                        </div>
                        <a href="{{$jobPost->link}}" target="_blank" class="btn btn-xl btn-primary"><i class="fa-regular fa-check"></i> Apply for this job</a>
                    @else
                        <div class="border border-2 border-primary text-dark py-5 mb-5 rounded shadow text-center">
                            <i class="fa-regular fa-info-circle fa-3x mb-2 text-primary"></i><br>
                            Please enroll to view job details and have access to the application form.
                        </div>
                    @endif
                </div>
                <div class="tab-content mt--20" id="myTabContent">
                    <div>
                        @if (lms_user_id() && $jobPostEnrolled)
                            @if ($rated)
                                <div class="alert alert-info mb-4"><i class="fa fa-check-circle"></i> {{ __('You already rated this job post') }}</div>
                            @else
                                @error('type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <form id="ratingForm" method="POST" class="submitForm" action="{{route('rating-save', ['type' => 'job-post', 'type_id' => $jobPost->id])}}">
                                    @csrf
                                    <h4>Leave a Rating</h4>
                                    
                                    <!-- Star Rating Input -->
                                    <div class="mb-3 d-flex align-items-center gap-4">
                                        <label class="form-label">Your Rating:</label>
                                        <div id="star-rating">
                                            @foreach (array_reverse(lms_rating_numbers()) as $r)
                                                <input type="radio" id="star-rating-{{$r['rating']}}" name="rating" value="{{$r['rating']}}" required />
                                                <label for="star-rating-{{$r['rating']}}" class="bs-tt" title="{{$r['name']}}">★</label>
                                            @endforeach
                                        </div>
                                    </div>
                                    @error('rating')
                                        <div class="text-danger mb-3">{{ $message }}</div>
                                    @enderror
                                
                                    <!-- Optional Message -->
                                    <div class="mb-4 d-flex align-items-center justify-content-between">
                                        <label for="message" class="form-label">Leave a Comment:</label>
                                        <textarea id="message" name="message" class="form-control" rows="3" required placeholder="Share your thoughts..."></textarea>
                                    </div>
                                    @error('message')
                                        <div class="text-danger mb-3">{{ $message }}</div>
                                    @enderror
                                
                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary btn-lg mb-5 w-auto">Submit Rating</button>
                                </form>
                                
                                <!-- Star Rating Styling -->
                                <style>
                                    #star-rating {
                                        display: flex;
                                        flex-direction: row-reverse;
                                        justify-content: flex-start;
                                    }
                                    #star-rating input {
                                        opacity: 0;
                                        position: absolute;
                                        z-index: -1;
                                    }
                                    #star-rating label {
                                        font-size: 30px;
                                        color: #a2a2a2;
                                        cursor: pointer;
                                        padding-left: 10px;
                                    }
                                    #star-rating label::before {
                                        display: none;
                                    }
                                    #star-rating input:checked ~ label {
                                        color: #f39c12;
                                    }
                                </style>
                            @endif
                        @endif
                        
                        <div class="rating-main-wrapper">
                            <!-- single-top-rating -->
                            <div class="rating-top-main-wrapper">
                                <!-- rating area start -->
                                <div class="rating-area-main-wrapper">
                                    <h2 class="title">{{lms_decimal_points($averageRating)}}</h2>

                                    <div class="stars-area">
                                        <div class="stars-background">
                                            {!!str_repeat('<i class="fa-regular fa-star"></i>', 5)!!}
                                        </div>
                                        <div class="stars-overlay" style="width: {{lms_rating_to_percentage($averageRating)}}%;">
                                            {!!str_repeat('<i class="fa fa-star"></i>', 5)!!}
                                        </div>
                                    </div>
                                    <br>
                                    <span>Total {{$ratings->count()}} Ratings</span>
                                </div>
                                <!-- rating area end -->
                                <div class="progress-wrapper-main">
                                    @foreach ($ratingPercentages as $rating => $percentage)
                                        <div class="single-progress-area-h" data-sal-delay="150" data-sal="slide-up" data-sal-duration="800">
                                            <div class="progress-top">
                                                <i class="fa-regular fa-star"></i>
                                                <span class="parcent">
                                                    {{$rating}}
                                                </span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar wow fadeInLeft bg--primary" role="progressbar" style="width: {{$percentage}}%" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <div class="end">
                                                <span>{{$ratingCounts[$rating]}} Rating</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- single-top-rating end-->
                            
                            @foreach ($ratings as $item)
                                <div class="indevidual-rating-area mt--25">
                                    <!-- author-area -->
                                    <div class="author-area">
                                        <img src="{{$global_user_image}}" alt="instructor">
                                        <div class="information">
                                            <span>
                                                {{$item->user->full_name}} 
                                                @if (lms_user_id() == $item->user->id)
                                                    <small class="text-muted">[Your rating]</small>
                                                @endif
                                            </span>
                                            <div class="stars">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-{{$item->rating > 1 ? 'solid' : 'regular'}} fa-star"></i>
                                                <i class="fa-{{$item->rating > 2 ? 'solid' : 'regular'}} fa-star"></i>
                                                <i class="fa-{{$item->rating > 3 ? 'solid' : 'regular'}} fa-star"></i>
                                                <i class="fa-{{$item->rating > 4 ? 'solid' : 'regular'}} fa-star"></i>
                                                <span class="ml--10 mt-2 text-muted fw-normal">{{$item->created_at->diffForHumans()}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- author-area end -->
                                    <p class="disc">
                                        {{$item->message}}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 order-cl-2 order-lg-2 order-md-1 order-sm-1 order-1  rts-sticky-column-item">
                <!-- right- sticky bar area -->
                <div class="right-course-details">
                    <!-- single course-sidebar -->
                    <div class="course-side-bar">
                        @if ($jobPost->thumbnail)
                            <div class="thumbnail">
                                <img src="{{lms_storage($jobPost->thumbnail)}}" alt="">
                            </div>
                        @endif
                            
                        <div class="price-area {{$jobPost->thumbnail ? 'mt-5' : 'mt-0'}}">                            
                            @if ($jobPost->discount_flag)
                                <h3 class="title">{!!lms_show_price($jobPost, 'discounted_price')!!}</h3>
                                <h4 class="none text-decoration-line-through">{!!lms_show_price($jobPost)!!}</h4>
                                <span class="discount">-{{(100 - round(($jobPost->discounted_price/$jobPost->price) * 100, 2))}}%</span>
                            @else
                                <h3 class="title">{!!lms_show_price($jobPost)!!}</h3>
                            @endif
                        </div>
                        
                        @if (!$jobPostEnrolled)
                            @if ($jobPost->is_paid)
                                @if (auth()->check())
                                    @if ($jobPost->cart())
                                        <form action="{{ route('cart-save', ['type' => 'job-post', 'type_id' => $jobPost->id]) }}" id="cart-form" 
                                            class="mb-0 ml-1" method="POST">
                                            @csrf
                                            <button type="submit" class="rts-btn btn-border">
                                                <i class="fa-regular fa-shopping-cart"></i> Remove From Cart
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('cart-save', ['type' => 'job-post', 'type_id' => $jobPost->id]) }}" id="cart-form" 
                                            class="mb-0 ml-1" method="POST">
                                            @csrf
                                            <button type="submit" class="rts-btn btn-border">
                                                <i class="fa-solid fa-shopping-cart"></i> Add To Cart
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{route('login')}}" class="rts-btn btn-border"><i class="fa-solid fa-shopping-cart"></i> Add To Cart</a>
                                @endif                                
                                
                                <form action="{{ route('job-posts.create_order', ['slug' => $jobPost->slug]) }}" id="pay-form" 
                                    class="mb-0 ml-1" method="POST">
                                    @csrf
                                    <button type="submit" class="rts-btn btn-primary">
                                        BUY NOW
                                    </button>
                                </form>
                                <script>
                                    $(document).ready(function() {
                                        $('#pay-form').submit(function(e) {
                                            e.preventDefault();
                                            $('#pay-form [type="submit"]').prop('disabled', true).html('<i class="fa fa-spin fa-circle-notch"></i> Processing...');
                                            this.submit();
                                        });
                                    });
                                </script>
                            @else
                                <form action="{{ route('job-posts.enroll', ['slug' => $jobPost->slug]) }}" id="enroll-form" 
                                    class="mb-0 ml-1" method="POST">
                                    @csrf
                                    <button type="submit" class="rts-btn btn-primary">
                                        ENROLL
                                    </button>
                                </form>
                                <script>
                                    $(document).ready(function() {
                                        $('#enroll-form').submit(function(e) {
                                            e.preventDefault();
                                            $('#enroll-form [type="submit"]').prop('disabled', true).html('<i class="fa fa-spin fa-circle-notch"></i> Processing...');
                                            this.submit();
                                        });
                                    });
                                </script>
                            @endif
                        @endif

                        <div class="what-includes">
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-regular fa-tag"></i>
                                    <span>Category</span>
                                </div>
                                <div class="right">
                                    <span>{{$jobPost->category->title}}</span>
                                </div>
                            </div>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    <span>Update</span>
                                </div>
                                <div class="right">
                                    <span>{{lms_format_date($jobPost->updated_at)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- single course-sidebar end -->
                </div>
                <!-- right- sticky bar area end -->
                <!-- right- sticky bar area -->
                <div class="right-course-details mt--30">
                    <!-- single course-sidebar -->
                    <div class="course-side-bar">
                        <!-- course single sidebar -->
                        <div class="course-single-information">
                            <h5 class="title">A job post by</h5>
                            <div class="body">
                                <div class="author">
                                    <img src="{{asset('admin-assets/assets/images/profile-40.png')}}" alt="Instructor">
                                    <span>{{$jobPost->user->first_name}}</span>
                                </div>
                            </div>
                        </div>
                        <!-- course single sidebar end-->
                        <!-- course single sidebar -->
                        <div class="course-single-information d-none">
                            <h5 class="title">Material Includes</h5>
                            <div class="body">
                                <!-- ingle check -->
                                <div class="single-check">
                                    <i class="fa-light fa-circle-check"></i>
                                    Flexible Deadlines
                                </div>
                                <!-- ingle check end -->
                                <!-- ingle check -->
                                <div class="single-check">
                                    <i class="fa-light fa-circle-check"></i>
                                    Hours of live- demo
                                </div>
                                <!-- ingle check end -->
                                <!-- ingle check -->
                                <div class="single-check">
                                    <i class="fa-light fa-circle-check"></i>
                                    Hours of live- demo
                                </div>
                                <!-- ingle check end -->
                                <!-- ingle check -->
                                <div class="single-check">
                                    <i class="fa-light fa-circle-check"></i>
                                    200+ downloadable resoursces
                                </div>
                                <!-- ingle check end -->
                            </div>
                        </div>
                        <!-- course single sidebar end-->

                        @if ($requirements)
                            <!-- course single sidebar -->
                            <div class="course-single-information">
                                <h5 class="title">Requirements</h5>
                                <div class="body">
                                    @foreach ($requirements->name as $d)
                                        <!-- ingle check -->
                                        <div class="single-check">
                                            <i class="fa-light fa-circle-check"></i>
                                            {{$d}}
                                        </div>
                                        <!-- ingle check end -->
                                    @endforeach
                                </div>
                            </div>
                            <!-- course single sidebar end-->
                        @endif
                        
                        <!-- course single sidebar -->
                        {{-- <div class="course-single-information">
                            <h5 class="title">Tags</h5>
                            <div class="body">
                                <div class="tags-wrapper">
                                    <!-- single tags -->
                                    <span>Course</span>
                                    <span>Design</span>
                                    <span>Web development</span>
                                    <span>Business</span>
                                    <span>UI/UX</span>
                                    <span>Financial</span>
                                    <!-- single tags end -->
                                </div>
                            </div>
                        </div> --}}
                        <!-- course single sidebar end-->
                        <!-- course single sidebar -->
                        <div class="course-single-information">
                            <h5 class="title">Share</h5>
                            <div class="body">
                                <!-- social-share-course-sidebar -->
                                <div class="social-share-course-side-bar">
                                    <ul>
                                        <li><a href="#" target="_blank" id="share-facebook" class="bs-tt" title="Share on Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                                        <li><a href="#" target="_blank" id="share-linkedin" class="bs-tt" title="Share on Linkedin"><i class="fa-brands fa-linkedin"></i></a></li>
                                        <li><a href="#" target="_blank" id="share-pinterest" class="bs-tt" title="Share on Pinterest"><i class="fa-brands fa-pinterest"></i></a></li>
                                        <li>
                                            <a href="#" target="_blank" id="share-twitter" class="bs-tt" title="Share on X">   
                                                <svg xmlns="http://www.w3.org/2000/svg" class="align-baseline" width="24" height="24" fill="#110c2d" viewBox="0 0 256 256"><path d="M215,219.85a8,8,0,0,1-7,4.15H160a8,8,0,0,1-6.75-3.71l-40.49-63.63L53.92,221.38a8,8,0,0,1-11.84-10.76l61.77-68L41.25,44.3A8,8,0,0,1,48,32H96a8,8,0,0,1,6.75,3.71l40.49,63.63,58.84-64.72a8,8,0,0,1,11.84,10.76l-61.77,67.95,62.6,98.38A8,8,0,0,1,215,219.85Z"></path></svg>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- social-share-course-sidebar end -->
                            </div>
                        </div>
                        <!-- course single sidebar end-->

                        <!-- course single sidebar -->
                        <div class="course-single-information last">
                            <h5 class="title">Audience</h5>
                            <div class="body">
                                <!-- ingle check -->
                                <div class="single-check">
                                    <i class="fa-light fa-circle-check"></i>
                                    Suitable for {{$jobPost->level}} students
                                </div>
                                <!-- ingle check end -->
                            </div>
                        </div>
                        <!-- course single sidebar end-->
                    </div>
                    <!-- single course-sidebar end -->
                </div>
                <!-- right- sticky bar area end -->
            </div>
        </div>
    </div>
</div>
<!-- course details area end -->
<script>
    const currentURL = encodeURIComponent(window.location.href);
    const courseTitle = encodeURIComponent("{{$jobPost->title}}");

    document.getElementById('share-facebook').href = `https://www.facebook.com/sharer/sharer.php?u=${currentURL}`;
    document.getElementById('share-linkedin').href = `https://www.linkedin.com/sharing/share-offsite/?url=${currentURL}`;
    document.getElementById('share-pinterest').href = `https://pinterest.com/pin/create/button/?url=${currentURL}&description=${courseTitle}`;
    document.getElementById('share-twitter').href = `https://twitter.com/intent/tweet?text=${courseTitle}&url=${currentURL}`;
</script>
@endsection