@extends('front_end.layout')

@section('main')

<div class="main">

    <section class="banner" id="home">
        <div class="home-slider owl-carousel">
            <div class="item">
                <div class="image">
                    <img alt="IT Solutions | Web Development" src="{{url('front-end/images/banner/banner1.jpg')}}">
                    <div class="content">
                        <div class="container">
                            <div class="banner-heading">
                                <span>Trusted IT partner in Haridwar</span>
                                We build smart, scalable<br>IT solutions that grow with you
                            </div>
                            <div class="hero-actions">
                                <a href="#contact" class="theme-btn scroll-link"><span class="txt">Start a project</span></a>
                                <a href="tel:+91-7351334717" class="theme-btn outline"><span class="txt">Call +91 73513 34717</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="image">
                    <img alt="IT Business Consulting | App Development" src="{{url('front-end/images/banner/banner4.jpg')}}">
                    <div class="content">
                        <div class="container">
                            <div class="banner-heading">
                                <span>Digital transformation & technology</span>
                                Build better software.<br>Grow your business.
                            </div>
                            <div class="hero-actions">
                                <a href="#our-service" class="theme-btn scroll-link"><span class="txt">Explore services</span></a>
                                <a href="tel:+91-7351334717" class="theme-btn outline"><span class="txt">Call +91 73513 34717</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section about-us max" id="about-us">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="title">About the company</div>
                    <h1 class="section-title">Aadhyasri Web Solutions</h1>
                    <p class="text mb-24">
                        We're a Haridwar-based technology team helping businesses turn ideas into
                        reliable, well-built software — from marketing websites to full product
                        platforms. Every engagement starts with understanding your goals, not
                        just your feature list.
                    </p>
                    <h2 class="d-none">Best IT Company in Haridwar</h2>
                    <div class="d-flex exp-div mb-4">
                        <div class="icon"><i class="bi bi-patch-check"></i></div>
                        <div class="info">
                            <h3>Proven experience</h3>
                            <p class="text">A senior team that has shipped web, mobile and enterprise
                                projects across retail, logistics and finance.</p>
                        </div>
                    </div>
                    <div class="d-flex exp-div">
                        <div class="icon"><i class="bi bi-gear"></i></div>
                        <div class="info">
                            <h3>Quick, direct support</h3>
                            <p class="text">You talk to the people building your product — no ticket
                                queues, no account-manager hand-offs.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-flash">
                        <div class="about-flash-image">
                            <img alt="Best Software Company" src="{{url('front-end/images/pattern-1.png')}}" />
                        </div>
                        <div class="about-flash-info">
                            <img alt="Best Digital Company" src="{{url('front-end/images/about.jpg')}}" />
                        </div>
                        <div class="about-stat-card">
                            <div class="num">40+</div>
                            <div class="lbl">Projects delivered for businesses across India</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pattern-layer">
        <div class="container">
            <div class="text-center">
                <div class="title text-white">Who we are</div>
                <h2 class="section-title text-white">Every part of professional IT,<br>handled under one roof</h2>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="service-box">
                        <div class="icon"><i class="bi bi-laptop"></i></div>
                        <div class="info">
                            <h3>IT solutions</h3>
                            <p class="text">Reliable systems and scalable digital services tailored to how your business actually runs.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mob-mt-20">
                    <div class="service-box">
                        <div class="icon"><i class="bi bi-pc-display-horizontal"></i></div>
                        <div class="info">
                            <h3>Security systems</h3>
                            <p class="text">Surveillance, access control and monitoring built for safety without slowing your team down.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mob-mt-20">
                    <div class="service-box">
                        <div class="icon"><i class="bi bi-webcam"></i></div>
                        <div class="info">
                            <h3>Web development</h3>
                            <p class="text">Modern, secure, fast websites — built on clean code, not page-builder shortcuts.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mob-mt-20">
                    <div class="service-box">
                        <div class="icon"><i class="bi bi-database-lock"></i></div>
                        <div class="info">
                            <h3>Database security</h3>
                            <p class="text">Advanced protection and continuous monitoring to keep critical data safe and compliant.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section service-section" id="our-service">
        <div class="upper-container">
            <div class="text-center">
                <h2 class="section-title text-white">What we build for you</h2>
            </div>
        </div>
        <div class="lower-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="service-box1">
                            <div class="inner-box">
                                <div class="icon"><i class="bi bi-laptop"></i></div>
                                <div class="info">
                                    <h3>Web development</h3>
                                    <p class="text">Fast, maintainable websites and web apps built with clean code and a genuine focus on how customers use them.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mob-mt-20">
                        <div class="service-box1">
                            <div class="inner-box">
                                <div class="icon"><i class="bi bi-android2"></i></div>
                                <div class="info">
                                    <h3>Mobile app development</h3>
                                    <p class="text">Native and cross-platform apps for iOS and Android, built to handle real business complexity.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mob-mt-20">
                        <div class="service-box1">
                            <div class="inner-box">
                                <div class="icon"><i class="bi bi-view-list"></i></div>
                                <div class="info">
                                    <h3>UI/UX design</h3>
                                    <p class="text">Interfaces designed around your users first, with a clear process from wireframe to final screen.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4">
                        <div class="service-box1">
                            <div class="inner-box">
                                <div class="icon"><i class="bi bi-briefcase"></i></div>
                                <div class="info">
                                    <h3>QA & testing</h3>
                                    <p class="text">Multi-stage testing and auditing so what ships is stable, secure and ready for real users.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4">
                        <div class="service-box1">
                            <div class="inner-box">
                                <div class="icon"><i class="bi bi-pc-display-horizontal"></i></div>
                                <div class="info">
                                    <h3>IT consultancy</h3>
                                    <p class="text">We help you fix workflow pain points, adopt the right tech, and simplify your app portfolio.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4">
                        <div class="service-box1">
                            <div class="inner-box">
                                <div class="icon"><i class="bi bi-microsoft-teams"></i></div>
                                <div class="info">
                                    <h3>Dedicated team</h3>
                                    <p class="text">An embedded team that works as an extension of yours, for projects that need ongoing support.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section call-us-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h2 class="section-title text-white mb-0 mob-text-center">Preparing your business for success with the right IT solution</h2>
                </div>
                <div class="col-md-5 text-end mob-mt-20 mob-text-center">
                    <a href="tel:+91-7351334717" class="theme-btn light mt-2"><span class="txt">Meet with us</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="section how-we-work">
        <div class="container">
            <div class="text-center">
                <div class="title">How we work</div>
                <h2 class="section-title">A straightforward process,<br>start to finish</h2>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="step-item">
                        <div class="step-circle"><span>1</span></div>
                        <h3>Discovery</h3>
                        <p class="text">We start by understanding your business goals through in-depth analysis and conversation.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="step-item">
                        <div class="step-circle"><span>2</span></div>
                        <h3>Planning</h3>
                        <p class="text">A detailed roadmap and strategy, aligned to your objectives, budget and timeline.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="step-item">
                        <div class="step-circle"><span>3</span></div>
                        <h3>Execution</h3>
                        <p class="text">We build with precision and keep communication open throughout the project.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="step-item">
                        <div class="step-circle"><span>4</span></div>
                        <h3>Delivery</h3>
                        <p class="text">We finalise, hand over, and stay on for long-term support when you need it.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="technology-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex h-100 align-items-center">
                        <div>
                            <div class="title text-white">Technology index</div>
                            <h2 class="section-title text-white mb-0">Solutions built on technology you can trust</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tech-row mob-mt-20">
                        <div class="tech-col">
                            <div class="tech-box"><a href="javascript:;"><div class="icon"><i class="bi bi-pc-display-horizontal"></i></div><h4>Web</h4></a></div>
                        </div>
                        <div class="tech-col">
                            <div class="tech-box"><a href="javascript:;"><div class="icon"><i class="bi bi-apple"></i></div><h4>iOS</h4></a></div>
                        </div>
                        <div class="tech-col">
                            <div class="tech-box"><a href="javascript:;"><div class="icon"><i class="bi bi-android2"></i></div><h4>Android</h4></a></div>
                        </div>
                        <div class="tech-col">
                            <div class="tech-box"><a href="javascript:;"><div class="icon"><i class="bi bi-bezier2"></i></div><h4>IoT</h4></a></div>
                        </div>
                        <div class="tech-col">
                            <div class="tech-box"><a href="javascript:;"><div class="icon"><i class="bi bi-server"></i></div><h4>Wearables</h4></a></div>
                        </div>
                        <div class="tech-col">
                            <div class="tech-box"><a href="javascript:;"><div class="icon"><i class="bi bi-card-list"></i></div><h4>SEO</h4></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section projects" id="projects">
        <div class="container">
            <div class="text-center">
                <div class="title">Latest projects</div>
                <h2 class="section-title">A few things we've recently built</h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="project-box">
                        <div class="image">
                            <a href="javascript:;" class="arrow-icon"><i class="bi bi-arrow-right"></i></a>
                            <img alt="Railway Station Lounge Management" src="{{url('front-end/images/projects/loungue.jpg')}}">
                            <div class="info">
                                <div>
                                    <h3>Railway station lounge management</h3>
                                    <p>All-in-one lounge management software to simplify operations and elevate service.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mob-mt-20">
                    <div class="project-box">
                        <div class="image">
                            <a href="javascript:;" class="arrow-icon"><i class="bi bi-arrow-right"></i></a>
                            <img alt="Canteen Management Software" src="{{url('front-end/images/projects/canteen.jpg')}}">
                            <div class="info">
                                <div>
                                    <h3>Canteen management</h3>
                                    <p>Smart canteen management software for fast service and smooth daily operations.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-4">
                    <div class="project-box">
                        <div class="image">
                            <a href="javascript:;" class="arrow-icon"><i class="bi bi-arrow-right"></i></a>
                            <img alt="Vehicle Parking App" src="{{url('front-end/images/projects/parking.jpg')}}">
                            <div class="info">
                                <div>
                                    <h3>Vehicle parking app</h3>
                                    <p>Smart vehicle parking app for easy booking, tracking, and payments.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-4">
                    <div class="project-box">
                        <div class="image">
                            <a href="javascript:;" class="arrow-icon"><i class="bi bi-arrow-right"></i></a>
                            <img alt="Financial Management Software" src="{{url('front-end/images/projects/fin.jpg')}}">
                            <div class="info">
                                <div>
                                    <h3>Financial management</h3>
                                    <p>Smart financial management for better control, clarity, and growth.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section team-section" id="team">
    <div class="container">
        <div class="text-center">
            <div class="title">Our team</div>
            <h2 class="section-title">The people behind your project</h2>
        </div>
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="team-card">
                    <div class="avatar"><span>DC</span></div>
                    <h3>Dipanshu Chauhan</h3>
                    <div class="role">Founder &amp; Project Manager</div>
                    <p class="text">Keeps timelines, budgets and communication on track from kickoff to delivery.</p>
                    <div class="socials">
                        <a href="javascript:;" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="javascript:;" aria-label="Email"><i class="bi bi-envelope"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-6 mob-mt-20">
                <div class="team-card">
                    <div class="avatar"><span>DD</span></div>
                    <h3>Divandra Diwakar</h3>
                    <div class="role">Founder &amp; Backend Engineer</div>
                    <p class="text">Builds the systems and APIs that keep everything running reliably behind the scenes.</p>
                    <div class="socials">
                        <a href="javascript:;" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="javascript:;" aria-label="Email"><i class="bi bi-envelope"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mob-mt-20">
                <div class="team-card">
                    <div class="avatar"><span>PD</span></div>
                    <h3>Pushpendra Diwakar</h3>
                    <div class="role">Mobile Application Developer</div>
                    <p class="text">Develops scalable, user-friendly mobile apps with a focus on performance and reliability.</p>
                    <div class="socials">
                        <a href="javascript:;" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="javascript:;" aria-label="Email"><i class="bi bi-envelope"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mob-mt-20">
                <div class="team-card">
                    <div class="avatar"><span>SS</span></div>
                    <h3>Saloni Chauhan</h3>
                    <div class="role">UI/UX Designer</div>
                    <p class="text">Turns requirements into clean, usable interfaces people actually enjoy working with.</p>
                    <div class="socials">
                        <a href="javascript:;" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="javascript:;" aria-label="Email"><i class="bi bi-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <section class="section contact-us mob-pt-0" id="contact">
        <div class="container">
            <div class="text-center">
                <div class="title">Get in touch</div>
                <h2 class="section-title">Contact us</h2>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="service-box1 with-box">
                        <div class="inner-box">
                            <div class="icon"><i class="bi bi-geo-alt"></i></div>
                            <div class="info">
                                <h3>Address</h3>
                                <p class="text">M-39, M Cluster, Shivalik Nagar,<br>Haridwar, Uttarakhand – 247662</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mob-mt-20">
                    <div class="service-box1 with-box">
                        <div class="inner-box">
                            <div class="icon"><i class="bi bi-telephone-inbound"></i></div>
                            <div class="info">
                                <h3>Call us</h3>
                                <p class="text"><a href="tel:+91-7351334717">+91 73513 34717</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mob-mt-20">
                    <div class="service-box1 with-box">
                        <div class="inner-box">
                            <div class="icon"><i class="bi bi-envelope"></i></div>
                            <div class="info">
                                <h3>Mail us</h3>
                                <p class="text"><a href="mailto:aadhyasriwebsolutions@gmail.com">aadhyasriwebsolutions@gmail.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection
