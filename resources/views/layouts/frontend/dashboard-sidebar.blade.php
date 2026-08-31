@php
    $activeMenu = $activeMenu ?? 'dashboard';
    $menus = [
        'dashboard' => [
            'link'      => route('dashboard'),
            'title'     => 'Dashboard',
            'icon'      => 'fa-light fa-house'
        ],
        'my-profile' => [
            'link'      => route('my-profile'),
            'title'     => 'My Profile',
            'icon'      => 'fa-regular fa-user'
        ],
        'my-courses' =>[
            'link'      => route('my-courses'),
            'title'     => 'Courses',
            'icon'      => 'fa-light fa-graduation-cap'
        ],
        'my-exams' =>[
            'link'      => route('my-exams'),
            'title'     => 'Exams',
            'icon'      => 'fa-light fa-notebook'
        ],
        'my-recorded-videos' =>[
            'link'      => route('my-recorded-videos'),
            'title'     => 'Videos',
            'icon'      => 'fa-light fa-video'
        ],
        'my-job-posts' =>[
            'link'      => route('my-job-posts'),
            'title'     => 'Jobs',
            'icon'      => 'fa-light fa-briefcase'
        ],
        'my-wishlist' => [
            'link'      => route('my-wishlist'),
            'title'     => 'Wishlist',
            'icon'      => 'fa-sharp fa-light fa-bookmark'
        ],
        'reviews' => [
            'link'      => route('my-ratings'),
            'title'     => 'Ratings',
            'icon'      => 'fa-regular fa-star'
        ],
        /*'quiz-attempts' => [
            'link'      => '#',
            'title'     => 'Quiz Attempts',
            'icon'      => 'fa-sharp fa-light fa-bullseye-pointer'
        ],
        'order-history' => [
            'link'      => '#',
            'title'     => 'Order History',
            'icon'      => 'fa-sharp fa-light fa-bag-shopping'
        ],
        'question' => [
            'link'      => '#',
            'title'     => 'Question & Answer',
            'icon'      => 'fa-regular fa-circle-question'
        ],
        'calendar' => [
            'link'      => '#',
            'title'     => 'Calendar',
            'icon'      => 'fa-light fa-calendar-days'
        ]*/
    ];
@endphp
<div class="left-sindebar-dashboard mb-5">
    <div class="dashboard-left-single-wrapper">
        @foreach ($menus as $mk => $m)
            <a href="{{$m['link']}}" class="single-item {{$mk == $activeMenu ? 'active' : ''}}">
                <i class="{{$m['icon']}}"></i>
                <p>{{$m['title']}}</p>
            </a>
        @endforeach
    </div>
    <div class="dashboard-left-single-wrapper bbnone mt--40">
        <h4 class="title mb--5 text-muted">Settings</h4>
        <a href="{{route('change-password')}}" class="single-item {{$activeMenu == 'change-password' ? 'active' : ''}}">
            <i class="fa-light fa-key fa-gear"></i>
            <p>Change Password</p>
        </a>
        <a href="{{route('log.out')}}" class="single-item">
            <i class="fa-light fa-right-from-bracket"></i>
            <p>Logout</p>
        </a>
    </div>
</div>