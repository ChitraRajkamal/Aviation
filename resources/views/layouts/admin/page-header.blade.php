@php
    $icon = $icon ?? '';
    $title = $title ?? '';
    $tagline = $tagline ?? '';
    $breadcrumbs = $breadcrumbs ?? [];
    $links = $links ?? [];
@endphp
<div class="row align-items-end mb-4">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>
                    @if ($icon)
                        <i class="header-icon {{ $icon }} nn"></i>
                    @endif
                    {{ $title }}
                    @if ($links)
                        @foreach ($links as $k => $item)
                            @php
                                if(!$item) continue;
                            @endphp
                            <a href="{{$item['route']}}" class="{{$item['class']}} sloc" title="{{$item['title']}}">{!!$item['label']!!}</a>
                        @endforeach
                    @endif
                    
                </h4>
                @if ($tagline)
                    <span>{{ $tagline }}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class="breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('')}}"> <i class="ph ph-house"></i> </a>
                </li>
                @if ($breadcrumbs)
                    @foreach ($breadcrumbs as $route => $item)
                        <li class="breadcrumb-item"><a href="{{$route}}">{{$item}}</a></li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
