<!-- dashboard banner area start -->
<div class="dashboard-banner-area-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-banner-area-start bg_image  student-dashboard">
                    <div class="rating-area-banner-dashboard d-none">
                        <a href="become-instructor.html" class="create-btn"><i class="fa-regular fa-circle-plus"></i> Become an Instructor</a>
                    </div>
                    <div class="author-profile-image-and-name">
                        @php
                            $image = auth()->user()->image;
                            $thumb = lms_profile_placeholder();                            
                            if($image) $thumb = lms_storage($image);
                        @endphp
                        <div class="profile-pic shadow">
                            <img src="{{$thumb}}" alt="dashboard">
                        </div>
                        <div class="name-desig">
                            <h1 class="title">{{auth()->user()->full_name}}</h1>
                            <div class="course-vedio">
                                <div class="single">
                                    <i class="fa-thin fa-graduation-cap"></i>
                                    <span>{{ $global_course_enrolled }} {{lms_plural('Course', $global_course_enrolled)}} Enrolled</span>
                                </div>
                                <div class="single">
                                    <i class="fa-thin fa-notebook"></i>
                                    <span>{{ $global_exam_enrolled }} {{lms_plural('Exam', $global_exam_enrolled)}} Enrolled</span>
                                </div>
                                <div class="single d-none">
                                    <i class="fa-thin fa-file-certificate"></i>
                                    <span>0 Certificate</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- dashboard banner area end -->