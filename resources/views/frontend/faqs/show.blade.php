@extends('layouts.frontend.skeleton')
@section('title', $category->meta_title ?: $category->name . ' FAQs')

@section('content')

    <div class="rts-bread-crumbarea-1 rts-section-gap bg_image">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main-wrapper">
                        <h1 class="title">{{ $category->name }} FAQs</h1>

                        <div class="pagination-wrapper">
                            <a href="{{ route('home') }}">Home</a>
                            <i class="fa-regular fa-chevron-right"></i>

                            <a href="{{ route('faq.index') }}">FAQ</a>
                            <i class="fa-regular fa-chevron-right"></i>

                            <a class="active" href="javascript:;">
                                {{ $category->name }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="rts-section-gap faq-container">
        <div class="container-sm">

            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="mb-5">
                        <h2>{{ $category->name }} Frequently Asked Questions</h2>

                        <p>
                            Find answers to common questions about
                            {{ $category->name }} including eligibility,
                            training, certification, career opportunities,
                            placement assistance, salary expectations,
                            and industry requirements.
                        </p>
                    </div>

                    <div class="accordion" id="faqAccordion">

                        @foreach ($faqs as $faq)
                            <div class="accordion-item mb-3 border rounded">
                                <h2 class="accordion-header" id="heading{{ $faq->id }}">

                                    <button class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}"
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $faq->id }}">

                                        {{ $faq->question }}

                                    </button>
                                </h2>

                                <div id="collapse{{ $faq->id }}"
                                    class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqAccordion">

                                    <div class="accordion-body p-4">

                                        {!! nl2br(e($faq->answer)) !!}

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>
            </div>

        </div>
    </section>

    @if ($categories->count())
        <section class="pb--80">
            <div class="container-sm">

                <h3 class="mb-4">
                    Related FAQ Categories
                </h3>

                <div class="row">

                    @foreach ($categories as $related)
                        <div class="col-lg-4 col-md-6 mb-3">

                            <a href="{{ route('faq.show', $related->slug) }}" class="single-facilityes shadow d-block">

                                <div class="information p-4">
                                    <h6 class="title fw-normal mb-0">
                                        {{ $related->name }}
                                        <span class="text-muted small">({{ $related->faqs_count }} FAQs)</span>
                                    </h6>
                                </div>

                            </a>

                        </div>
                    @endforeach

                </div>

            </div>
        </section>
    @endif

    <style>
        .faq-container .accordion-button{
            font-size: 18px;
            font-weight: 500;
        }
        .faq-container .accordion-button:not(.collapsed){
            color: white;
            background-color: var(--color-primary);
        }
    </style>

@endsection

@section('extra-scripts')
    {{-- FAQ Schema --}}
    <script type="application/ld+json">
{
    "@context":"https://schema.org",
    "@type":"FAQPage",
    "mainEntity":[
        @foreach($faqs as $faq)
        {
            "@type":"Question",
            "name": @json($faq->question),
            "acceptedAnswer":{
                "@type":"Answer",
                "text": @json($faq->answer)
            }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>

    {{-- Breadcrumb Schema --}}
    <script type="application/ld+json">
{
 "@context":"https://schema.org",
 "@type":"BreadcrumbList",
 "itemListElement":[
   {
     "@type":"ListItem",
     "position":1,
     "name":"Home",
     "item":"{{ url('/') }}"
   },
   {
     "@type":"ListItem",
     "position":2,
     "name":"FAQ",
     "item":"{{ route('faq.index') }}"
   },
   {
     "@type":"ListItem",
     "position":3,
     "name":"{{ $category->name }}"
   }
 ]
}
</script>
@endsection
