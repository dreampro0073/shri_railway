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
							<!-- <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-video d-flex align-items-center">
								<i class="bi bi-play-fill"></i>
								<span>Watch Demo</span>
							</a> -->
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
                  <h3><span data-purecounter-start="0" data-purecounter-end="12" data-purecounter-duration="0" class="purecounter">8</span>+</h3>
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
        <p>Delivering quality services tailored to your needs with reliability, efficiency, and care.</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-palette"></i>
              </div>
              <h3>Creative Design</h3>
              <p>We design solutions that inspire, connect, and deliver results. Our journey is driven by innovation, guided by purpose, and built for growth.</p>
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
              <p>We design and develop fast, responsive, and user-friendly websites that connect ideas with people.</p>
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
              <p>We specialize in digital marketing that helps brands grow, connect with the right audience, and build a strong online presence through creative strategies and data-driven results.</p>
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
              <p>Crafting smart strategies that drive growth, innovation, and long-term success for businesses.</p>
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
              <p>Providing smart, reliable, and advanced security solutions to protect what matters most.</p>
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
              <p>Always available, our 24/7 support ensures quick solutions and reliable assistance anytime you need.</p>
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
        <p>We defines collaboration, communication, and flexibility to foster efficiency and balance.</p>
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
                <img src="{{url('/front_end/img/person/dipanshu.png')}}" class="img-fluid" alt="" loading="lazy">
              </div>
              <div class="member-info flex-grow-1">
                <h4>Dipanshu Chauhan</h4>
                <span>Product Manager</span>
                <p>Aliquam iure quaerat voluptatem praesentium possimus unde laudantium vel dolorum distinctio dire flow</p>
                <div class="social">
                  <!-- <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-twitter-x"></i></a> -->
                  <a href="https://www.linkedin.com/in/dipanshu-chauhan-390723169/" target="_blank"><i class="bi bi-linkedin"></i></a>
                  <!-- <a href=""><i class="bi bi-youtube"></i></a> -->
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6">
            <div class="team-member d-flex">
              <div class="member-img">
                <img src="{{url('front_end/img/person/devendra1.png')}}" class="img-fluid" alt="" loading="lazy">
              </div>
              <div class="member-info flex-grow-1">
                <h4>Devendra Diwakar</h4>
                <span>Developer</span>
                <p>Labore ipsam sit consequatur exercitationem rerum laboriosam laudantium aut quod dolores exercitationem ut</p>
                <!-- <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""><i class="bi bi-youtube"></i></a>
                </div> -->
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6">
            <div class="team-member d-flex">
              <div class="member-img">
                <img src="{{url('front_end/img/person/aashish.png')}}" class="img-fluid" alt="" loading="lazy">
              </div>
              <div class="member-info flex-grow-1">
                <h4>Aashish Chauhan</h4>
                <span>Developer</span>
                <p>Illum minima ea autem doloremque ipsum quidem quas aspernatur modi ut praesentium vel tque sed facilis at qui</p>
                <div class="social">
                  <!-- <a href="https://in.linkedin.com/in/ashish-chauhan-271b90284" target="_black"><i class="bi bi-facebook"></i></a> -->
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href="https://in.linkedin.com/in/ashish-chauhan-271b90284" target="_black"><i class="bi bi-linkedin"></i></a>
                  <a href=""><i class="bi bi-youtube"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6">
            <div class="team-member d-flex">
              <div class="member-img">
                <img src="{{url('front_end/img/person/pushpa.png')}}" class="img-fluid" alt="" loading="lazy">
              </div>
              <div class="member-info flex-grow-1">
                <h4>Pushpendra</h4>
                <span>Developer</span>
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

          

        </div>

      </div>

    </section>

    <section id="contact" class="contact section">
      <!-- Section Title -->
      <div class="container section-title">
        <h2>Contact</h2>
        <p>We're here to help! Whether you have questions, feedback, or need support, feel free to reach out. Our team is ready to assist you and will respond as soon as possible.</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="row align-items-stretch">
          <div class="col-lg-7 order-lg-1 order-2">
            <div class="contact-form-container">
              <div class="form-intro">
                <h2>Let's Start a Conversation</h2>
                <!-- <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur excepteur sint occaecat cupidatat.</p> -->
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
                <!-- <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam quis nostrud.</p> -->
              </div>

              <div class="contact-methods">
                <div class="contact-method">
                  <div class="contact-icon">
                    <i class="bi bi-geo-alt"></i>
                  </div>
                  <div class="contact-details">
                    <div>
                      <span class="method-label">Reg. Office</span>
                      <p>Shaheed Wala Grant, Buggawala, Haridwar, Uttarakhand (247662)</p>
                    </div>
                    <span class="method-label">Address</span>
                    <p>M-39, M Cluster, Shivalik Nagar, Haridwar, Uttarakhand (247662)</p>
                  </div>
                </div>

                <div class="contact-method">
                  <div class="contact-icon">
                    <i class="bi bi-envelope"></i>
                  </div>
                  <div class="contact-details">
                    <span class="method-label">Email</span>
                    <p><a href="mailto:aadhyasriwebsolutions@gmail.com">aadhyasriwebsolutions@gmail.com</a></p>
                  </div>
                </div>

                <div class="contact-method">
                  <div class="contact-icon">
                    <i class="bi bi-telephone"></i>
                  </div>
                  <div class="contact-details">
                    <span class="method-label">Phone</span>
                    <p>
                      <a href="tel:+91-7351334717">+91-7351334717</a>
                    </p>
                  </div>
                </div>

                <div class="contact-method">
                  <div class="contact-icon">
                    <i class="bi bi-clock"></i>
                  </div>
                  <div class="contact-details">
                    <span class="method-label">Hours</span>
                    <p>Monday - Sunday: 9AM - 6PM</p>
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