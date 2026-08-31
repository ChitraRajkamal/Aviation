<nav class="pcoded-navbar" navbar-theme="theme1" active-item-theme="theme3" sub-item-theme="theme2"
    active-item-style="style0">
    <div class="pcoded-inner-navbar main-menu">
        <ul class="pcoded-item pcoded-left-item mt-2">
            @if (lms_is_admin())
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="pcoded-micon"><i class="ph ph-house"></i></span>
                        <span class="pcoded-mtext">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.course_categories.index') }}">
                        <span class="pcoded-micon"><i class="ph ph-tag"></i></span>
                        <span class="pcoded-mtext">Course Categories</span>
                    </a>
                </li>
                <li class="menu_courses">
                    <a href="{{ route('admin.courses.index') }}">
                        <span class="pcoded-micon"><i class="ph ph-book-open"></i></span>
                        <span class="pcoded-mtext">Courses</span>
                    </a>
                </li>
                <li class="menu_qbank">
                    <a href="{{ route('admin.qbank.index') }}">
                        <span class="pcoded-micon"><i class="ph ph-question"></i></span>
                        <span class="pcoded-mtext">Question Bank</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.exam_categories.index') }}">
                        <span class="pcoded-micon"><i class="ph ph-tag"></i></span>
                        <span class="pcoded-mtext">Exam Categories</span>
                    </a>
                </li>
                <li class="menu_exams">
                    <a href="{{ route('admin.exams.index') }}">
                        <span class="pcoded-micon"><i class="ph ph-notebook"></i></span>
                        <span class="pcoded-mtext">Exams</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.job-post-categories.index') }}">
                        <span class="pcoded-micon"><i class="ph ph-tag"></i></span>
                        <span class="pcoded-mtext">Job Post Categories</span>
                    </a>
                </li>
                <li class="menu_job_posts">
                    <a href="{{ route('admin.job-posts.index') }}">
                        <span class="pcoded-micon"><i class="ph ph-briefcase"></i></span>
                        <span class="pcoded-mtext">Job Posts</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.recorded-video-categories.index') }}">
                        <span class="pcoded-micon"><i class="ph ph-tag"></i></span>
                        <span class="pcoded-mtext">Recorded Video Categories</span>
                    </a>
                </li>
                <li class="menu_recorded_videos">
                    <a href="{{ route('admin.recorded-videos.index') }}">
                        <span class="pcoded-micon"><i class="ph ph-video"></i></span>
                        <span class="pcoded-mtext">Recorded Videos</span>
                    </a>
                </li>
                <li class="menu_payments">
                    <a href="{{ route('admin.payments') }}">
                        <span class="pcoded-micon"><i class="ph ph-credit-card"></i></span>
                        <span class="pcoded-mtext">Payments</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ph ph-users"></i></span>
                        <span class="pcoded-mtext">Users</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li>
                            <a href="{{ route('admin.users.admin') }}"><span class="pcoded-mtext">Staffs</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.student') }}"><span class="pcoded-mtext">Students</span></a>
                        </li>
                    </ul>
                </li>
                <li class="menu_organizations">
                    <a href="{{ route('admin.users.organizations') }}">
                        <span class="pcoded-micon"><i class="ph ph-notebook"></i></span>
                        <span class="pcoded-mtext">Organizations</span>
                    </a>
                </li>
                <li class="menu_subscriptions">
                    <a href="{{ route('admin.subscriptions.index') }}">
                        <span class="pcoded-micon"><i class="ph ph-bookmark"></i></span>
                        <span class="pcoded-mtext">Subscriptions</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ph ph-gear-six"></i></span>
                        <span class="pcoded-mtext">Settings</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li>
                            <a href="{{ route('admin.settings') }}"><span class="pcoded-mtext">General</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.certificate') }}"><span class="pcoded-mtext">Certificate</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.social-media') }}"><span class="pcoded-mtext">Social Media</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.razorpay') }}"><span class="pcoded-mtext">Razorpay</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.social-login') }}"><span class="pcoded-mtext">Social Login</span></a>
                        </li>
                    </ul>
                </li>
            @else
                @php
                    $menus = lms_organization_menus(auth()->user()->role_id);
                @endphp
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="pcoded-micon"><i class="ph ph-house"></i></span>
                        <span class="pcoded-mtext">Dashboard</span>
                    </a>
                </li>
                @if (lms_can_access('admin.courses.index', $menus))
                    <li class="menu_courses">
                        <a href="{{ route('admin.courses.index') }}">
                            <span class="pcoded-micon"><i class="ph ph-book-open"></i></span>
                            <span class="pcoded-mtext">Courses</span>
                        </a>
                    </li>
                @endif
                @if (lms_can_access('admin.job-posts.index', $menus))
                    <li class="menu_job_posts">
                        <a href="{{ route('admin.job-posts.index') }}">
                            <span class="pcoded-micon"><i class="ph ph-briefcase"></i></span>
                            <span class="pcoded-mtext">Job Posts</span>
                        </a>
                    </li>
                @endif
                @if (lms_can_access('admin.recorded-videos.index', $menus))
                    <li class="menu_recorded_videos">
                        <a href="{{ route('admin.recorded-videos.index') }}">
                            <span class="pcoded-micon"><i class="ph ph-video"></i></span>
                            <span class="pcoded-mtext">Recorded Videos</span>
                        </a>
                    </li>
                @endif
                @if (lms_can_access('admin.qbank.index', $menus))
                    <li class="menu_qbank d-none">
                        <a href="{{ route('admin.qbank.index') }}">
                            <span class="pcoded-micon"><i class="ph ph-question"></i></span>
                            <span class="pcoded-mtext">Question Bank</span>
                        </a>
                    </li>
                @endif
                @if (lms_can_access('admin.exams.index', $menus))
                    <li class="menu_exams">
                        <a href="{{ route('admin.exams.index') }}">
                            <span class="pcoded-micon"><i class="ph ph-notebook"></i></span>
                            <span class="pcoded-mtext">Exams</span>
                        </a>
                    </li>
                @endif
                @if (lms_can_access('admin.users.student', $menus))
                    <li class="menu_users_students">
                        <a href="{{ route('admin.users.student') }}">
                            <span class="pcoded-micon"><i class="ph ph-graduation-cap"></i></span>
                            <span class="pcoded-mtext">Students</span>
                        </a>
                    </li>
                @endif
                @if (lms_can_access('admin.users.organization', $menus))
                    <li class="menu_users_staffs">
                        <a href="{{ route('admin.users.organization') }}">
                            <span class="pcoded-micon"><i class="ph ph-users"></i></span>
                            <span class="pcoded-mtext">Staffs</span>
                        </a>
                    </li>
                @endif
                @if (lms_can_access('admin.roles.organization', $menus))
                    <li class="menu_roles">
                        <a href="{{ route('admin.roles.organization') }}">
                            <span class="pcoded-micon"><i class="ph ph-key"></i></span>
                            <span class="pcoded-mtext">Roles</span>
                        </a>
                    </li>
                @endif
                @if (lms_is_organization_admin())
                    <li class="menu_subscriptions">
                        <a href="{{ route('admin.subscriptions.index') }}">
                            <span class="pcoded-micon"><i class="ph ph-bookmark"></i></span>
                            <span class="pcoded-mtext">Subscriptions</span>
                        </a>
                    </li>
                @endif
                <li class="menu_settings">
                    <a href="{{ route('admin.settings.organization') }}">
                        <span class="pcoded-micon"><i class="ph ph-gear"></i></span>
                        <span class="pcoded-mtext">Settings</span>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('log.out') }}">
                    <span class="pcoded-micon"><i class="ph ph-sign-out"></i></span>
                    <span class="pcoded-mtext">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
