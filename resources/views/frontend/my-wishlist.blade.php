@extends('layouts.frontend.skeleton')

@section('title', 'My Wishlist')

@section('content')

@include('layouts.frontend.dashboard-header')

<!-- rts dahboard-area-main-wrapper -->
<div class="dashboard--area-main pt--100">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                @include('layouts.frontend.dashboard-sidebar', ['activeMenu' => 'my-wishlist'])
            </div>
            <div class="col-lg-9">
                <div class="right-sidebar-dashboard no-border no-padding">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- in progress course area -->
                            <div class="in-progress-course-wrapper  title-between-dashboard mb--10">
								{{-- <h5 class="title mb-2">My Wishlist ({{count($wishlist)}})</h5> --}}
                                <h5 class="title mb-2">My Wishlist</h5>
                            </div>
                            <!-- in progress course area end -->

                            <!-- my course enroll wrapper -->
                            <div class="my-course-enroll-wrapper-board">
                                <!-- single course inroll -->
                                <div class="single-course-inroll-board head">
                                    <div class="name">
                                        <p>Type</p>
                                    </div>
                                    <div class="name">
                                        <p>Course / Exam</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Added By</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Added On</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Action</p>
                                    </div>
                                </div>
                                @if ($wishlist->isEmpty())
                                    <div class="alert alert-light text-center my-5">
                                        <i class="fa-regular fa-info-circle fa-3x text-success mb-3"></i><br>Your wishlist is empty
                                    </div>
                                @endif
                                @foreach ($wishlist as $e)
									@php
										if(!$e->item) continue;
									@endphp
                                    <div class="single-course-inroll-board">
                                        <div class="name">
                                            <p>{{ucfirst($e->type)}}</p>
                                        </div>
                                        <div class="name">
                                            <p>
                                                {{$e->item->title}} 
                                                @if ($e->type == 'course')
                                                    <a href="{{route('courses.details', ['slug' => $e->item->slug])}}" class="fw-medium" target="_blank"><i class="fa-regular fa-eye text-danger"></i></a>
                                                @else
                                                    <a href="{{route('exams.details', ['slug' => $e->item->slug])}}" class="fw-medium" target="_blank"><i class="fa-regular fa-eye text-danger"></i></a>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="enroll">
                                            <p>{{$e->item->user->full_name}}</p>
                                        </div>
                                        <div class="enroll">
                                            <p>{{lms_format_date($e->created_at)}}</p>
                                        </div>
                                        <div class="enroll">
                                            <form action="{{ route('ajax.wishlist_remove', ['type' => $e->type, 'type_id' => $e->type_id]) }}" id="delete-form-{{$e->id}}" 
                                                class="d-inline-block mb-0 confirm-action" data-action="submit" 
                                                data-element_id="delete-form-{{$e->id}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-mini bs-tt" title="Delete Item" data-placement="left">
                                                    <i class="fa-regular fa-trash mr-0"></i> Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="custom-pagination">
                                    {{$wishlist->links()}}
                                </div>
                            </div>
                            <!-- my course enroll wrapper end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts dahboard-area-main-wrapper end -->

@endsection