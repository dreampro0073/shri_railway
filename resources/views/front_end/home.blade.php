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
								<span>IT Business Consulting</span>
								We are provide the<br> best IT Solutions
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
								<span>IT Business Consulting</span>
								We are provide the<br> best IT Solutions
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
					<div class="">
						<div class="title">About the Company</div>
						<h1 class="section-title">
							Aadhyasri Web Solutions
						</h1>
						<p class="text mb-24">
							Does any industry face a more complex audience journey and marketing sales process than B2B technology? Consider the number of people who influence a sale, the length of the decision-making cycle, the competing interests of the people who purchase, implement, manage, and use the technology. It’s a lot meaningful content here.
						</p>
						<h2 class="d-none">Best IT Company in Haridwar</h2>
						<div class="d-flex exp-div mb-4">
							<div class="icon">
								<i class="bi bi-patch-check"></i>
							</div>
							<div class="info">
								<h3>Experience</h3>
								<p class="text">
									Does any industry face a more complex audience journey and marketing sales process.
								</p>
							</div>
						</div>
						<div class="d-flex exp-div">
							<div class="icon">
								<i class="bi bi-gear"></i>
							</div>
							<div class="info">
								<h3>Quick Support</h3>
								<p class="text">
									Does any industry face a more complex audience journey and marketing.
								</p>
							</div>

						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="about-flash">
						<div class="about-flash-image">
							<img alt="Best Software Company" src="front-end/images/pattern-1.png" />
						</div>
						<div class="about-flash-info">
							<img alt="Best Digital Company" src="front-end/images/about.jpg" />
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
				<h2 class="section-title text-white">
					We deal with the aspects of </br>professional IT Services
				</h2>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="service-box">
						<div class="icon">
							<i class="bi bi-laptop"></i>
						</div>
						<div class="info">
							<h3>IT Soluations</h3>
							<p class="text">
								We deliver reliable IT solutions that help businesses grow through secure systems, smart technology, and scalable digital services tailored to meet modern business needs efficiently.
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-3 mob-mt-20">
					<div class="service-box">
						<div class="icon">
							<i class="bi bi-pc-display-horizontal"></i>
						</div>
						<div class="info">
							<h3>Security System</h3>
							<p class="text">
								We provide advanced security systems to protect homes and businesses with reliable surveillance, access control, and smart monitoring solutions designed for safety, efficiency, and peace of mind.
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-3 mob-mt-20">
					<div class="service-box">
						<div class="icon">
							<i class="bi bi-webcam"></i>
						</div>
						<div class="info">
							<h3>Web Development</h3>
							<p class="text">
								We build modern, secure, and user-friendly websites using clean code and smart design. Our focus is on performance, scalability, and solutions that help businesses grow online.
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-3 mob-mt-20">
					<div class="service-box">
						<div class="icon">
							<i class="bi bi-database-lock"></i>
						</div>
						<div class="info">
							<h3>Database Security</h3>
							<p class="text">
								Our database security services safeguard critical data using advanced protection, continuous monitoring, and secure access management to reduce risks and ensure compliance.
							</p>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
	<section class="section service-section" id="our-service">
		<div class="upper-container ">
			<div class="text-center">
				<div>
					<h2 class="section-title text-white">
						We deal with the aspects of </br>professional IT Services
					</h2>
				</div>
			</div>
			<span class="icon1"></span>
			<span class="icon2"></span>
			<span class="icon3"></span>
		</div>
		<div class="lower-container">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="service-box1">
							<div class="shape-one"></div>
							<div class="shape-two"></div>
							<div class="inner-box">
								<div class="icon">
									<i class="bi bi-laptop"></i>
								</div>
								<div class="info">
									<h3>Web Develpment</h3>
									<p class="text">
										We carry more than just good coding skills. Our experience makes us stand out from other web development.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 mob-mt-20">
						<div class="service-box1">
							<div class="shape-one"></div>
							<div class="shape-two"></div>
							<div class="inner-box">
								<div class="icon">
									<i class="bi bi-android2"></i>
								</div>
								<div class="info">
									<h3>Mobile App Development</h3>
									<p class="text">
										Create complex enterprise software, ensure reliable software integration, modernise your legacy system.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 mob-mt-20">
						<div class="service-box1">
							<div class="shape-one"></div>
							<div class="shape-two"></div>
							<div class="inner-box">
								<div class="icon">
									<i class="bi bi-view-list"></i>
								</div>
								<div class="info">
									<h3>UI/UX Design</h3>
									<p class="text">
										UI/UX Design Build the product you need on time with an experienced team that uses a clear and effective design process.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 mt-4">
						<div class="service-box1">
							<div class="shape-one"></div>
							<div class="shape-two"></div>
							<div class="inner-box">
								<div class="icon">
									<i class="bi bi-briefcase"></i>
								</div>
								<div class="info">
									<h3>QA & Testing</h3>
									<p class="text">
										Turn to our experts to perform comprehensive, multi-stage testing and auditing of your software.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 mt-4">
						<div class="service-box1">
							<div class="shape-one"></div>
							<div class="shape-two"></div>
							<div class="inner-box">
								<div class="icon">
									<i class="bi bi-pc-display-horizontal"></i>
								</div>
								<div class="info">
									<h3>IT Counsultancy</h3>
									<p class="text">
										Trust our top minds to eliminate workflow pain points, implement new tech, and consolidate app portfolios.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 mt-4">
						<div class="service-box1">
							<div class="shape-one"></div>
							<div class="shape-two"></div>
							<div class="inner-box">
								<div class="icon">
									<i class="bi bi-microsoft-teams"></i>
								</div>
								<div class="info">
									<h3>Dedicated Team</h3>
									<p class="text">
										Over the past decade, our customers succeeded by leveraging Intellectsoft’s process of building, motivating.
									</p>
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
				<div class="col-md-6">
					<h2 class="section-title text-white mb-0 mob-text-center">
						Preparing For Your Business<br>Success With IT Solution
					</h2>
				</div>
				<div class="col-md-6 text-end mob-mt-20 mob-text-center">
					<a href="tel:+91-7351334717" class="theme-btn mt-2">
						<span class="txt">Meet With Us</span>
					</a>
				</div>
			</div>
		</div>
	</section>

	<section class="section how-we-work">
		<div class="container">
			<div class="text-center">
				<div class="title text-white">How We Work</div>
				<h2 class="section-title">
					We deal with the aspects of </br>professional IT Services
				</h2>
			</div>
			<div class="row">
				<div class="col-lg-3 col-md-3">
					<div class="step-item">
						<div class="step-circle">
							<span>1</span>
						</div>
						<h3>Discovery</h3>
						<p class="text">Understanding your business goals and requirements through in-depth analysis and consultation sessions.</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-3">
					<div class="step-item">
						<div class="step-circle">
							<span>2</span>
						</div>
						<h3>Planning</h3>
						<p class="text">Creating detailed project roadmaps and strategies aligned with your objectives and timeline requirements.</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-3">
					<div class="step-item">
						<div class="step-circle">
							<span>3</span>
						</div>
						<h3>Execution</h3>
						<p class="text">Implementing solutions with precision while maintaining transparent communication throughout the process.</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-3">
					<div class="step-item">
						<div class="step-circle">
							<span>4</span>
						</div>
						<h3>Delivery</h3>
						<p class="text">Finalizing implementations and providing comprehensive support to ensure long-term success.</p>
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
							<div class="title text-white">TECHNOLOGY INDEX</div>
							<h2 class="section-title text-white mb-0">
								We Deliver Solutions with<br> the Goal of Trusting Workships
							</h2>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="tech-row rh1 mob-mt-20">
						<div class="tech-col">
							<div class="tech-box">
								<a href="javascript:;">
									<div class="icon">
										<i class="bi bi-pc-display-horizontal"></i>
									</div>
									<h4>
										Web
									</h4>
								</a>
							</div>
						</div>
						<div class="tech-col">
							<div class="tech-box">
								<a href="javascript:;">
									<div class="icon">
										<i class="bi bi-apple"></i>
									</div>
									<h4>
										Ios
									</h4>
								</a>
							</div>
						</div>
						<div class="tech-col">
							<div class="tech-box">
								<a href="javascript:;">
									<div class="icon">
										<i class="bi bi-android2"></i>
									</div>
									<h4>
										Android
									</h4>
								</a>
							</div>
						</div>
					</div>
					<div class="tech-row rh2 mt-4">
						<div class="tech-col">
							<div class="tech-box">
								<a href="javascript:;">
									<div class="icon">
										<i class="bi bi-bezier2"></i>
									</div>
									<h4>
										Iot
									</h4>
								</a>
							</div>
						</div>
						<div class="tech-col">
							<div class="tech-box">
								<a href="javascript:;">
									<div class="icon">
										<i class="bi bi-server"></i>
									</div>
									<h4>
										WEARALABLES
									</h4>
								</a>
							</div>
						</div>
						<div class="tech-col">
							<div class="tech-box">
								<a href="javascript:;">
									<div class="icon">
										<i class="bi bi-card-list"></i>
									</div>
									<h4>
										SEO
									</h4>
								</a>
							</div>
						</div>
					</div>
				
				</div>
			</div>
		</div>
	</section>
	<section class="section projects">
		<div class="container">
			<div class="text-center">
				<div class="title">LATEST PROJECTS</div>
				<h2 class="section-title">
					Reads now our Latest Projects
				</h2>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="project-box">
						<div class="image">
							<a href="javascript:;" class="arrow-icon">
								<i class="bi bi-arrow-right"></i>
							</a>
							<img alt="Railway Station Loungue Management" src="{{url('front-end/images/projects/loungue.jpg')}}">
							<div class="info">
								<div>
									<h3>
										Railway Station Loungue Management
									</h3>
									<p>
										All-in-one lounge management software to simplify operations and elevate service
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 mob-mt-20">
					<div class="project-box">
						<div class="image">
							<a href="javascript:;" class="arrow-icon">
								<i class="bi bi-arrow-right"></i>
							</a>
							<img alt="Canteen Management | Shopping Shop Software" src="{{url('front-end/images/projects/canteen.jpg')}}">
							<div class="info">
								<div>
									<h3>
										Canteen Management
									</h3>
									<p>
										Smart canteen management software for fast service and smooth daily operations
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 mt-4">
					<div class="project-box">
						<div class="image">
							<a href="javascript:;" class="arrow-icon">
								<i class="bi bi-arrow-right"></i>
							</a>
							<img alt="Vehicle Parking App" src="{{url('front-end/images/projects/parking.jpg')}}">
							<div class="info">
								<div>
									<h3>
										Vehicle Parking App
									</h3>
									<p>
										Smart vehicle parking app for easy booking, tracking, and payments
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 mt-4">
					<div class="project-box">
						<div class="image">
							<a href="javascript:;" class="arrow-icon">
								<i class="bi bi-arrow-right"></i>
							</a>
							<img alt="Financial Management" src="{{url('front-end/images/projects/fin.jpg')}}">
							<div class="info">
								<div>
									<h3>
										Financial Management
									</h3>
									<p>
										Smart financial management for better control, clarity, and growth
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="section contact-us mob-pt-0" id="contact">
		<div class="container">
			<div class="text-center">
				<div class="title">Get in Touch</div>
				<h2 class="section-title">
					Contact Us
				</h2>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="service-box1 with-box">
						<div class="shape-one"></div>
						<div class="shape-two"></div>
						<div class="inner-box">
							<div class="icon">
								<i class="bi bi-geo-alt"></i>
							</div>
							<div class="info">
								<h3>Address</h3>
								<p class="text">
									M-39, M Cluster Shivalik Nagar, Haridwar,(247662)
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mob-mt-20">
					<div class="service-box1 with-box">
						<div class="shape-one"></div>
						<div class="shape-two"></div>
						<div class="inner-box">
							<div class="icon">
								<i class="bi bi-telephone-inbound"></i>
							</div>
							<div class="info">
								<h3>Call us</h3>
								<p class="text">
									<a title="Call us to Aadhyasri Web Solutions" href="tel:+91-7351334717">7351334717</a>
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mob-mt-20">
					<div class="service-box1 with-box">
						<div class="shape-one"></div>
						<div class="shape-two"></div>
						<div class="inner-box">
							<div class="icon">
								<i class="bi bi-envelope"></i>
							</div>
							<div class="info">
								<h3>Mail Us</h3>
								<p class="text">
									<a title="Mail to Aadhyasri Web Solutions" href="mailto:aadhyasriwebsolutions@gmail.com">aadhyasriwebsolutions@gmail.com</a>
								</p> 
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</section>
</div>
@endsection