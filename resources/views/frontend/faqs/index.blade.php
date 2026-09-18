@extends('layouts.frontend.skeleton')

@section('title', 'Frequently Asked Questions')

@section('content')

    <div class="rts-bread-crumbarea-1 rts-section-gap bg_image">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main-wrapper">
                        <h1 class="title">Frequently Asked Questions</h1>

                        <div class="pagination-wrapper">
                            <a href="{{ route('home') }}">Home</a>
                            <i class="fa-regular fa-chevron-right"></i>
                            <a class="active" >FAQ</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="rts-section-gap">
        <div class="container-sm">

            <div class="row justify-content-center">
                <div class="col-lg-10 text-center mb-5">

                    <h2>
                        Aviation Course FAQs
                    </h2>

                    <p>
                        Find answers to common questions about Cabin Crew,
                        Airport Operations, Pilot Training (CPL / PPL),
                        Drone Training, Air Cargo Operations, Travel & Tourism,
                        RTR (Aero), Customer Service Agent, and Airport Security courses.
                    </p>

                </div>
            </div>

            <div class="row">

                @foreach ($categories as $category)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('faq.show', $category->slug) }}" class="faq-category-card h-100">

                            <div class="faq-category-card__content">

                                <div class="faq-category-card__top">
                                    <span class="faq-category-card__badge">
                                        {{ $category->faqs_count }} FAQs
                                    </span>
                                </div>

                                <h5 class="faq-category-card__title">
                                    {{ $category->name }}
                                </h5>

                                <p class="faq-category-card__desc">
                                    {{ $category->description }}
                                </p>

                                <div class="faq-category-card__footer">
                                    <span>Explore FAQs</span>

                                    <i class="fa-regular fa-arrow-right"></i>
                                </div>

                            </div>
                        </a>
                    </div>
                @endforeach

            </div>

        </div>

        <style>
            .faq-category-card {
                display: block;
                height: 100%;
                text-decoration: none;
                color: inherit;
            }

            .faq-category-card__content {
                height: 100%;
                padding: 30px;
                border-radius: 18px;
                background: #fff;
                border: 1px solid rgba(0, 0, 0, .06);
                box-shadow: 0 10px 30px rgba(0, 0, 0, .04);
                transition: all .35s ease;
                position: relative;
                overflow: hidden;
            }

            .faq-category-card__content::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background: var(--color-primary);
                transform: scaleX(0);
                transform-origin: left;
                transition: .35s ease;
            }

            .faq-category-card:hover .faq-category-card__content {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, .10);
            }

            .faq-category-card:hover .faq-category-card__content::before {
                transform: scaleX(1);
            }

            .faq-category-card__top {
                margin-bottom: 20px;
            }

            .faq-category-card__badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 6px 14px;
                border-radius: 50px;
                font-size: 13px;
                font-weight: 600;
                background: var(--color-primary4);
                color: var(--color-primary);
            }

            .faq-category-card__title {
                font-size: 22px;
                line-height: 1.4;
                margin-bottom: 15px;
                transition: .3s;
            }

            .faq-category-card:hover .faq-category-card__title {
                color: var(--color-primary);
            }

            .faq-category-card__desc {
                color: var(--color-body);
                line-height: 1.8;
                margin-bottom: 25px;
            }

            .faq-category-card__footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-weight: 600;
                color: var(--color-primary);
                margin-top: auto;
            }

            .faq-category-card__footer i {
                transition: .3s ease;
            }

            .faq-category-card:hover .faq-category-card__footer i {
                transform: translateX(6px);
            }

            @media(max-width: 767px) {
                .gallery-tabs{
                    gap: 10px 0px;
                }
            }

        </style>
    </section>

@endsection

@push('scripts')
    <script type="application/ld+json">
{
    "@context":"https://schema.org",
    "@type":"CollectionPage",
    "name":"Frequently Asked Questions",
    "description":"Frequently Asked Questions about aviation training courses including Cabin Crew, Airport Operations, Pilot Training, Drone Training, Air Cargo Operations, Travel & Tourism, RTR (Aero), Customer Service Agent, and Airport Security."
}
</script>
@endpush
