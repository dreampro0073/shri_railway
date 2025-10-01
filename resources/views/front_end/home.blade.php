@extends('front_end.layout')

@section('main')

<div class="main">
	<section id="hero" class="hero section">

		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="hero-content">
						<h1>Transform Your <span>Digital Future</span></h1>
						<p>We are passionate about turning ideas into impactful digital experiences. With a commitment to innovation and dedication, we specialize in delivering high-quality software development, website design, and digital solutions that help businesses grow and thrive in the digital age.</p>
						<div class="hero-actions justify-content-center justify-content-lg-start">
							<a href="#services" class="btn-primary scrollto">Start Journey</a>
							<a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-video d-flex align-items-center">
								<i class="bi bi-play-fill"></i>
								<span>Watch Demo</span>
							</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="hero-image">
						<img src="{{url('front_end/img/illustration/illustration-28.webp')}}" class="img-fluid floating" alt="">
					</div>
				</div>
			</div>
		</div>

	</section>

	<section id="about" class="about section">

      <div class="container">

        <div class="row align-items-center">

          <!-- Image Column -->
          <div class="col-lg-6">
            <div class="about-image">
              <img src="{{url('front_end/img/about/about-portrait-4.webp')}}" alt="About" class="img-fluid">
            </div>
          </div>

          <!-- Content Column -->
          <div class="col-lg-6">
            <div class="content">
              <h2>Aadhyasri Web Solutions – Crafting Excellence Through Innovation</h2>
              <p class="lead">Our team blends creativity with technical expertise to craft custom solutions tailored to your unique needs. From robust software applications to engaging, responsive websites, we ensure every project is executed with precision, efficiency, and a focus on delivering measurable results.</p>

              <p>
              	At the heart of our mission is client satisfaction—we don’t just build digital solutions; we build lasting partnerships. Whether it’s enhancing your online presence or streamlining your business processes, Aadhyasri Web Solution is your trusted partner in navigating the digital landscape.
              </p>
              
            
              <!-- Stats Row -->
              <div class="stats-row">
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="150" data-purecounter-duration="0" class="purecounter">150</span>+</h3>
                  <p>Projects Completed</p>
                </div>
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="12" data-purecounter-duration="0" class="purecounter">12</span>+</h3>
                  <p>Years Experience</p>
                </div>
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="98" data-purecounter-duration="0" class="purecounter">98</span>%</h3>
                  <p>Client Satisfaction</p>
                </div>
              </div><!-- End Stats Row -->

              <!-- CTA Button -->
              <div class="cta-wrapper">
                <a href="#" class="btn-cta">
                  <span>Discover Our Story</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>

            </div>
          </div>

        </div>

      </div>

    </section>

    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Services</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-palette"></i>
              </div>
              <h3>Creative Design</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p>
              <a href="service-details.html" class="service-link">
                Learn More
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-4 col-md-6">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-code-slash"></i>
              </div>
              <h3>Web Development</h3>
              <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.</p>
              <a href="service-details.html" class="service-link">
                Learn More
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-4 col-md-6">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-megaphone"></i>
              </div>
              <h3>Digital Marketing</h3>
              <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
              <a href="service-details.html" class="service-link">
                Learn More
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-4 col-md-6">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-graph-up-arrow"></i>
              </div>
              <h3>Business Strategy</h3>
              <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim.</p>
              <a href="service-details.html" class="service-link">
                Learn More
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-4 col-md-6">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-shield-check"></i>
              </div>
              <h3>Security Solutions</h3>
              <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
              <a href="service-details.html" class="service-link">
                Learn More
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-4 col-md-6">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-headset"></i>
              </div>
              <h3>24/7 Support</h3>
              <p>Totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae.</p>
              <a href="service-details.html" class="service-link">
                Learn More
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div><!-- End Service Card -->

        </div>

      </div>

    </section>

    <section id="features" class="features section">

      <div class="container">

        <div class="features-grid">
          <div class="features-card">
            <div class="icon-wrapper">
              <i class="bi bi-laptop"></i>
            </div>
            <h3>Streamlined Workflow Solution</h3>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.
            </p>
            <div class="features-list">
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Integrated development environment</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Cloud-based collaborative tools</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Automated testing procedures</span>
              </div>
            </div>
            <div class="image-container">
              <img src="{{url('front_end/img/illustration/illustration-14.webp')}}" alt="Streamlined Workflow" class="img-fluid">
            </div>
          </div>

          <div class="features-card">
            <div class="icon-wrapper">
              <i class="bi bi-graph-up"></i>
            </div>
            <h3>Performance Analytics</h3>
            <p>
              Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel.
            </p>
            <div class="features-list">
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Real-time data visualization</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Custom report generation</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Predictive analysis models</span>
              </div>
            </div>
            <div class="image-container">
              <img src="{{url('front_end/img/illustration/illustration-6.webp')}}" alt="Performance Analytics" class="img-fluid">
            </div>
          </div>

          <div class="features-card">
            <div class="icon-wrapper">
              <i class="bi bi-shield-lock"></i>
            </div>
            <h3>Enterprise Security Framework</h3>
            <p>
              Quisque velit nisi, pretium ut lacinia in, elementum id enim. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar.
            </p>
            <div class="features-list">
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Multi-factor authentication</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>End-to-end encryption standard</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Automated security audits</span>
              </div>
            </div>
            <div class="image-container">
              <img src="{{url('front_end/img/illustration/illustration-7.webp')}}" alt="Security Framework" class="img-fluid">
            </div>
          </div>

          <div class="features-card">
            <div class="icon-wrapper">
              <i class="bi bi-people"></i>
            </div>
            <h3>Collaborative Team Environment</h3>
            <p>
              Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Cras ultricies ligula sed magna dictum porta.
            </p>
            <div class="features-list">
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Shared workspace functionality</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Real-time communication tools</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Progress tracking dashboards</span>
              </div>
            </div>
            <div class="image-container">
              <img src="{{url('front_end/img/illustration/illustration-8.webp')}}" alt="Team Environment" class="img-fluid">
            </div>
          </div>
        </div>

      </div>

    </section>

    <section id="how-we-work" class="how-we-work section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>How We Work</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="steps-wrapper">

          <div class="row">
            <div class="col-lg-3 col-md-6">
              <div class="step-item">
                <div class="step-circle">
                  <span>1</span>
                </div>
                <h3>Discovery</h3>
                <p>Understanding your business goals and requirements through in-depth analysis and consultation sessions.</p>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="step-item">
                <div class="step-circle">
                  <span>2</span>
                </div>
                <h3>Planning</h3>
                <p>Creating detailed project roadmaps and strategies aligned with your objectives and timeline requirements.</p>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="step-item">
                <div class="step-circle">
                  <span>3</span>
                </div>
                <h3>Execution</h3>
                <p>Implementing solutions with precision while maintaining transparent communication throughout the process.</p>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="step-item">
                <div class="step-circle">
                  <span>4</span>
                </div>
                <h3>Delivery</h3>
                <p>Finalizing implementations and providing comprehensive support to ensure long-term success.</p>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section>

