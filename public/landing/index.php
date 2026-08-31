<?php
session_start();
$_SESSION['cfs'] = true;
$enroll_link = "https://api.whatsapp.com/send/?phone=917550289600&text=Hi%21+I+want+to+enroll+in+the+Aviation+Course&type=phone_number&app_absent=0";
?>
<html lang="en">

<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-11029575685"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-11029575685');
</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>First Fly Aviation</title>
    <link href="assets/images/favicon.png" rel="icon">
    <link rel="icon" href="assets/images/favicon.png" type="image/png" />
    <meta name="theme-color" content="#0a2463" />
    <meta name="theme-color" content="#000000">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600&amp;display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
        section {
            scroll-margin-top: 80px;
        }
    </style>
</head>

<body>
    <noscript>You need to enable JavaScript to run this app.</noscript>
    <div id="root">
        <div class="App">
            <div class="min-h-screen">
                <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md shadow-md">
                    <div class="container mx-auto px-4">
                        <div class="flex items-center justify-between h-20">
                            <div class="flex items-center gap-2">
                                <img src="assets/images/logo.png" alt="First Fly Aviation Logo">
                            </div>
                            <nav class="hidden md:flex md:items-center gap-8">
                                <a
                                    href="#combo-course-section"
                                    class="text-gray-700 hover:text-[#3E92CC] font-medium transition-colors duration-300">Combo Course</a>
                                <a href="#about-section"
                                    class="text-gray-700 hover:text-[#3E92CC] font-medium transition-colors duration-300">About</a>
                                <a
                                    href="#testimonials-section"
                                    class="text-gray-700 hover:text-[#3E92CC] font-medium transition-colors duration-300">Reviews</a>
                                <a
                                    href="#faq-section"
                                    class="text-gray-700 hover:text-[#3E92CC] font-medium transition-colors duration-300">FAQ</a>
                                <button data-title="Enroll Now"
                                    class="openLeadPopup inline-flex md:hidden items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-9 py-2 bg-[#FFD700] hover:bg-[#FFD700]/90 text-[#0A2463] font-bold px-6 shadow-lg hover:shadow-xl transition-all duration-300">Enroll Now</button>
                            </nav>
                            <div class="hidden md:block">
                                <button data-title="Enroll Now"
                                    class="openLeadPopup inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-9 py-2 bg-[#FFD700] hover:bg-[#FFD700]/90 text-[#0A2463] font-bold px-6 shadow-lg hover:shadow-xl transition-all duration-300">Enroll Now
                                </button>
                            </div>
                            <button class="md:hidden text-[#0A2463]" id="menuButton">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-menu w-6 h-6" aria-hidden="true">
                                    <path d="M4 12h16"></path>
                                    <path d="M4 18h16"></path>
                                    <path d="M4 6h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>
                <!-- Hero Section -->
                <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
                    <div class="absolute inset-0 z-0">
                        <img alt="Aircraft on runway" class="w-full h-full object-cover"
                            src="https://images.unsplash.com/photo-1729156047036-d2ea0ab44ff4">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-[#0A2463]/95 via-[#0A2463]/85 to-[#0A2463]/40">
                        </div>
                    </div>
                    <div
                        class="absolute top-28 right-6 md:right-16 z-20 hidden lg:block animate-[float_4s_ease-in-out_infinite]">
                        <div
                            class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4 shadow-2xl">
                            <div class="text-[#FFD700] text-sm font-semibold mb-1">Average Starting Salary</div>
                            <div class="text-white text-3xl font-bold">₹40K+</div>
                        </div>
                    </div>
                    <div
                        class="absolute bottom-32 right-6 md:right-16 z-20 hidden lg:block animate-[float_5s_ease-in-out_infinite]">
                        <div
                            class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4 shadow-2xl">
                            <div class="text-[#FFD700] text-sm font-semibold mb-1">Placement Support</div>
                            <div class="text-white text-3xl font-bold">100%</div>
                        </div>
                    </div>
                    <div class="relative z-10 container mx-auto px-4 py-28">
                        <div class="max-w-4xl">
                            <div
                                class="inline-flex mt-4 items-center gap-2 bg-[#FFD700] text-[#0A2463] px-5 py-2 rounded-full font-semibold mb-6 shadow-xl animate-pulse">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Admissions Closing Soon - Limited Seats Available
                            </div>
                            <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight mb-6">
                                Turn Your Dream of Flying Into a
                                <span class="text-[#FFD700]">High-Paying Career</span>
                            </h1>
                            <p class="text-xl md:text-2xl text-gray-200 leading-relaxed mb-6 max-w-3xl">
                                Start Your Aviation Journey with Industry-Ready Training, Placement Support &
                                Airline-Level Grooming.
                            </p>
                            <div class="flex flex-wrap gap-3 mb-8">
                                <div
                                    class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 text-sm text-white font-medium">
                                    ✈️ 12th Pass / Graduates
                                </div>
                                <div
                                    class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 text-sm text-white font-medium">
                                    🎓 Cabin Crew Aspirants
                                </div>
                                <div
                                    class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 text-sm text-white font-medium">
                                    💼 Airport Job Seekers
                                </div>
                                <div
                                    class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 text-sm text-white font-medium">
                                    🌍 Travel Career Opportunities
                                </div>
                            </div>
                            <div
                                class="bg-white/10 border border-white/20 backdrop-blur-md rounded-2xl p-5 mb-8 max-w-2xl shadow-2xl">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-14 h-14 bg-[#FFD700] rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#0A2463]"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-white text-xl font-bold mb-1">
                                            No Experience? No Problem.
                                        </h3>
                                        <p class="text-gray-200">
                                            We Train You from Basics to Airline-Level Confidence.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-10">
                                <p class="text-white text-sm mb-3 uppercase tracking-widest font-semibold">
                                    Offer Ends In
                                </p>
                                <div
                                    class="inline-flex items-center gap-3 bg-white/95 backdrop-blur-sm px-6 py-4 rounded-2xl shadow-2xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-red-600 w-6 h-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <div class="flex items-center gap-2">
                                        <div class="text-center">
                                            <div id="days" class="text-2xl font-bold text-gray-900">00</div>
                                            <div class="text-xs text-gray-600 uppercase">Days</div>
                                        </div>
                                        <div class="text-2xl font-bold text-gray-400">:</div>
                                        <div class="text-center">
                                            <div id="hours" class="text-2xl font-bold text-gray-900">00</div>
                                            <div class="text-xs text-gray-600 uppercase">Hours</div>
                                        </div>
                                        <div class="text-2xl font-bold text-gray-400">:</div>
                                        <div class="text-center">
                                            <div id="minutes" class="text-2xl font-bold text-gray-900">00</div>
                                            <div class="text-xs text-gray-600 uppercase">Mins</div>
                                        </div>
                                        <div class="text-2xl font-bold text-gray-400">:</div>
                                        <div class="text-center">
                                            <div id="seconds" class="text-2xl font-bold text-gray-900">00</div>
                                            <div class="text-xs text-gray-600 uppercase">Secs</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4 mb-10">
                                <button type="button" data-title="Enroll Now"
                                    class="openLeadPopup inline-flex items-center justify-center gap-2 bg-[#FFD700] hover:bg-[#ffd900] text-[#0A2463] font-bold text-lg px-8 py-5 rounded-xl shadow-[0_10px_40px_rgba(255,215,0,0.35)] hover:scale-105 transition-all duration-300">
                                    👉 Enroll Now
                                </button>
                                <button type="button" data-title="Book Free Counseling"
                                    class="openLeadPopup inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-md border-2 border-white/20 hover:bg-white/20 text-white font-semibold text-lg px-8 py-5 rounded-xl transition-all duration-300">
                                    📞 Book Free Counseling
                                </button>
                                <a href="<?php echo $enroll_link; ?>" target="_blank"
                                    class="inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold text-lg px-8 py-5 rounded-xl shadow-xl transition-all duration-300 hover:scale-105">
                                    💬 WhatsApp Us
                                </a>
                            </div>
                            <div class="flex flex-wrap items-center gap-6 text-white/90 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></div>
                                    <span>5000+ Students Trained</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></div>
                                    <span>Interview & Placement Assistance</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></div>
                                    <span>Industry-Experienced Trainers</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white to-transparent z-10">
                    </div>
                </section>
                <style>
                    @keyframes float {

                        0%,
                        100% {
                            transform: translateY(0px);
                        }

                        50% {
                            transform: translateY(-10px);
                        }
                    }
                </style>
                <!-- High Growth Career Opportunities -->
                <section class="py-20 bg-white">
                    <div class="container mx-auto px-4">
                        <div class="text-center mb-16">
                            <div
                                class="inline-flex items-center gap-2 bg-[#FFD700] text-[#0A2463] px-5 py-2 rounded-full font-bold shadow-lg mb-5">
                                💰 High Growth Career Opportunities
                            </div>
                            <h2 class="text-4xl md:text-5xl font-bold text-[#0A2463] mb-4">Why Choose an Aviation
                                Career?</h2>
                            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Join India's fastest-growing industry
                                with unlimited opportunities and career growth</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            <div
                                class="rounded-xl bg-card text-card-foreground shadow border-2 border-gray-100 hover:border-[#3E92CC] transition-all duration-300 hover:shadow-xl group">
                                <div class="p-6 text-center">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-[#3E92CC] to-[#0A2463] rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trending-up w-8 h-8 text-white" aria-hidden="true">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-[#0A2463] mb-3">High Salary Potential</h3>
                                    <p class="text-gray-600">Start with ₹25,000-₹40,000/month and grow to ₹60,000+ with
                                        experience</p>
                                </div>
                            </div>
                            <div
                                class="rounded-xl bg-card text-card-foreground shadow border-2 border-gray-100 hover:border-[#3E92CC] transition-all duration-300 hover:shadow-xl group">
                                <div class="p-6 text-center">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-[#3E92CC] to-[#0A2463] rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-plane w-8 h-8 text-white" aria-hidden="true">
                                            <path
                                                d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-[#0A2463] mb-3">Airport Job Opportunities</h3>
                                    <p class="text-gray-600">Work with leading airlines and airport authorities across
                                        India</p>
                                </div>
                            </div>
                            <div
                                class="rounded-xl bg-card text-card-foreground shadow border-2 border-gray-100 hover:border-[#3E92CC] transition-all duration-300 hover:shadow-xl group">
                                <div class="p-6 text-center">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-[#3E92CC] to-[#0A2463] rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-graduation-cap w-8 h-8 text-white" aria-hidden="true">
                                            <path
                                                d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z">
                                            </path>
                                            <path d="M22 10v6"></path>
                                            <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-[#0A2463] mb-3">No Heavy Qualification</h3>
                                    <p class="text-gray-600">12th pass? That's enough! We'll train you for everything
                                        else</p>
                                </div>
                            </div>
                            <div
                                class="rounded-xl bg-card text-card-foreground shadow border-2 border-gray-100 hover:border-[#3E92CC] transition-all duration-300 hover:shadow-xl group">
                                <div class="p-6 text-center">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-[#3E92CC] to-[#0A2463] rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-award w-8 h-8 text-white" aria-hidden="true">
                                            <path
                                                d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526">
                                            </path>
                                            <circle cx="12" cy="8" r="6"></circle>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-[#0A2463] mb-3">Job-Ready Skills</h3>
                                    <p class="text-gray-600">Get certified and placement-ready in weeks, not years</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Combo course -->
                <section id="combo-course-section" class="py-20 bg-gradient-to-b from-white to-gray-50 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-96 h-96 bg-[#3E92CC]/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#FFD700]/10 rounded-full blur-3xl"></div>
                    <div class="container mx-auto px-4 relative z-10">
                        <div class="text-center max-w-4xl mx-auto mb-14">
                            <div class="inline-flex items-center gap-2 bg-[#0A2463] text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg mb-5 animate-pulse">
                                🔥 Most Preferred Combo Program
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold text-[#0A2463] leading-tight mb-6">
                                Airhostess + Airport Operations <span class="text-[#3E92CC]">Combo Course</span>
                            </h2>
                            <p class="text-lg text-gray-600 leading-relaxed">
                                Get dual-skill aviation training designed to increase your job opportunities in airlines, airports and customer service sectors.
                            </p>
                        </div>
                        <div class="max-w-7xl mx-auto">
                            <div class="bg-gradient-to-r from-[#0A2463] via-[#123b91] to-[#0A2463] rounded-[2rem] overflow-hidden shadow-[0_25px_80px_rgba(10,36,99,0.25)] relative">
                                <div class="absolute top-0 right-0 w-96 h-96 bg-[#FFD700]/10 rounded-full blur-3xl"></div>
                                <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#3E92CC]/20 rounded-full blur-3xl"></div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 relative z-10">
                                    <div class="p-8 md:p-10 lg:p-12 border-b lg:border-b-0 lg:border-r border-white/10">
                                        <div class="flex items-center gap-4 mb-8">
                                            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#FFD700] to-[#ffb700] flex items-center justify-center text-5xl shadow-2xl">
                                                💃
                                            </div>
                                            <div>
                                                <div class="text-[#FFD700] font-bold uppercase tracking-widest text-sm mb-1">
                                                    Course 01
                                                </div>
                                                <h3 class="text-3xl font-bold text-white">
                                                    Airhostess Training
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="space-y-5">
                                            <div class="flex items-start gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-[#FFD700] flex-shrink-0">
                                                    ✔
                                                </div>
                                                <div>
                                                    <h4 class="text-white font-bold text-lg mb-1">
                                                        Cabin Crew Grooming
                                                    </h4>
                                                    <p class="text-white/70 text-sm leading-relaxed">
                                                        Professional appearance, posture and airline-standard grooming techniques.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-[#FFD700] flex-shrink-0">
                                                    ✔
                                                </div>
                                                <div>
                                                    <h4 class="text-white font-bold text-lg mb-1">
                                                        Communication Skills
                                                    </h4>
                                                    <p class="text-white/70 text-sm leading-relaxed">
                                                        Build spoken English confidence and passenger interaction skills.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-[#FFD700] flex-shrink-0">
                                                    ✔
                                                </div>
                                                <div>
                                                    <h4 class="text-white font-bold text-lg mb-1">
                                                        Interview Preparation
                                                    </h4>
                                                    <p class="text-white/70 text-sm leading-relaxed">
                                                        Mock interviews and airline HR preparation for better placements.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-8 md:p-10 lg:p-12">
                                        <div class="flex items-center gap-4 mb-8">
                                            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#3E92CC] to-[#d9ecff] flex items-center justify-center text-5xl shadow-2xl">
                                                🛫
                                            </div>
                                            <div>
                                                <div class="text-[#FFD700] font-bold uppercase tracking-widest text-sm mb-1">
                                                    Course 02
                                                </div>
                                                <h3 class="text-3xl font-bold text-white">
                                                    Airport Operations
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="space-y-5">
                                            <div class="flex items-start gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-[#FFD700] flex-shrink-0">
                                                    ✔
                                                </div>
                                                <div>
                                                    <h4 class="text-white font-bold text-lg mb-1">
                                                        Passenger Handling
                                                    </h4>
                                                    <p class="text-white/70 text-sm leading-relaxed">
                                                        Learn check-in operations, boarding and airport customer service.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-[#FFD700] flex-shrink-0">
                                                    ✔
                                                </div>
                                                <div>
                                                    <h4 class="text-white font-bold text-lg mb-1">
                                                        Ground Operations
                                                    </h4>
                                                    <p class="text-white/70 text-sm leading-relaxed">
                                                        Airport safety, baggage handling and operational procedures training.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-[#FFD700] flex-shrink-0">
                                                    ✔
                                                </div>
                                                <div>
                                                    <h4 class="text-white font-bold text-lg mb-1">
                                                        Career Support
                                                    </h4>
                                                    <p class="text-white/70 text-sm leading-relaxed">
                                                        Placement guidance, resume building and aviation career counseling.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t border-white/10 px-8 md:px-10 lg:px-12 py-10">
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                                        <div class="lg:col-span-2">
                                            <div class="flex flex-wrap gap-3 mb-5">
                                                <div class="bg-[#FFD700] text-[#0A2463] px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                                    🎯 Dual Certification Program
                                                </div>
                                                <div class="bg-white/10 text-white px-4 py-2 rounded-full text-sm font-semibold border border-white/10">
                                                    ✈️ Airline + Airport Careers
                                                </div>
                                                <div class="bg-white/10 text-white px-4 py-2 rounded-full text-sm font-semibold border border-white/10">
                                                    💼 Better Job Opportunities
                                                </div>
                                            </div>
                                            <h3 class="text-3xl md:text-4xl font-bold text-white leading-tight mb-4">
                                                One Course. Multiple Career Opportunities.
                                            </h3>
                                            <p class="text-white/75 text-lg leading-relaxed">
                                                This combo course is specially designed for students who want maximum aviation career exposure with better placement possibilities.
                                            </p>
                                        </div>
                                        <div class="flex flex-col gap-4">
                                            <button type="button" data-title="Combo Course Enquiry" class="openLeadPopup inline-flex items-center justify-center bg-[#FFD700] hover:bg-[#ffe14a] text-[#0A2463] font-bold text-lg px-7 py-5 rounded-xl shadow-[0_10px_40px_rgba(255,215,0,0.35)] hover:scale-105 transition-all duration-300">
                                                🔥 Enquire Combo Course
                                            </button>
                                            <a href="<?php echo $enroll_link; ?>" target="_blank" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md text-white font-semibold px-7 py-5 rounded-xl transition-all duration-300">
                                                💬 WhatsApp Us
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
                <!-- Transformation Focused Aviation Training -->
                <section class="py-20 bg-gradient-to-b from-gray-50 to-white overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-72 h-72 bg-[#3E92CC]/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 right-0 w-72 h-72 bg-[#FFD700]/10 rounded-full blur-3xl"></div>
                    <div class="container mx-auto px-4 relative z-10">
                        <div class="text-center max-w-4xl mx-auto mb-16">
                            <div
                                class="inline-flex items-center gap-2 bg-[#0A2463] text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg mb-6">
                                💄 Transformation Focused Aviation Training
                            </div>
                            <h2 class="text-4xl md:text-5xl font-bold text-[#0A2463] leading-tight mb-6">
                                From Ordinary Student to <span class="text-[#3E92CC]">Aviation Professional</span>
                            </h2>
                            <p class="text-lg text-gray-600 leading-relaxed">
                                We don’t just teach aviation subjects. We transform your confidence, communication,
                                personality and professional appearance to make you airline-ready.
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7">
                            <div
                                class="group relative bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100 hover:border-[#3E92CC] hover:shadow-2xl transition-all duration-500 overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#3E92CC]/0 to-[#3E92CC]/5 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#3E92CC] to-[#dcdde0] flex items-center justify-center text-4xl shadow-xl mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                        🗣️
                                    </div>
                                    <h3 class="text-2xl font-bold text-[#0A2463] mb-4">
                                        Communication Skills
                                    </h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Improve spoken English, confidence, public interaction and professional
                                        communication required in airlines and airports.
                                    </p>
                                </div>
                            </div>
                            <div
                                class="group relative bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100 hover:border-[#3E92CC] hover:shadow-2xl transition-all duration-500 overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#FFD700]/0 to-[#FFD700]/10 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#6886d0] to-[#0A2463] flex items-center justify-center text-4xl shadow-xl mb-6 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500">
                                        ✨
                                    </div>
                                    <h3 class="text-2xl font-bold text-[#0A2463] mb-4">
                                        Grooming Excellence
                                    </h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Learn professional grooming, presentation, posture and airline-standard
                                        appearance training.
                                    </p>
                                </div>
                            </div>
                            <div
                                class="group relative bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100 hover:border-[#3E92CC] hover:shadow-2xl transition-all duration-500 overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#0A2463]/0 to-[#0A2463]/5 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#0A2463] to-[#123b91] flex items-center justify-center text-4xl shadow-xl mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                        🎯
                                    </div>
                                    <h3
                                        class="text-2xl font-bold text-white mb-4 bg-[#0A2463] inline-block px-3 py-1 rounded-xl">
                                        Interview Preparation
                                    </h3>
                                    <p class="text-gray-600 leading-relaxed mt-4">
                                        Get airline interview guidance, HR preparation and mock interview sessions from
                                        experts.
                                    </p>
                                </div>
                            </div>
                            <div
                                class="group relative bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100 hover:border-[#3E92CC] hover:shadow-2xl transition-all duration-500 overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#3E92CC]/0 to-[#FFD700]/10 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#3E92CC] to-[#dcdde0] flex items-center justify-center text-4xl shadow-xl mb-6 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500">
                                        💼
                                    </div>
                                    <h3 class="text-2xl font-bold text-[#0A2463] mb-4">
                                        Career Readiness
                                    </h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Become job-ready with industry exposure, practical aviation training and
                                        placement assistance.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Your career aviation -->
                <section class="relative py-10 bg-white overflow-hidden">
                    <div class="absolute inset-0 opacity-[0.03]"
                        style="background-image:radial-gradient(#0A2463 1px,transparent 1px);background-size:20px 20px;">
                    </div>
                    <div class="container mx-auto px-4 relative z-10">
                        <div
                            class="bg-gradient-to-r from-[#0A2463] via-[#123b91] to-[#0A2463] rounded-[2rem] p-6 md:p-10 shadow-2xl overflow-hidden relative">
                            <div class="absolute top-0 right-0 w-72 h-72 bg-[#FFD700]/10 rounded-full blur-3xl"></div>
                            <div class="absolute bottom-0 left-0 w-72 h-72 bg-[#3E92CC]/20 rounded-full blur-3xl"></div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center relative z-10">
                                <div>
                                    <div
                                        class="inline-flex items-center gap-2 bg-[#FFD700] text-[#0A2463] px-4 py-2 rounded-full text-sm font-bold shadow-lg mb-5 animate-pulse">
                                        🎯 Career-Focused Aviation Training
                                    </div>
                                    <h2 class="text-4xl md:text-5xl font-bold text-white leading-tight mb-5">
                                        Your Aviation Career Starts <span class="text-[#FFD700]">Right Here</span>
                                    </h2>
                                    <p class="text-lg text-white/80 leading-relaxed mb-8">
                                        Whether you dream of becoming Cabin Crew, Ground Staff, Airport Operations
                                        Executive or entering the Travel Industry — we help you become industry-ready
                                        with practical aviation training.
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                                        <div
                                            class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-5 hover:scale-105 transition-all duration-300">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-[#FFD700] flex items-center justify-center text-2xl mb-4 shadow-lg">
                                                🎓</div>
                                            <h3 class="text-white font-bold text-lg mb-2">12th Pass / Graduates</h3>
                                            <p class="text-white/70 text-sm">Perfect career opportunity for students
                                                looking for a fast-growing industry.</p>
                                        </div>
                                        <div
                                            class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-5 hover:scale-105 transition-all duration-300">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-[#FFD700] flex items-center justify-center text-2xl mb-4 shadow-lg">
                                                ✈️</div>
                                            <h3 class="text-white font-bold text-lg mb-2">Cabin Crew & Airport Jobs</h3>
                                            <p class="text-white/70 text-sm">Build a professional aviation career with
                                                grooming and interview preparation.</p>
                                        </div>
                                        <div
                                            class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-5 hover:scale-105 transition-all duration-300">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-[#FFD700] flex items-center justify-center text-2xl mb-4 shadow-lg">
                                                💼</div>
                                            <h3 class="text-white font-bold text-lg mb-2">Placement Assistance</h3>
                                            <p class="text-white/70 text-sm">Get guidance, interview support and career
                                                assistance from experts.</p>
                                        </div>
                                        <div
                                            class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-5 hover:scale-105 transition-all duration-300">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-[#FFD700] flex items-center justify-center text-2xl mb-4 shadow-lg">
                                                🌍</div>
                                            <h3 class="text-white font-bold text-lg mb-2">Global Career Exposure</h3>
                                            <p class="text-white/70 text-sm">Opportunities across airlines, airports and
                                                international travel industry.</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <button type="button" data-title="Get Free Career Guidance"
                                            class="openLeadPopup inline-flex items-center justify-center bg-[#FFD700] hover:bg-[#ffe14a] text-[#0A2463] font-bold px-7 py-4 rounded-xl shadow-[0_10px_40px_rgba(255,215,0,0.35)] hover:scale-105 transition-all duration-300">
                                            👉 Get Free Career Guidance
                                        </button>
                                        <a href="<?php echo $enroll_link; ?>" target="_blank"
                                            class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md text-white font-semibold px-7 py-4 rounded-xl transition-all duration-300">
                                            💬 WhatsApp Us
                                        </a>
                                    </div>
                                </div>
                                <div class="relative">
                                    <div
                                        class="bg-white rounded-[2rem] p-7 shadow-2xl border border-gray-100 relative overflow-hidden">
                                        <div
                                            class="absolute top-0 right-0 w-40 h-40 bg-[#FFD700]/10 rounded-full blur-3xl">
                                        </div>
                                        <div class="relative z-10">
                                            <div
                                                class="inline-flex items-center gap-2 bg-red-100 text-red-600 px-4 py-2 rounded-full text-sm font-bold mb-5">
                                                ⏳ Admissions Closing Soon
                                            </div>
                                            <h3 class="text-3xl font-bold text-[#0A2463] leading-tight mb-3">
                                                Confused About Your Career?
                                            </h3>
                                            <p class="text-gray-600 mb-6">
                                                Talk to Our Expert Counselors & Get a Clear Aviation Roadmap.
                                            </p>
                                            <div class="space-y-4">
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-[#0A2463] flex items-center justify-center text-white flex-shrink-0">
                                                        ✔️</div>
                                                    <div>
                                                        <h4 class="font-bold text-[#0A2463]">Experienced Aviation
                                                            Trainers</h4>
                                                        <p class="text-sm text-gray-600">Learn from industry
                                                            professionals with real-time exposure.</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-[#0A2463] flex items-center justify-center text-white flex-shrink-0">
                                                        ✔️</div>
                                                    <div>
                                                        <h4 class="font-bold text-[#0A2463]">Interview & Grooming
                                                            Support</h4>
                                                        <p class="text-sm text-gray-600">Build confidence with
                                                            personality development & interview preparation.</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-[#0A2463] flex items-center justify-center text-white flex-shrink-0">
                                                        ✔️</div>
                                                    <div>
                                                        <h4 class="font-bold text-[#0A2463]">Practical Career Guidance
                                                        </h4>
                                                        <p class="text-sm text-gray-600">Get the right aviation course
                                                            guidance based on your goals.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" data-title="Book Free Counseling"
                                                class="openLeadPopup w-full mt-8 inline-flex items-center justify-center bg-[#0A2463] hover:bg-[#123b91] text-white font-bold py-4 rounded-xl shadow-xl transition-all duration-300 hover:scale-[1.02]">
                                                📞 Book Free Counseling
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- About section -->
                <section id="about-section" class="py-20 bg-gradient-to-b from-gray-50 to-white">
                    <div class="container mx-auto px-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                            <div class="relative">
                                <div class="relative rounded-2xl overflow-hidden shadow-2xl"><img
                                        alt="Modern airport terminal" class="w-full h-[500px] object-cover"
                                        src="https://images.unsplash.com/photo-1758669246636-17a5f6d972ec">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#0A2463]/60 to-transparent">
                                    </div>
                                </div>
                                <div class="absolute -bottom-8 -right-8 bg-[#FFD700] p-6 rounded-xl shadow-xl">
                                    <div class="text-center">
                                        <div class="text-4xl font-bold text-[#0A2463]">5000+</div>
                                        <div class="text-sm font-semibold text-[#0A2463]">Students Trained</div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div
                                    class="inline-flex items-center gap-2 bg-[#FFD700] text-[#0A2463] px-5 py-2 rounded-full text-sm font-bold shadow-lg mb-5">
                                    🏆 Trusted Aviation Training Institute
                                </div>
                                <h2 class="text-4xl md:text-5xl font-bold text-[#0A2463] mb-6">About First Fly Aviation
                                    Academy</h2>
                                <p class="text-lg text-gray-600 mb-8">India's premier aviation training institute,
                                    dedicated to transforming aspirations into successful aviation careers. With
                                    industry-leading trainers and comprehensive curriculum, we've helped thousands
                                    launch their dream careers.</p>
                                <div class="space-y-4">
                                    <div
                                        class="flex gap-4 items-start p-4 rounded-lg hover:bg-white hover:shadow-md transition-all duration-300">
                                        <div
                                            class="w-12 h-12 bg-[#3E92CC] rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-users w-6 h-6 text-white" aria-hidden="true">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-[#0A2463] mb-2">Real-Time Industry
                                                Trainers</h3>
                                            <p class="text-gray-600">Learn from active aviation professionals with 10+
                                                years experience</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex gap-4 items-start p-4 rounded-lg hover:bg-white hover:shadow-md transition-all duration-300">
                                        <div
                                            class="w-12 h-12 bg-[#3E92CC] rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-briefcase w-6 h-6 text-white" aria-hidden="true">
                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                                <rect width="20" height="14" x="2" y="6" rx="2"></rect>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-[#0A2463] mb-2">Practical Training</h3>
                                            <p class="text-gray-600">Hands-on training with real equipment and airport
                                                simulations</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex gap-4 items-start p-4 rounded-lg hover:bg-white hover:shadow-md transition-all duration-300">
                                        <div
                                            class="w-12 h-12 bg-[#3E92CC] rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-target w-6 h-6 text-white" aria-hidden="true">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <circle cx="12" cy="12" r="6"></circle>
                                                <circle cx="12" cy="12" r="2"></circle>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-[#0A2463] mb-2">Placement Guidance</h3>
                                            <p class="text-gray-600">Interview preparation, resume building, and direct
                                                company referrals</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex gap-4 items-start p-4 rounded-lg hover:bg-white hover:shadow-md transition-all duration-300">
                                        <div
                                            class="w-12 h-12 bg-[#3E92CC] rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-book-open w-6 h-6 text-white" aria-hidden="true">
                                                <path d="M12 7v14"></path>
                                                <path
                                                    d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-[#0A2463] mb-2">Industry-Recognized
                                                Curriculum</h3>
                                            <p class="text-gray-600">Training modules aligned with IATA and aviation
                                                industry standards</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Industry-Oriented Aviation Courses -->
                <section class="py-20 bg-[#0A2463] relative overflow-hidden">
                    <div class="absolute inset-0 opacity-[0.04]"
                        style="background-image:radial-gradient(#ffffff 1px,transparent 1px);background-size:22px 22px;">
                    </div>
                    <div class="absolute top-0 left-0 w-96 h-96 bg-[#FFD700]/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#3E92CC]/20 rounded-full blur-3xl"></div>
                    <div class="container mx-auto px-4 relative z-10">
                        <div class="text-center max-w-4xl mx-auto mb-16">
                            <div
                                class="inline-flex items-center gap-2 bg-[#FFD700] text-[#0A2463] px-5 py-2 rounded-full text-sm font-bold shadow-xl mb-6 animate-pulse">
                                💼 Industry-Oriented Aviation Courses
                            </div>
                            <h2 class="text-4xl md:text-5xl font-bold text-white leading-tight mb-6">
                                Job-Oriented Aviation Courses Designed by <span class="text-[#FFD700]">Industry
                                    Experts</span>
                            </h2>
                            <p class="text-lg text-white/75 leading-relaxed">
                                Not Just Training — We Prepare You for Real Airline Careers with practical exposure,
                                interview preparation and placement-focused learning.
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div
                                class="group bg-white/10 backdrop-blur-md border border-white/10 rounded-[2rem] p-8 hover:bg-white/15 hover:-translate-y-2 transition-all duration-500 shadow-xl relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#FFD700]/0 to-[#FFD700]/10 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-6">
                                        <div
                                            class="w-16 h-16 rounded-2xl bg-[#FFD700] flex items-center justify-center text-3xl shadow-lg group-hover:rotate-6 transition-all duration-500">
                                            🛫
                                        </div>
                                        <div class="bg-white/10 text-white text-sm font-bold px-4 py-2 rounded-full">
                                            Module 01
                                        </div>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-4">
                                        Airport Ground Handling
                                    </h3>
                                    <p class="text-white/75 leading-relaxed mb-6">
                                        Learn baggage handling, aircraft servicing, cargo operations and airport ground
                                        procedures with practical exposure.
                                    </p>
                                    <div class="flex items-center gap-2 text-[#FFD700] text-sm font-semibold">
                                        ✔️ Practical Industry Training
                                    </div>
                                </div>
                            </div>
                            <div
                                class="group bg-white/10 backdrop-blur-md border border-white/10 rounded-[2rem] p-8 hover:bg-white/15 hover:-translate-y-2 transition-all duration-500 shadow-xl relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#3E92CC]/0 to-[#3E92CC]/10 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-6">
                                        <div
                                            class="w-16 h-16 rounded-2xl bg-[#acc4d5] flex items-center justify-center text-3xl shadow-lg group-hover:-rotate-6 transition-all duration-500">
                                            🎟️
                                        </div>
                                        <div class="bg-white/10 text-white text-sm font-bold px-4 py-2 rounded-full">
                                            Module 02
                                        </div>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-4">
                                        Passenger Handling
                                    </h3>
                                    <p class="text-white/75 leading-relaxed mb-6">
                                        Master check-in procedures, boarding operations and customer service standards
                                        followed in leading airlines.
                                    </p>
                                    <div class="flex items-center gap-2 text-[#FFD700] text-sm font-semibold">
                                        ✔️ Airline Customer Service Skills
                                    </div>
                                </div>
                            </div>
                            <div
                                class="group bg-white/10 backdrop-blur-md border border-white/10 rounded-[2rem] p-8 hover:bg-white/15 hover:-translate-y-2 transition-all duration-500 shadow-xl relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#FFD700]/0 to-[#3E92CC]/10 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-6">
                                        <div
                                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#FFD700] to-[#ffb700] flex items-center justify-center text-3xl shadow-lg group-hover:rotate-6 transition-all duration-500">
                                            🛡️
                                        </div>
                                        <div class="bg-white/10 text-white text-sm font-bold px-4 py-2 rounded-full">
                                            Module 03
                                        </div>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-4">
                                        Ramp Safety
                                    </h3>
                                    <p class="text-white/75 leading-relaxed mb-6">
                                        Understand airport safety protocols, emergency handling and operational
                                        procedures used in aviation environments.
                                    </p>
                                    <div class="flex items-center gap-2 text-[#FFD700] text-sm font-semibold">
                                        ✔️ Real-Time Safety Procedures
                                    </div>
                                </div>
                            </div>
                            <div
                                class="group bg-white/10 backdrop-blur-md border border-white/10 rounded-[2rem] p-8 hover:bg-white/15 hover:-translate-y-2 transition-all duration-500 shadow-xl relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#ffffff]/0 to-[#ffffff]/5 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-6">
                                        <div
                                            class="w-16 h-16 rounded-2xl bg-white text-[#0A2463] flex items-center justify-center text-3xl shadow-lg group-hover:-rotate-6 transition-all duration-500">
                                            🗣️
                                        </div>
                                        <div class="bg-white/10 text-white text-sm font-bold px-4 py-2 rounded-full">
                                            Module 04
                                        </div>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-4">
                                        Aviation Communication
                                    </h3>
                                    <p class="text-white/75 leading-relaxed mb-6">
                                        Develop professional communication, aviation terminology and passenger
                                        interaction confidence.
                                    </p>
                                    <div class="flex items-center gap-2 text-[#FFD700] text-sm font-semibold">
                                        ✔️ Professional Communication Skills
                                    </div>
                                </div>
                            </div>
                            <div
                                class="group bg-white/10 backdrop-blur-md border border-white/10 rounded-[2rem] p-8 hover:bg-white/15 hover:-translate-y-2 transition-all duration-500 shadow-xl relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#FFD700]/0 to-[#FFD700]/10 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-6">
                                        <div
                                            class="w-16 h-16 rounded-2xl bg-[#fdebcf] flex items-center justify-center text-3xl shadow-lg group-hover:rotate-6 transition-all duration-500">
                                            ✨
                                        </div>
                                        <div class="bg-white/10 text-white text-sm font-bold px-4 py-2 rounded-full">
                                            Module 05
                                        </div>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-4">
                                        Grooming & Interview Prep
                                    </h3>
                                    <p class="text-white/75 leading-relaxed mb-6">
                                        Build airline-level personality, grooming standards, interview confidence and
                                        presentation skills.
                                    </p>
                                    <div class="flex items-center gap-2 text-[#FFD700] text-sm font-semibold">
                                        ✔️ Personality Development Included
                                    </div>
                                </div>
                            </div>
                            <div
                                class="group bg-gradient-to-br from-[#FFD700] to-[#ffb700] rounded-[2rem] p-8 hover:-translate-y-2 transition-all duration-500 shadow-2xl relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-40 h-40 bg-white/20 rounded-full blur-3xl"></div>
                                <div class="relative z-10">
                                    <div
                                        class="inline-flex items-center gap-2 bg-white text-[#0A2463] px-4 py-2 rounded-full text-sm font-bold shadow-lg mb-6">
                                        🚀 Bonus Career Support
                                    </div>
                                    <h3 class="text-3xl font-bold text-[#0A2463] leading-tight mb-5">
                                        Get Career Guidance & Placement Support
                                    </h3>
                                    <ul class="space-y-4 mb-8">
                                        <li class="flex items-start gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-[#0A2463] text-white flex items-center justify-center flex-shrink-0">
                                                ✔
                                            </div>
                                            <p class="text-[#0A2463] font-medium">
                                                Resume Building Support
                                            </p>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-[#0A2463] text-white flex items-center justify-center flex-shrink-0">
                                                ✔
                                            </div>
                                            <p class="text-[#0A2463] font-medium">
                                                Mock Interviews & Guidance
                                            </p>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-[#0A2463] text-white flex items-center justify-center flex-shrink-0">
                                                ✔
                                            </div>
                                            <p class="text-[#0A2463] font-medium">
                                                Industry Exposure & Career Support
                                            </p>
                                        </li>
                                    </ul>
                                    <button type="button" data-title="Get Free Counseling"
                                        class="openLeadPopup inline-flex items-center justify-center bg-[#0A2463] hover:bg-[#123b91] text-white font-bold px-7 py-4 rounded-xl shadow-xl hover:scale-105 transition-all duration-300 w-full">
                                        📞 Get Free Counseling
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php /*
                <section class="py-20 bg-[#0A2463] relative overflow-hidden">
                    <div class="absolute inset-0 opacity-5">
                        <div class="absolute inset-0"
                            style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);">
                        </div>
                    </div>
                    <div class="container mx-auto px-4 relative z-10">
                        <div class="text-center mb-16">
                            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">What You Will Learn in This
                                Bootcamp</h2>
                            <p class="text-lg text-gray-300 max-w-2xl mx-auto">Comprehensive training modules designed
                                by industry experts to make you job-ready</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                            <div
                                class="rounded-xl text-card-foreground shadow bg-white/10 backdrop-blur-md border-2 border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105 group">
                                <div class="p-6">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-12 h-12 bg-[#FFD700] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:rotate-12 transition-transform duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-package w-6 h-6 text-[#0A2463]" aria-hidden="true">
                                                <path
                                                    d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z">
                                                </path>
                                                <path d="M12 22V12"></path>
                                                <polyline points="3.29 7 12 12 20.71 7"></polyline>
                                                <path d="m7.5 4.27 9 5.15"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-bold text-[#FFD700] mb-2">Module 1</div>
                                            <h3 class="text-xl font-bold text-white mb-3">Airport Ground Handling</h3>
                                            <p class="text-gray-300 text-sm">Baggage handling, cargo operations, and
                                                aircraft servicing procedures</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="rounded-xl text-card-foreground shadow bg-white/10 backdrop-blur-md border-2 border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105 group">
                                <div class="p-6">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-12 h-12 bg-[#FFD700] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:rotate-12 transition-transform duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-user-check w-6 h-6 text-[#0A2463]"
                                                aria-hidden="true">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <polyline points="16 11 18 13 22 9"></polyline>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-bold text-[#FFD700] mb-2">Module 2</div>
                                            <h3 class="text-xl font-bold text-white mb-3">Passenger Handling</h3>
                                            <p class="text-gray-300 text-sm">Check-in procedures, boarding processes,
                                                and customer service excellence</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="rounded-xl text-card-foreground shadow bg-white/10 backdrop-blur-md border-2 border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105 group">
                                <div class="p-6">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-12 h-12 bg-[#FFD700] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:rotate-12 transition-transform duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-shield w-6 h-6 text-[#0A2463]" aria-hidden="true">
                                                <path
                                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-bold text-[#FFD700] mb-2">Module 3</div>
                                            <h3 class="text-xl font-bold text-white mb-3">Ramp Safety</h3>
                                            <p class="text-gray-300 text-sm">Safety protocols, equipment operation, and
                                                emergency procedures</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="rounded-xl text-card-foreground shadow bg-white/10 backdrop-blur-md border-2 border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105 group">
                                <div class="p-6">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-12 h-12 bg-[#FFD700] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:rotate-12 transition-transform duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-message-square w-6 h-6 text-[#0A2463]"
                                                aria-hidden="true">
                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-bold text-[#FFD700] mb-2">Module 4</div>
                                            <h3 class="text-xl font-bold text-white mb-3">Aviation Terminology</h3>
                                            <p class="text-gray-300 text-sm">Industry jargon, airport codes, and
                                                professional communication</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="rounded-xl text-card-foreground shadow bg-white/10 backdrop-blur-md border-2 border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105 group">
                                <div class="p-6">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-12 h-12 bg-[#FFD700] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:rotate-12 transition-transform duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-sparkles w-6 h-6 text-[#0A2463]"
                                                aria-hidden="true">
                                                <path
                                                    d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z">
                                                </path>
                                                <path d="M20 3v4"></path>
                                                <path d="M22 5h-4"></path>
                                                <path d="M4 17v2"></path>
                                                <path d="M5 18H3"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-bold text-[#FFD700] mb-2">Module 5</div>
                                            <h3 class="text-xl font-bold text-white mb-3">Grooming &amp; Interview Prep
                                            </h3>
                                            <p class="text-gray-300 text-sm">Professional appearance, body language, and
                                                interview techniques</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-12 text-center">
                            <p class="text-white/80 text-lg">Plus: Live doubt-clearing sessions, practice assignments,
                                and real-world case studies</p>
                        </div>
                    </div>
                </section>
                */ ?>
                <section id="testimonials-section" class="py-20 bg-gray-50 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-72 h-72 bg-[#3E92CC]/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 right-0 w-72 h-72 bg-[#FFD700]/10 rounded-full blur-3xl"></div>
                    <div class="container mx-auto px-4 relative z-10">
                        <div class="text-center max-w-4xl mx-auto mb-16">
                            <div
                                class="inline-flex items-center gap-2 bg-[#FFD700] text-[#0A2463] px-5 py-2 rounded-full text-sm font-bold shadow-lg mb-5">
                                ❤️ Real Student Success Stories
                            </div>
                            <h2 class="text-4xl md:text-5xl font-bold text-[#0A2463] leading-tight mb-6">
                                Students Who Turned Their <span class="text-[#3E92CC]">Dreams Into Careers</span>
                            </h2>
                            <p class="text-lg text-gray-600 leading-relaxed">
                                From ordinary students to aviation professionals — hear how First Fly Aviation helped
                                students start successful airline careers.
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div
                                class="group bg-white rounded-[2rem] p-8 shadow-xl border border-gray-100 hover:border-[#3E92CC] hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#3E92CC]/0 to-[#3E92CC]/5 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex gap-1 mb-6 text-[#FFD700] text-xl">
                                        ★★★★★
                                    </div>
                                    <p class="text-gray-700 leading-relaxed italic mb-8">
                                        “This academy completely changed my confidence and communication skills. Within
                                        2 months after training, I got placed in IndiGo Airlines.”
                                    </p>
                                    <div class="flex items-center gap-4">
                                        <img alt="Priya Sharma"
                                            class="w-16 h-16 rounded-2xl object-cover border-2 border-[#3E92CC]"
                                            src="assets/images/testimonials/priya-sharma.jpg">
                                        <div>
                                            <div class="font-bold text-[#0A2463] text-lg">
                                                Priya Sharma
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Ground Staff • IndiGo Airlines
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="group bg-gradient-to-br from-[#0A2463] to-[#123b91] rounded-[2rem] p-8 shadow-2xl hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-40 h-40 bg-[#FFD700]/10 rounded-full blur-3xl">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex gap-1 mb-6 text-[#FFD700] text-xl">
                                        ★★★★★
                                    </div>
                                    <p class="text-white/90 leading-relaxed italic mb-8">
                                        “Best decision for my career. I learned airport operations, grooming and
                                        interview preparation. Now I’m earning ₹35,000/month at age 21.”
                                    </p>
                                    <div class="flex items-center gap-4">
                                        <img alt="Rahul Verma"
                                            class="w-16 h-16 rounded-2xl object-cover border-2 border-[#FFD700]"
                                            src="assets/images/testimonials/rahul-verma.jpg">
                                        <div>
                                            <div class="font-bold text-white text-lg">
                                                Rahul Verma
                                            </div>
                                            <div class="text-sm text-white/60">
                                                Ramp Agent • Air India
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="group bg-white rounded-[2rem] p-8 shadow-xl border border-gray-100 hover:border-[#3E92CC] hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-[#FFD700]/0 to-[#FFD700]/10 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex gap-1 mb-6 text-[#FFD700] text-xl">
                                        ★★★★★
                                    </div>
                                    <p class="text-gray-700 leading-relaxed italic mb-8">
                                        “The trainers were extremely supportive. Grooming classes and mock interviews
                                        helped me clear my airline interview confidently.”
                                    </p>
                                    <div class="flex items-center gap-4">
                                        <img alt="Sneha Kapoor"
                                            class="w-16 h-16 rounded-2xl object-cover border-2 border-[#3E92CC]"
                                            src="assets/images/testimonials/sneha-kapoor.jpg">
                                        <div>
                                            <div class="font-bold text-[#0A2463] text-lg">
                                                Sneha Kapoor
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Cabin Crew Aspirant
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-16">
                            <div
                                class="bg-gradient-to-r from-[#0A2463] to-[#123b91] rounded-[2rem] p-8 md:p-10 shadow-2xl overflow-hidden relative">
                                <div class="absolute top-0 right-0 w-72 h-72 bg-[#FFD700]/10 rounded-full blur-3xl">
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center relative z-10">
                                    <div>
                                        <div
                                            class="inline-flex items-center gap-2 bg-[#FFD700] text-[#0A2463] px-4 py-2 rounded-full text-sm font-bold shadow-lg mb-5">
                                            ⚡ Limited Admissions Open
                                        </div>
                                        <h3 class="text-4xl font-bold text-white leading-tight mb-5">
                                            Your Dream Career in Aviation is Just One Decision Away
                                        </h3>
                                        <p class="text-white/75 text-lg leading-relaxed">
                                            Don’t Wait. Start Your Journey with First Fly Aviation Today.
                                        </p>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <button type="button" data-title="Enroll Now"
                                            class="openLeadPopup inline-flex items-center justify-center bg-[#FFD700] hover:bg-[#ffe14a] text-[#0A2463] font-bold px-8 py-5 rounded-xl shadow-[0_10px_40px_rgba(255,215,0,0.35)] hover:scale-105 transition-all duration-300">
                                            👉 Enroll Now
                                        </button>
                                        <a href="<?php echo $enroll_link; ?>" target="_blank"
                                            class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md text-white font-semibold px-8 py-5 rounded-xl transition-all duration-300">
                                            💬 WhatsApp Us
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="py-20 bg-gradient-to-b from-white to-gray-50">
                    <div class="container mx-auto px-4">
                        <div class="text-center mb-12">
                            <div
                                class="inline-flex items-center gap-2 bg-[#FFD700] text-[#0A2463] px-4 py-2 rounded-full font-semibold mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-gift w-5 h-5" aria-hidden="true">
                                    <rect x="3" y="8" width="18" height="4" rx="1"></rect>
                                    <path d="M12 8v13"></path>
                                    <path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"></path>
                                    <path
                                        d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5">
                                    </path>
                                </svg>Exclusive Bonuses Worth ₹2,000
                            </div>
                            <h2 class="text-4xl md:text-5xl font-bold text-[#0A2463] mb-4">Get These Bonuses FREE!</h2>
                            <p class="text-lg text-gray-600">Everything you need to kickstart your aviation career</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                            <div
                                class="relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border-2 border-gray-100 hover:border-[#3E92CC] group">
                                <div
                                    class="absolute -top-4 -right-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                    FREE</div>
                                <div class="text-center">
                                    <div
                                        class="w-20 h-20 bg-gradient-to-br from-[#3E92CC] to-[#0A2463] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-file-text w-10 h-10 text-white" aria-hidden="true">
                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                            <path d="M10 9H8"></path>
                                            <path d="M16 13H8"></path>
                                            <path d="M16 17H8"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-[#0A2463] mb-3">Free Resume Template</h3>
                                    <p class="text-gray-600">Aviation-industry specific resume format that gets noticed
                                    </p>
                                </div>
                            </div>
                            <div
                                class="relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border-2 border-gray-100 hover:border-[#3E92CC] group">
                                <div
                                    class="absolute -top-4 -right-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                    FREE</div>
                                <div class="text-center">
                                    <div
                                        class="w-20 h-20 bg-gradient-to-br from-[#3E92CC] to-[#0A2463] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-circle-help w-10 h-10 text-white" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                            <path d="M12 17h.01"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-[#0A2463] mb-3">Interview Questions PDF</h3>
                                    <p class="text-gray-600">100+ commonly asked questions with model answers</p>
                                </div>
                            </div>
                            <div
                                class="relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border-2 border-gray-100 hover:border-[#3E92CC] group">
                                <div
                                    class="absolute -top-4 -right-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                    FREE</div>
                                <div class="text-center">
                                    <div
                                        class="w-20 h-20 bg-gradient-to-br from-[#3E92CC] to-[#0A2463] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-award w-10 h-10 text-white" aria-hidden="true">
                                            <path
                                                d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526">
                                            </path>
                                            <circle cx="12" cy="8" r="6"></circle>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-[#0A2463] mb-3">Certificate of Completion</h3>
                                    <p class="text-gray-600">Industry-recognized certificate to boost your profile</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="py-20 bg-gradient-to-b from-gray-50 to-white">
                    <div class="container mx-auto px-4">
                        <div class="text-center mb-12">
                            <div
                                class="inline-flex items-center rounded-md border transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent shadow hover:bg-primary/80 bg-red-500 text-white px-4 py-2 text-sm font-bold mb-4">
                                LIMITED TIME OFFER</div>
                            <h2 class="text-4xl md:text-5xl font-bold text-[#0A2463] mb-4">Invest in Your Future Today
                            </h2>
                            <p class="text-lg text-gray-600">Get complete aviation training at an unbelievable price</p>
                        </div>
                        <div class="max-w-4xl mx-auto">
                            <div
                                class="rounded-xl bg-card text-card-foreground border-4 border-[#FFD700] shadow-2xl overflow-hidden">
                                <div class="p-0">
                                    <div class="grid grid-cols-1 md:grid-cols-5">
                                        <!-- <div
                                            class="md:col-span-2 bg-gradient-to-br from-[#0A2463] to-[#3E92CC] p-8 text-white flex flex-col justify-center">
                                            <div class="mb-6">
                                                <div class="text-sm uppercase tracking-wider mb-2">Regular Price</div>
                                                <div class="text-3xl line-through opacity-60">₹4999</div>
                                            </div>
                                            <div class="mb-6">
                                                <div class="text-sm uppercase tracking-wider mb-2">Today's Price</div>
                                                <div class="flex items-baseline gap-2"><span
                                                        class="text-6xl font-bold text-[#FFD700]">₹3999</span><span
                                                        class="text-xl">only</span></div>
                                            </div>
                                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 mb-6">
                                                <div class="flex items-center gap-2 text-[#FFD700] font-bold"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-trending-up w-5 h-5" aria-hidden="true">
                                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                        <polyline points="16 7 22 7 22 13"></polyline>
                                                    </svg>You Save ₹1000!</div>
                                            </div>
                                            <div
                                                class="bg-red-500 text-white px-4 py-3 rounded-lg text-center font-bold animate-pulse">
                                                Only 25 seats left!</div>
                                        </div> -->
                                        <div
                                            class="md:col-span-2 bg-gradient-to-br from-[#0A2463] to-[#3E92CC] text-white flex flex-col justify-center relative">
                                            <img alt="Aircraft on runway" class="w-full h-full object-cover"
                                                src="assets/images/airhostess.jpg">
                                            <div class="absolute top-4 left-4 bg-red-500 text-white px-4 py-3 rounded-lg text-center font-bold animate-pulse whitespace-nowrap">
                                                Only 25 seats left!</div>
                                        </div>
                                        <div class="md:col-span-3 p-8">
                                            <h3 class="text-2xl font-bold text-[#0A2463] mb-6">What's Included:</h3>
                                            <div class="space-y-4 mb-8">
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-check w-4 h-4 text-white"
                                                            aria-hidden="true">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                    </div><span class="text-gray-700">5 Core Training
                                                        Modules</span>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-check w-4 h-4 text-white"
                                                            aria-hidden="true">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                    </div><span class="text-gray-700">Live Industry Expert
                                                        Sessions</span>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-check w-4 h-4 text-white"
                                                            aria-hidden="true">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                    </div><span class="text-gray-700">Practical Training
                                                        Materials</span>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-check w-4 h-4 text-white"
                                                            aria-hidden="true">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                    </div><span class="text-gray-700">Interview Preparation
                                                        Kit</span>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-check w-4 h-4 text-white"
                                                            aria-hidden="true">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                    </div><span class="text-gray-700">Certificate of
                                                        Completion</span>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-check w-4 h-4 text-white"
                                                            aria-hidden="true">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                    </div><span class="text-gray-700">Lifetime Course
                                                        Access</span>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-check w-4 h-4 text-white"
                                                            aria-hidden="true">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                    </div><span class="text-gray-700">Placement
                                                        Assistance</span>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-check w-4 h-4 text-white"
                                                            aria-hidden="true">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                    </div><span class="text-gray-700">Bonus Resources Worth
                                                        ₹2000</span>
                                                </div>
                                            </div><button type="button" data-title="Enroll Now"
                                                class="openLeadPopup inline-flex items-center justify-center gap-2 whitespace-nowrap focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 px-8 w-full bg-[#FFD700] hover:bg-[#FFD700]/90 text-[#0A2463] font-bold text-lg py-6 rounded-lg shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">Enroll
                                                Now<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-arrow-right ml-2 w-5 h-5" aria-hidden="true">
                                                    <path d="M5 12h14"></path>
                                                    <path d="m12 5 7 7-7 7"></path>
                                                </svg></button>
                                            <p class="text-center text-sm text-gray-500 mt-4">Secure payment • Money-back guarantee</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap justify-center items-center gap-6 mt-8 text-sm text-gray-600">
                                <div class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-check w-5 h-5 text-green-500" aria-hidden="true">
                                        <path d="M20 6 9 17l-5-5"></path>
                                    </svg><span>100% Secure Payment</span></div>
                                <div class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-check w-5 h-5 text-green-500" aria-hidden="true">
                                        <path d="M20 6 9 17l-5-5"></path>
                                    </svg><span>Industry Certified</span></div>
                                <div class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-check w-5 h-5 text-green-500" aria-hidden="true">
                                        <path d="M20 6 9 17l-5-5"></path>
                                    </svg><span>Money Back Guarantee</span></div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- FAQ Section -->
                <section id="faq-section" class="py-20 bg-white">
                    <div class="container mx-auto px-4 max-w-5xl">
                        <div class="text-center mb-14">
                            <h2 class="text-4xl md:text-5xl font-bold text-[#0A2463] mb-4">
                                Frequently Asked Questions
                            </h2>
                            <p class="text-gray-500 text-lg">
                                Get answers to the most commonly asked questions
                            </p>
                        </div>
                        <div class="space-y-4">
                            <!-- FAQ ITEM -->
                            <div
                                class="faq-item bg-gray-50 border-2 hover:border-[#3E92CC] rounded-2xl overflow-hidden transition-all duration-300">
                                <button
                                    class="faq-btn w-full flex items-center justify-between px-8 py-4 text-left group">
                                    <span
                                        class="text-[#0A2463] hover:text-[#3E92CC] text-lg font-semibold group-hover:underline">
                                        Is the training available online, offline or hybrid?
                                    </span>
                                    <svg class="faq-icon w-6 h-6 text-[#3E92CC] transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </button>
                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-500">
                                    <div class="px-8 pb-8 text-gray-600 text-lg leading-8">
                                        The training is available in online, offline and hybrid modes, allowing students to choose the learning format that best suits their convenience and location.
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ ITEM -->
                            <div
                                class="faq-item bg-gray-50 border-2 hover:border-[#3E92CC] rounded-2xl overflow-hidden transition-all duration-300">
                                <button
                                    class="faq-btn w-full flex items-center justify-between px-8 py-4 text-left group">
                                    <span
                                        class="text-[#0A2463] hover:text-[#3E92CC] text-lg font-semibold group-hover:underline">
                                        Will I get a certificate after completion?
                                    </span>
                                    <svg class="faq-icon w-6 h-6 text-[#3E92CC] transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </button>
                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-500">
                                    <div class="px-8 pb-8 text-gray-600 text-lg leading-8">
                                        Yes, every student who successfully completes the course
                                        will receive a certificate of completion from First Fly Aviation Academy.
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ ITEM -->
                            <div
                                class="faq-item bg-gray-50 border-2 hover:border-[#3E92CC] rounded-2xl overflow-hidden transition-all duration-300">
                                <button
                                    class="faq-btn w-full flex items-center justify-between px-8 py-4 text-left group">
                                    <span
                                        class="text-[#0A2463] hover:text-[#3E92CC] text-lg font-semibold group-hover:underline">
                                        Is placement assistance really provided?
                                    </span>
                                    <svg class="faq-icon w-6 h-6 text-[#3E92CC] transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </button>
                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-500">
                                    <div class="px-8 pb-8 text-gray-600 text-lg leading-8">
                                        Yes, students receive interview preparation support,
                                        resume guidance, and job assistance to help them prepare for aviation careers.
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ ITEM -->
                            <div
                                class="faq-item bg-gray-50 border-2 hover:border-[#3E92CC] rounded-2xl overflow-hidden transition-all duration-300">
                                <button
                                    class="faq-btn w-full flex items-center justify-between px-8 py-4 text-left group">
                                    <span
                                        class="text-[#0A2463] hover:text-[#3E92CC] text-lg font-semibold group-hover:underline">
                                        What is the course duration?
                                    </span>
                                    <svg class="faq-icon w-6 h-6 text-[#3E92CC] transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </button>
                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-500">
                                    <div class="px-8 pb-8 text-gray-600 text-lg leading-8">
                                        The course is designed as a fast-track aviation training program
                                        that can be completed within a few weeks.
                                    </div>
                                </div>
                            </div>
                            <div class="faq-item bg-gray-50 border-2 hover:border-[#3E92CC] rounded-2xl overflow-hidden transition-all duration-300">
                                <button class="faq-btn w-full flex items-center justify-between px-8 py-4 text-left group">
                                    <span class="text-[#0A2463] hover:text-[#3E92CC] text-lg font-semibold group-hover:underline">
                                        Can 12th pass students join this aviation course?
                                    </span>

                                    <svg class="faq-icon w-6 h-6 text-[#3E92CC] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-500">
                                    <div class="px-8 pb-8 text-gray-600 text-lg leading-8">
                                        Yes, students who completed 12th standard can join and start their aviation career journey.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item bg-gray-50 border-2 hover:border-[#3E92CC] rounded-2xl overflow-hidden transition-all duration-300">
                                <button class="faq-btn w-full flex items-center justify-between px-8 py-4 text-left group">
                                    <span class="text-[#0A2463] hover:text-[#3E92CC] text-lg font-semibold group-hover:underline">
                                        Do I need previous aviation experience to join?
                                    </span>

                                    <svg class="faq-icon w-6 h-6 text-[#3E92CC] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-500">
                                    <div class="px-8 pb-8 text-gray-600 text-lg leading-8">
                                        No prior aviation experience is required. Our trainers will guide you from basics to professional airline-level training.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item bg-gray-50 border-2 hover:border-[#3E92CC] rounded-2xl overflow-hidden transition-all duration-300">
                                <button class="faq-btn w-full flex items-center justify-between px-8 py-4 text-left group">
                                    <span class="text-[#0A2463] hover:text-[#3E92CC] text-lg font-semibold group-hover:underline">
                                        What kind of jobs can I apply for after this course?
                                    </span>

                                    <svg class="faq-icon w-6 h-6 text-[#3E92CC] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-500">
                                    <div class="px-8 pb-8 text-gray-600 text-lg leading-8">
                                        Students can apply for Cabin Crew, Ground Staff, Airport Operations, Customer Service and Travel Industry roles.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item bg-gray-50 border-2 hover:border-[#3E92CC] rounded-2xl overflow-hidden transition-all duration-300">
                                <button class="faq-btn w-full flex items-center justify-between px-8 py-4 text-left group">
                                    <span class="text-[#0A2463] hover:text-[#3E92CC] text-lg font-semibold group-hover:underline">
                                        Will grooming and communication training be included?
                                    </span>

                                    <svg class="faq-icon w-6 h-6 text-[#3E92CC] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-500">
                                    <div class="px-8 pb-8 text-gray-600 text-lg leading-8">
                                        Yes, students receive personality development, grooming, spoken English and interview preparation sessions.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item bg-gray-50 border-2 hover:border-[#3E92CC] rounded-2xl overflow-hidden transition-all duration-300">
                                <button class="faq-btn w-full flex items-center justify-between px-8 py-4 text-left group">
                                    <span class="text-[#0A2463] hover:text-[#3E92CC] text-lg font-semibold group-hover:underline">
                                        Why should I choose the combo course?
                                    </span>

                                    <svg class="faq-icon w-6 h-6 text-[#3E92CC] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-500">
                                    <div class="px-8 pb-8 text-gray-600 text-lg leading-8">
                                        The combo course helps students gain dual aviation skills, increasing job opportunities in both airline and airport sectors.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Don't Miss Your Takeoff -->
                <section
                    class="py-20 bg-gradient-to-r from-[#0A2463] via-[#3E92CC] to-[#0A2463] relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-10 left-10 w-32 h-32 border-4 border-white rounded-full animate-pulse">
                        </div>
                        <div class="absolute bottom-10 right-10 w-40 h-40 border-4 border-white rounded-full animate-pulse"
                            style="animation-delay: 1s;"></div>
                    </div>
                    <div class="container mx-auto px-4 relative z-10">
                        <div class="max-w-4xl mx-auto text-center">
                            <div
                                class="w-20 h-20 bg-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-plane w-10 h-10 text-[#0A2463]"
                                    aria-hidden="true">
                                    <path
                                        d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-4xl md:text-6xl font-bold text-white mb-6">Don't Miss Your Takeoff!</h2>
                            <p class="text-xl md:text-2xl text-gray-200 mb-8">Your aviation career is just one click
                                away. Join 5000+ students who trusted First Fly and are now working in top airports
                                across India.</p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8"><button type="button" data-title="Enroll Now"
                                    class="openLeadPopup inline-flex items-center justify-center gap-2 whitespace-nowrap focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 bg-[#FFD700] hover:bg-[#FFD700]/90 text-[#0A2463] font-bold text-xl px-10 py-7 rounded-lg shadow-2xl hover:shadow-3xl transform hover:scale-110 transition-all duration-300">Enroll
                                    Now<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-arrow-right ml-2 w-6 h-6" aria-hidden="true">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg></button></div>
                            <div
                                class="inline-block bg-red-500 text-white px-6 py-3 rounded-full font-bold animate-pulse">
                                ⚠️ Only 15 seats remaining at this price!</div>
                            <div class="mt-10 flex flex-wrap justify-center gap-8 text-white/80">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-[#FFD700]">48 Hours</div>
                                    <div class="text-sm">Offer Valid</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-[#FFD700]">100%</div>
                                    <div class="text-sm">Money Back</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="bg-[#0A2463] text-white">
                    <div class="container mx-auto px-4 py-12">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <div class="md:col-span-2">
                                <div class="flex items-center gap-2 mb-4">
                                    <img src="assets/images/logo.png" class="bg-white px-5 py-2 rounded-lg" alt="First Fly Aviation Logo">
                                </div>
                                <p class="text-gray-300 mb-6 max-w-md">India's premier aviation training institute.
                                    Transforming aspirations into successful aviation careers since 2015.</p>
                                <div class="flex gap-4">
                                    <a href="https://www.facebook.com/firstflyaviationacademy/" target="_blank"
                                        class="w-10 h-10 bg-white/10 hover:bg-[#FFD700] rounded-lg flex items-center justify-center transition-colors duration-300"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-facebook w-5 h-5" aria-hidden="true">
                                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z">
                                            </path>
                                        </svg></a>
                                    <a href="https://instagram.com/firstfly_aviation_chennai?igshid=YmMyMTA2M2Y=" target="_blank"
                                        class="w-10 h-10 bg-white/10 hover:bg-[#FFD700] rounded-lg flex items-center justify-center transition-colors duration-300"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-instagram w-5 h-5" aria-hidden="true">
                                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                                        </svg></a>
                                    <a href="https://www.linkedin.com/in/firstflyaviation" target="_blank"
                                        class="w-10 h-10 bg-white/10 hover:bg-[#FFD700] rounded-lg flex items-center justify-center transition-colors duration-300"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-linkedin w-5 h-5" aria-hidden="true">
                                            <path
                                                d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                                            </path>
                                            <rect width="4" height="12" x="2" y="9"></rect>
                                            <circle cx="4" cy="4" r="2"></circle>
                                        </svg></a>
                                    <a href="https://youtube.com/@firstflyaviationacademy7923" target="_blank"
                                        class="w-10 h-10 bg-white/10 hover:bg-[#FFD700] rounded-lg flex items-center justify-center transition-colors duration-300"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-youtube w-5 h-5" aria-hidden="true">
                                            <path
                                                d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17">
                                            </path>
                                            <path d="m10 15 5-3-5-3z"></path>
                                        </svg></a></div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                                <ul class="space-y-2">
                                    <li><a href="#about-section"
                                            class="text-gray-300 hover:text-[#FFD700] transition-colors duration-300">About
                                            Us</a></li>
                                    <li><a href="#combo-course-section"
                                            class="text-gray-300 hover:text-[#FFD700] transition-colors duration-300">Combo Course</a>
                                    </li>
                                    <li><a href="#testimonials-section"
                                            class="text-gray-300 hover:text-[#FFD700] transition-colors duration-300">Reviews</a>
                                    </li>
                                    <li><a href="#faq-section"
                                            class="text-gray-300 hover:text-[#FFD700] transition-colors duration-300">FAQ</a>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold mb-4">Contact Us</h3>
                                <ul class="space-y-3">
                                    <li class="flex items-start gap-3"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-phone w-5 h-5 text-[#FFD700] flex-shrink-0 mt-0.5"
                                            aria-hidden="true">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                            </path>
                                        </svg><a href="tel:+917550289600" class="text-gray-300">+91-75502 89600</a></li>
                                    <li class="flex items-start gap-3"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-mail w-5 h-5 text-[#FFD700] flex-shrink-0 mt-0.5"
                                            aria-hidden="true">
                                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        </svg><a href="mailto:firstflyaviation@gmail.com" class="text-gray-300">firstflyaviation@gmail.com</a></li>
                                    <li class="flex items-start gap-3"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-map-pin w-5 h-5 text-[#FFD700] flex-shrink-0 mt-0.5"
                                            aria-hidden="true">
                                            <path
                                                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                                            </path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg><span class="text-gray-300">3rd Floor, Chandra Towers,<br>No.23, Rajaji Rd,<br>Chennai, 600045, TN, India</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-white/10">
                        <div class="container mx-auto px-4 py-6">
                            <div
                                class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-400">
                                <div>&copy; <?php echo date('Y'); ?> First Fly Aviation Academy. All rights reserved.</div>
                                <div class="flex gap-6"><a href="#"
                                        class="hover:text-[#FFD700] transition-colors duration-300">Privacy Policy</a><a
                                        href="#" class="hover:text-[#FFD700] transition-colors duration-300">Terms of
                                        Service</a><a href="#"
                                        class="hover:text-[#FFD700] transition-colors duration-300">Refund Policy</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <div class="fixed bottom-6 right-6 z-50 animate-bounce"><a href="<?php echo $enroll_link; ?>" target="_blank"
                        class="gap-2 whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-primary-foreground px-4 py-2 w-16 h-16 rounded-full bg-green-500 hover:bg-green-600 shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-message-circle w-8 h-8 text-white" aria-hidden="true">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                        </svg></a>
                    <!-- <div
                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold animate-pulse">
                        1</div> -->
                </div>
            </div>
        </div>
    </div>
    <div id="leadPopup" class="fixed inset-0 z-[999] hidden items-center justify-center p-3 md:p-4 overflow-y-auto">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm popupOverlay"></div>
        <div class="relative w-full max-w-5xl animate-[popup_0.35s_ease]">
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-[0_20px_80px_rgba(0,0,0,0.35)] relative">
                <button type="button"
                    class="closeLeadPopup absolute top-4 right-4 w-11 h-11 rounded-full bg-white shadow-lg hover:bg-red-500 hover:text-white text-gray-700 text-2xl font-bold transition-all duration-300 z-30 flex items-center justify-center">
                    ×
                </button>
                <div class="grid grid-cols-1 lg:grid-cols-[0.9fr_1.1fr]">
                    <div
                        class="bg-gradient-to-br from-[#0A2463] via-[#123b91] to-[#0A2463] p-6 lg:p-8 relative overflow-hidden hidden lg:flex flex-col justify-center">
                        <div class="absolute top-0 right-0 w-56 h-56 bg-[#FFD700]/10 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <div
                                class="inline-flex items-center gap-2 bg-[#FFD700] text-[#0A2463] px-4 py-2 rounded-full text-sm font-bold shadow-lg mb-5">
                                ✈️ Aviation Career Guidance
                            </div>
                            <h3 class="text-3xl xl:text-4xl font-bold text-white leading-tight mb-5">
                                Start Your Dream Aviation Career Today
                            </h3>
                            <p class="text-white/75 leading-relaxed mb-7 text-sm xl:text-base">
                                Get expert counseling, course guidance and placement-focused aviation training support.
                            </p>
                            <div class="space-y-5">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-11 h-11 rounded-xl bg-[#FFD700] flex items-center justify-center text-xl flex-shrink-0">
                                        ✔️
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-base xl:text-lg">
                                            Industry Trainers
                                        </h4>
                                        <p class="text-white/60 text-sm">
                                            Learn from experienced aviation professionals.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-11 h-11 rounded-xl bg-[#FFD700] flex items-center justify-center text-xl flex-shrink-0">
                                        ✔️
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-base xl:text-lg">
                                            Placement Assistance
                                        </h4>
                                        <p class="text-white/60 text-sm">
                                            Interview guidance and career support included.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-11 h-11 rounded-xl bg-[#FFD700] flex items-center justify-center text-xl flex-shrink-0">
                                        ✔️
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-base xl:text-lg">
                                            Airline-Level Grooming
                                        </h4>
                                        <p class="text-white/60 text-sm">
                                            Professional communication & personality development.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 md:p-6 lg:p-8">
                        <div class="mb-6">
                            <div
                                class="inline-flex items-center gap-2 bg-red-100 text-red-600 px-4 py-2 rounded-full text-xs md:text-sm font-bold mb-4 animate-pulse">
                                ⏳ Limited Seats Available
                            </div>
                            <h3 id="popupTitle" class="text-3xl md:text-4xl font-bold text-[#0A2463] leading-tight mb-1">
                                Book Free Counseling
                            </h3>
                            <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                                Fill the form below and our team will contact you shortly.
                            </p>
                        </div>
                        <form id="leadForm" action="send-mail.php" method="POST" data-parsley-validate>
                            <div class="space-y-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-[#0A2463] mb-2">
                                            Full Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" required
                                            data-parsley-required-message="Please enter your name"
                                            class="w-full h-10 px-4 rounded-xl border border-gray-200 focus:border-[#3E92CC] focus:ring-4 focus:ring-[#3E92CC]/10 outline-none transition-all duration-300 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-[#0A2463] mb-2">
                                            Qualification <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="qualification" required
                                            data-parsley-required-message="Please enter qualification"
                                            class="w-full h-10 px-4 rounded-xl border border-gray-200 focus:border-[#3E92CC] focus:ring-4 focus:ring-[#3E92CC]/10 outline-none transition-all duration-300 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-[#0A2463] mb-2">
                                            Mobile Number <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="mobile" required data-parsley-pattern="^[6-9]\d{9}$" minlength="10" maxlength="10"
                                            data-parsley-required-message="Please enter mobile number"
                                            data-parsley-pattern-message="Enter valid mobile number"
                                            class="w-full h-10 px-4 rounded-xl border border-gray-200 focus:border-[#3E92CC] focus:ring-4 focus:ring-[#3E92CC]/10 outline-none transition-all duration-300 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-[#0A2463] mb-2">
                                            Email Address <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="email" required
                                            data-parsley-type-message="Enter valid email address"
                                            class="w-full h-10 px-4 rounded-xl border border-gray-200 focus:border-[#3E92CC] focus:ring-4 focus:ring-[#3E92CC]/10 outline-none transition-all duration-300 text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-[#0A2463] mb-2">
                                            Select Course <span class="text-red-500">*</span>
                                        </label>
                                        <select name="course" required
                                            data-parsley-required-message="Please select course"
                                            class="w-full h-10 px-4 rounded-xl border border-gray-200 focus:border-[#3E92CC] focus:ring-4 focus:ring-[#3E92CC]/10 outline-none transition-all duration-300 bg-white text-sm">
                                            <option value="">Choose Course</option>
                                            <option>Pilot Training (CPL & PPL)</option>
                                            <option>International Diploma In Airhostess Training</option>
                                            <option>International Diploma In Airport Operations</option>
                                            <option>International Diploma In Travel And Tourism</option>
                                            <option>Radio Telephony Restricted (RTR)</option>
                                            <option>Drone Training</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <button type="submit" id="popupButtonTitle"
                                            class="w-full h-12 rounded-xl bg-[#FFD700] hover:bg-[#ffe14a] text-[#0A2463] font-bold text-base shadow-[0_10px_40px_rgba(255,215,0,0.35)] hover:scale-[1.02] transition-all duration-300">
                                            🚀 Get Free Career Guidance
                                        </button>
                                    </div>
                                    <div
                                        class="md:col-span-2 text-center text-xs md:text-sm text-gray-500 leading-relaxed">
                                        By submitting this form, you agree to be contacted regarding aviation courses &
                                        counseling.
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @keyframes popup {
            0% {
                opacity: 0;
                transform: scale(.92) translateY(20px);
            }

            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .parsley-errors-list {
            margin-top: 8px;
            font-size: 13px;
            color: #dc2626;
            font-weight: 600;
        }

        .parsley-error {
            border-color: #dc2626 !important;
        }
    </style>
    <script>
        const leadPopup = document.getElementById('leadPopup');
        const popupTitle = document.getElementById('popupTitle');
        const popupButtonTitle = document.getElementById('popupButtonTitle');
        document.querySelectorAll('.openLeadPopup').forEach(button => {
            button.addEventListener('click', () => {
                const title = button.getAttribute('data-title');
                if (title) {
                    popupTitle.innerText = title;
                    popupButtonTitle.innerText = title;
                }
                leadPopup.classList.remove('hidden');
                leadPopup.classList.add('flex');
                document.body.style.overflow = 'hidden';
            });
        });

        function closeLeadModal() {
            leadPopup.classList.add('hidden');
            leadPopup.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        document.querySelectorAll('.closeLeadPopup,.popupOverlay').forEach(el => {
            el.addEventListener('click', closeLeadModal);
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeLeadModal();
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            /*
            |--------------------------------------------------------------------------
            | Mobile Menu Toggle
            |--------------------------------------------------------------------------
            */
            const menuButton = document.querySelector("#menuButton");
            const nav = document.querySelector("nav.hidden.md\\:flex");
            if (menuButton && nav) {
                //nav.classList.remove("hidden");
                //nav.style.display = "none";
                menuButton.addEventListener("click", function() {
                    if (nav.style.display === "none" || nav.style.display === "") {
                        nav.style.display = "flex";
                        nav.style.flexDirection = "column";
                        nav.style.position = "absolute";
                        nav.style.top = "80px";
                        nav.style.left = "0";
                        nav.style.width = "100%";
                        nav.style.background = "#fff";
                        nav.style.padding = "20px";
                        nav.style.gap = "20px";
                        nav.style.boxShadow = "0 10px 30px rgba(0,0,0,0.1)";
                    } else {
                        nav.style.display = "none";
                    }
                });
                window.addEventListener("resize", function() {
                    if (window.innerWidth >= 768) {
                        nav.style.display = "flex";
                        nav.style.flexDirection = "row";
                        nav.style.position = "static";
                        nav.style.width = "auto";
                        nav.style.background = "transparent";
                        nav.style.padding = "0";
                        nav.style.boxShadow = "none";
                    } else {
                        nav.style.display = "none";
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const faqItems = document.querySelectorAll(".faq-item");
            faqItems.forEach(item => {
                const button = item.querySelector(".faq-btn");
                const content = item.querySelector(".faq-content");
                const icon = item.querySelector(".faq-icon");
                const text = item.querySelector("span");
                button.addEventListener("click", () => {
                    const isOpen = content.style.maxHeight;
                    faqItems.forEach(otherItem => {
                        otherItem.querySelector(".faq-content").style.maxHeight = null;
                        otherItem.querySelector(".faq-icon").classList.remove("rotate-180");
                        otherItem.classList.remove("border-[#3E92CC]");
                        if (!otherItem.matches(":hover")) {
                            otherItem.classList.add("border-gray-200");
                        }
                        const otherText = otherItem.querySelector(".faq-btn span");
                        otherText.classList.remove("text-[#3E92CC]", "underline");
                        otherText.classList.add("text-[#0A2463]");
                    });
                    if (!isOpen) {
                        content.style.maxHeight = content.scrollHeight + "px";
                        icon.classList.add("rotate-180");
                        item.classList.remove("border-gray-200");
                        item.classList.add("border-[#3E92CC]");
                        text.classList.remove("text-[#0A2463]");
                        text.classList.add("text-[#3E92CC]", "underline");
                    }
                });
            });
        });
    </script>
    <script>
        const countdownDate = new Date().getTime() + (48 * 60 * 60 * 1000);

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = countdownDate - now;
            if (distance < 0) {
                document.getElementById("days").innerHTML = "00";
                document.getElementById("hours").innerHTML = "00";
                document.getElementById("minutes").innerHTML = "00";
                document.getElementById("seconds").innerHTML = "00";
                clearInterval(timer);
                return;
            }
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor(
                (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
            );
            const minutes = Math.floor(
                (distance % (1000 * 60 * 60)) / (1000 * 60)
            );
            const seconds = Math.floor(
                (distance % (1000 * 60)) / 1000
            );
            document.getElementById("days").innerHTML =
                String(days).padStart(2, "0");
            document.getElementById("hours").innerHTML =
                String(hours).padStart(2, "0");
            document.getElementById("minutes").innerHTML =
                String(minutes).padStart(2, "0");
            document.getElementById("seconds").innerHTML =
                String(seconds).padStart(2, "0");
        }
        updateCountdown();
        const timer = setInterval(updateCountdown, 1000);
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>
    <div id="toast"
        class="fixed bottom-5 right-5 z-[9999] hidden min-w-[320px] max-w-sm rounded-2xl shadow-2xl overflow-hidden">
        <div id="toastContent" class="flex items-start gap-4 p-5 bg-white border border-gray-200">
            <div id="toastIcon" class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                ✓
            </div>
            <div class="flex-1">
                <h4 id="toastTitle" class="font-black text-lg text-[#0A2463] mb-1">
                    Success
                </h4>
                <p id="toastMessage" class="text-sm text-gray-600 leading-relaxed">
                    Message here
                </p>
            </div>
        </div>
    </div>
    <script>
        $('#leadForm').parsley();

        function showToast(type, message) {
            const toast = document.getElementById('toast');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            toast.classList.remove('hidden');
            if (type === 'success') {
                toastIcon.className = 'w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 bg-green-100 text-green-600';
                toastTitle.innerText = 'Success';
                toastMessage.innerText = message;
            } else {
                toastIcon.className = 'w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 bg-red-100 text-red-600';
                toastTitle.innerText = 'Error';
                toastMessage.innerText = message;
            }
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 5000);
        }
        
        $('#leadForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            if (!form.parsley().validate()) {
                return;
            }
            const button = form.find('button[type="submit"]');
            const originalText = button.html();
            button.prop('disabled', true);
            button.removeClass('hover:scale-[1.02]');
            button.addClass('opacity-70 cursor-not-allowed');
            button.html(`
                <span class="inline-flex items-center justify-center gap-3">
                    <svg class="w-5 h-5 animate-spin text-[#0A2463]" viewBox="0 0 50 50">
                        <circle class="opacity-20" cx="25" cy="25" r="20" fill="none" stroke="currentColor" stroke-width="5"></circle>
                        <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-dasharray="90 150"></circle>
                    </svg>
                    <span class="font-semibold tracking-wide">
                        Submitting Request...
                    </span>
                </span>
            `);
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showToast('success', response.message);
                        form[0].reset();
                        form.parsley().reset();
                        setTimeout(() => {
                            closeLeadModal();
                        }, 1200);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Something went wrong. Please try again later.');
                },
                complete: function() {
                    button.prop('disabled', false);
                    button.removeClass('opacity-70 cursor-not-allowed');
                    button.addClass('hover:scale-[1.02]');
                    button.html(originalText);
                }
            });
        });
    </script>
</body>

</html>