<section id="team" class="team section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Team</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6">
            <div class="team-member d-flex">
              <div class="member-img">
                <img src="{{url('/front_end/img/person/person-m-7.webp')}}" class="img-fluid" alt="" loading="lazy">
              </div>
              <div class="member-info flex-grow-1">
                <h4>Walter White</h4>
                <span>Chief Executive Officer</span>
                <p>Aliquam iure quaerat voluptatem praesentium possimus unde laudantium vel dolorum distinctio dire flow</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""><i class="bi bi-youtube"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6">
            <div class="team-member d-flex">
              <div class="member-img">
                <img src="{{url('front_end/img/person/person-f-8.webp')}}" class="img-fluid" alt="" loading="lazy">
              </div>
              <div class="member-info flex-grow-1">
                <h4>Sarah Jhonson</h4>
                <span>Product Manager</span>
                <p>Labore ipsam sit consequatur exercitationem rerum laboriosam laudantium aut quod dolores exercitationem ut</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""><i class="bi bi-youtube"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6">
            <div class="team-member d-flex">
              <div class="member-img">
                <img src="{{url('front_end/img/person/person-m-6.webp')}}" class="img-fluid" alt="" loading="lazy">
              </div>
              <div class="member-info flex-grow-1">
                <h4>William Anderson</h4>
                <span>CTO</span>
                <p>Illum minima ea autem doloremque ipsum quidem quas aspernatur modi ut praesentium vel tque sed facilis at qui</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""><i class="bi bi-youtube"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6">
            <div class="team-member d-flex">
              <div class="member-img">
                <img src="{{url('front_end/img/person/person-f-4.webp')}}" class="img-fluid" alt="" loading="lazy">
              </div>
              <div class="member-info flex-grow-1">
                <h4>Amanda Jepson</h4>
                <span>Accountant</span>
                <p>Magni voluptatem accusamus assumenda cum nisi aut qui dolorem voluptate sed et veniam quasi quam consectetur</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""><i class="bi bi-youtube"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6">
            <div class="team-member d-flex">
              <div class="member-img">
                <img src="{{url('front_end/img/person/person-m-12.webp')}}" class="img-fluid" alt="" loading="lazy">
              </div>
              <div class="member-info flex-grow-1">
                <h4>Brian Doe</h4>
                <span>Marketing</span>
                <p>Qui consequuntur quos accusamus magnam quo est molestiae eius laboriosam sunt doloribus quia impedit laborum velit</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""><i class="bi bi-youtube"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6">
            <div class="team-member d-flex">
              <div class="member-img">
                <img src="{{url('front_end/img/person/person-f-9.webp')}}" class="img-fluid" alt="" loading="lazy">
              </div>
              <div class="member-info flex-grow-1">
                <h4>Josepha Palas</h4>
                <span>Operation</span>
                <p>Sint sint eveniet explicabo amet consequatur nesciunt error enim rerum earum et omnis fugit eligendi cupiditate vel</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""><i class="bi bi-youtube"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

        </div>

      </div>

    </section>

    <section id="contact" class="contact section">
      <!-- Section Title -->
      <div class="container section-title">
        <h2>Contact</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="row align-items-stretch">
          <div class="col-lg-7 order-lg-1 order-2">
            <div class="contact-form-container">
              <div class="form-intro">
                <h2>Let's Start a Conversation</h2>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur excepteur sint occaecat cupidatat.</p>
              </div>

              <form action="forms/contact.php" method="post" class="php-email-form contact-form">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-field">
                      <input type="text" name="name" class="form-input" id="userName" placeholder="Your Name" required="">
                      <label for="userName" class="field-label">Name</label>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-field">
                      <input type="email" class="form-input" name="email" id="userEmail" placeholder="Your Email" required="">
                      <label for="userEmail" class="field-label">Email</label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-field">
                      <input type="tel" class="form-input" name="phone" id="userPhone" placeholder="Your Phone">
                      <label for="userPhone" class="field-label">Phone</label>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-field">
                      <input type="text" class="form-input" name="subject" id="messageSubject" placeholder="Subject" required="">
                      <label for="messageSubject" class="field-label">Subject</label>
                    </div>
                  </div>
                </div>

                <div class="form-field message-field">
                  <textarea class="form-input message-input" name="message" id="userMessage" rows="5" placeholder="Tell us about your project" required=""></textarea>
                  <label for="userMessage" class="field-label">Message</label>
                </div>

                <div class="my-3">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>
                </div>

                <button type="submit" class="send-button">
                  Send Message
                  <span class="button-arrow">→</span>
                </button>
              </form>
            </div>
          </div>

          <div class="col-lg-5 order-lg-2 order-1">
            <div class="contact-sidebar">
              <div class="contact-header">
                <h3>Get in Touch</h3>
                <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam quis nostrud.</p>
              </div>

              <div class="contact-methods">
                <div class="contact-method">
                  <div class="contact-icon">
                    <i class="bi bi-geo-alt"></i>
                  </div>
                  <div class="contact-details">
                    <span class="method-label">Address</span>
                    <p>892 Park Avenue, Manhattan<br>New York, NY 10075</p>
                  </div>
                </div>

                <div class="contact-method">
                  <div class="contact-icon">
                    <i class="bi bi-envelope"></i>
                  </div>
                  <div class="contact-details">
                    <span class="method-label">Email</span>
                    <p>hello@businessdemo.com</p>
                  </div>
                </div>

                <div class="contact-method">
                  <div class="contact-icon">
                    <i class="bi bi-telephone"></i>
                  </div>
                  <div class="contact-details">
                    <span class="method-label">Phone</span>
                    <p>+1 (555) 789-2468</p>
                  </div>
                </div>

                <div class="contact-method">
                  <div class="contact-icon">
                    <i class="bi bi-clock"></i>
                  </div>
                  <div class="contact-details">
                    <span class="method-label">Hours</span>
                    <p>Monday - Friday: 9AM - 6PM<br>Saturday: 10AM - 4PM</p>
                  </div>
                </div>
              </div>

              <div class="connect-section">
                <span class="connect-label">Connect with us</span>
                <div class="social-links">
                  <a href="#" class="social-link">
                    <i class="bi bi-linkedin"></i>
                  </a>
                  <a href="#" class="social-link">
                    <i class="bi bi-twitter-x"></i>
                  </a>
                  <a href="#" class="social-link">
                    <i class="bi bi-instagram"></i>
                  </a>
                  <a href="#" class="social-link">
                    <i class="bi bi-facebook"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection