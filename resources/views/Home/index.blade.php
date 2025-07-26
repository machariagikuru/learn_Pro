<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnPro - Your Path To Smarter Learning</title>
    <!-- Google Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="{{ asset('Photos/logomark.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="landing-page-body">

    <!-- Hero Section -->
    <section class="hero-section text-center text-white d-flex align-items-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3 hero-title">LearnPro: Your Path<br>To Smarter Learning</h1>
            <p class="lead mb-4 hero-subtitle">Discover courses, track progress, and<br>achieve more with LearnPro's intuitive LMS.</p>
            <div class="hero-buttons">
                <!-- Link to Dashboard -->
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2 px-4">Sign up for free</a>
                <a href="https://youtu.be/AN7YFcCmF5c?si=OZLunM6XlZ7oB2fy" target="_blank" class="btn btn-outline-light btn-lg px-4 watch-video-btn">
                    <i class="fa-regular fa-circle-play me-2"></i>Watch Video
                </a>
            </div>
        </div>
    </section>

    <!-- Partner Logos -->
    <section class="partner-logos py-4">
        <div class="container text-center">
            <div class="d-flex flex-wrap justify-content-center align-items-center">
                <!-- Replace with actual logos/links if available -->
                <img src="{{ asset('Photos/image copy 8.png') }}" alt="Dribbble" class="mx-3 my-2 partner-logo">
                <img src="{{ asset('Photos/image copy 2.png') }}" alt="Perplexity" class="mx-3 my-2 partner-logo">
                <img src="{{ asset('Photos/image copy 3.png') }}" alt="Lark" class="mx-3 my-2 partner-logo">
                <img src="{{ asset('Photos/image copy 4.png') }}" alt="Zapier" class="mx-3 my-2 partner-logo">
                <img src="{{ asset('Photos/image copy 5.png') }}" alt="Mollie" class="mx-3 my-2 partner-logo">
                <img src="{{ asset('Photos/image copy 6.png') }}" alt="Bird" class="mx-3 my-2 partner-logo">
                <img src="{{ asset('Photos/image copy 7.png') }}" alt="Whereby" class="mx-3 my-2 partner-logo">
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
   <!-- landing.html -->
<!-- ... (sections before Who We Are) ... -->

  <!-- landing.html -->
<!-- ... (sections before Who We Are) ... -->

    <!-- Who We Are Section -->
    <section class="who-we-are-section py-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                 <div class="col-12 position-relative" style="min-height: 500px;">

                    <!-- Image Wrapper (Positioned behind) -->
                    <div class="who-we-are-image-wrapper shadow-sm">
                         <img src="https://images.unsplash.com/photo-1683803055067-1ca1c17cb2b9?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid" alt="Person working on laptop near window">
                         <!-- Notification is MOVED OUT of here -->
                    </div>

                     <!-- Text Card (Positioned overlapping) -->
                    <div class="card who-we-are-card shadow border-0">
                        <div class="card-body p-4 p-md-5 position-relative">
                            <div class="decorative-quote"></div>
                            <h2 class="fw-bold mb-3 section-title">Who We Are ?</h2>
                            <p class="text-muted mb-4">At <span class="fw-semibold text-dark">LearnPro</span>, we making education accessible, engaging, and <span class="text-primary fw-medium">effective</span> for everyone. Our platform is designed to empower learners with the tools they need to <span class="text-primary fw-medium">succeed</span>, offering a seamless learning experience that adapts to your goals.</p>
                            <a href="{{ route('register') }}" class="btn btn-primary px-4">Start Learning Now</a>
                        </div>
                    </div>

                    <!-- Floating Notification Element (MOVED HERE - Sibling to card and image wrapper) -->
                    <div class="floating-notification shadow-sm">
                        <i class="fa-solid fa-circle-check text-success me-2"></i>
                        <div>
                           <p class="mb-0 small fw-medium">Well done! You have submitted your tasks of Javascript</p>
                           <p class="mb-0 text-muted" style="font-size: 0.75rem;">Today</p>
                        </div>
                   </div>
                   <!-- End Moved Notification -->

                 </div>
            </div>
        </div>
    </section>
    <!-- End Who We Are Section -->

<!-- ... (rest of landing page sections) ... -->

<!-- ... (rest of landing page sections) ... -->

    <!-- landing.html -->
<!-- ... (sections before Why Choose Us) ... -->

     <!-- Why Choose Us Section -->
     <!-- ***** REMOVED bg-light CLASS ***** -->
     <section class="why-choose-us py-5">
        <div class="container text-center">
            <h2 class="display-6 fw-bold mb-3 section-title">Why Choose LearnPro?</h2>
            <p class="text-muted lead mb-5">At LearnPro, we've designed our platform with features that make learning effective, engaging, and seamless</p>
            <div class="row g-4">
                <!-- Feature Card 1 -->
                <div class="col-lg-4">
                    <!-- ***** REMOVED shadow-sm, border-light, ADDED border ***** -->
                    <div class="card feature-card h-100 border text-center p-4">
                         <!-- ***** ICON UPDATED ***** -->
                         <div class="feature-icon mb-3 mx-auto">
                             <i class="fa-solid fa-chart-pie"></i> <!-- Example: Pie chart icon -->
                         </div>
                        <div class="card-body pt-0"> <!-- Removed extra padding from card-body top -->
                            <h5 class="card-title fw-semibold mb-3">Personalized Learning</h5>
                            <p class="card-text text-muted small">Customized learning plans designed to fit your individual needs and goals.</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm mt-3">LEARN MORE</a>
                        </div>
                    </div>
                </div>
                 <!-- Feature Card 2 -->
                 <div class="col-lg-4">
                     <!-- ***** REMOVED shadow-sm, border-light, ADDED border ***** -->
                    <div class="card feature-card h-100 border text-center p-4">
                         <!-- ***** ICON UPDATED ***** -->
                         <div class="feature-icon mb-3 mx-auto">
                             <i class="fa-solid fa-puzzle-piece"></i> <!-- Example: Puzzle icon -->
                         </div>
                        <div class="card-body pt-0">
                            <h5 class="card-title fw-semibold mb-3">Interactive & Gamification</h5>
                            <p class="card-text text-muted small">Engage in interactive learning with games, challenges and quizzes.</p>
                             <a href="#" class="btn btn-outline-primary btn-sm mt-3">LEARN MORE</a>
                        </div>
                    </div>
                </div>
                 <!-- Feature Card 3 -->
                 <div class="col-lg-4">
                     <!-- ***** REMOVED shadow-sm, border-light, ADDED border ***** -->
                    <div class="card feature-card h-100 border text-center p-4">
                         <!-- ***** ICON UPDATED ***** -->
                         <div class="feature-icon mb-3 mx-auto">
                             <i class="fa-solid fa-bolt-lightning"></i> <!-- Example: Lightning icon -->
                         </div>
                        <div class="card-body pt-0">
                            <h5 class="card-title fw-semibold mb-3">Kick Your Goal</h5>
                            <p class="card-text text-muted small">Share insights on projects. Engage with a community that values collaboration and discover new perspectives.</p>
                             <a href="#" class="btn btn-outline-primary btn-sm mt-3">LEARN MORE</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Why Choose Us Section -->

<!-- ... (rest of landing page sections) ... -->

    <!-- Feature Detail Sections -->
    <section class="feature-detail py-5">
         <!-- Personalized Learning Paths -->
         <div class="container mb-5 pb-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-3 section-title"><span class="text-primary">Personalized</span><br>Learning Paths</h2>
                    <p class="text-muted">Tailor your learning journey with personalized plans to meet your needs and goals. Our adaptive technology helps you focus on key skills for success.</p>
                </div>
                 <div class="col-lg-6">
                     <img src="https://images.unsplash.com/photo-1688646583123-16844c80e78a?q=80&w=2340&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid rounded shadow-sm" alt="Personalized Path Image">
                 </div>
            </div>
         </div>
         <!-- Interactive Tools -->
         <div class="container mb-5 pb-4">
             <div class="row align-items-center g-5">
                 <div class="col-lg-6 order-lg-last">
                    <h2 class="display-6 fw-bold mb-3 section-title"><span class="text-primary">Interactive</span> Tools &<br>Gamification</h2>
                    <p class="text-muted">LearnPro makes education engaging with games, challenges, and quizzes to keep you motivated. Unlock achievements as you learn!</p>
                 </div>
                 <div class="col-lg-6 order-lg-first">
                      <img src="https://plus.unsplash.com/premium_photo-1668383208409-ecca414341a3?q=80&w=2340&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid rounded shadow-sm" alt="Interactive Tools Image">
                 </div>
            </div>
         </div>
          <!-- Kick Your Goal -->
         <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-3 section-title">Kick Your <span class="text-primary">Goal</span></h2>
                    <p class="text-muted">Connect with peers, share insights, and collaborate on projects. LearnPro fosters a community-driven approach, ensuring you're never learning alone.</p>
                </div>
                 <div class="col-lg-6">
                     <img src="https://images.unsplash.com/photo-1682668938117-a45b79fb4476?q=80&w=2340&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid rounded shadow-sm" alt="Community Image">
                 </div>
            </div>
         </div>
    </section>

    <!-- landing.html -->
<!-- ... (sections before Testimonials) ... -->

     <!-- Testimonials Section -->
     <!-- ***** REMOVED bg-light ***** -->
     <section class="testimonials py-5">
        <div class="container">
            <!-- ***** ADDED TESTIMONIAL LABEL ***** -->
            <p class="text-center text-primary fw-semibold small mb-2">TESTIMONIAL</p>
            <h2 class="text-center display-6 fw-bold mb-5 section-title">Client Success Stories</h2>
             <div class="row g-4 justify-content-center"> <!-- Added justify-content-center -->
                <!-- Testimonial 1 -->
                <div class="col-lg-4 col-md-6"> <!-- Added col-md-6 -->
                    <!-- ***** UPDATED card classes ***** -->
                    <div class="card testimonial-card h-100 border p-4">
                         <div class="stars mb-3">
                            <!-- ***** UPDATED star colors ***** -->
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-regular fa-star text-muted"></i>
                        </div>
                        <p class="card-text text-muted small mb-4 flex-grow-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua quis nostrud exercitation ullamcoLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</p>
                        <div class="d-flex align-items-center mt-auto">
                            <!-- ***** Updated placeholder avatar example ***** -->
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="testimonial-avatar rounded-circle me-3" alt="Maxin Will Avatar">
                            <div>
                                <h6 class="mb-0 fw-semibold">Maxin Will</h6>
                                <p class="text-muted small mb-0">Product Manager</p>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- Testimonial 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card testimonial-card h-100 border p-4">
                         <div class="stars mb-3">
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-solid fa-star text-primary"></i>
                        </div>
                        <p class="card-text text-muted small mb-4 flex-grow-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua quis nostrud exercitation ullamco</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="https://randomuser.me/api/portraits/men/33.jpg" class="testimonial-avatar rounded-circle me-3" alt="Maxin Will Avatar">
                            <div>
                                <h6 class="mb-0 fw-semibold">Maxin Will</h6>
                                <p class="text-muted small mb-0">Product Manager</p>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- Testimonial 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card testimonial-card h-100 border p-4">
                        <div class="stars mb-3">
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-solid fa-star text-primary"></i>
                            <i class="fa-regular fa-star text-muted"></i>
                        </div>
                        <p class="card-text text-muted small mb-4 flex-grow-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua quis nostrud exercitation ullamcoLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</p>
                        <div class="d-flex align-items-center mt-auto">
                           <img src="https://randomuser.me/api/portraits/men/34.jpg" class="testimonial-avatar rounded-circle me-3" alt="Maxin Will Avatar">
                           <div>
                               <h6 class="mb-0 fw-semibold">Maxin Will</h6>
                               <p class="text-muted small mb-0">Product Manager</p>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Testimonials Section -->

<!-- ... (rest of landing page sections) ... -->

    <!-- Final CTA Section -->
    <section class="final-cta py-5">
        <div class="container text-center">
            <h2 class="display-6 fw-bold mb-3 section-title">Learn in Minutes, Master in Hours</h2>
            <p class="lead text-muted mb-4">LearnPro, dive into courses quickly, track your progress, and achieve your goals faster. An intuitive<br>platform that makes learning simple and engaging.</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">Start Learning Now</a>
        </div>
    </section>

    <!-- Dashboard Preview -->
     <section class="dashboard-preview py-5 bg-light">
        <div class="container">
             <img src="{{ asset('Photos/Browser.png') }}" class="img-fluid rounded shadow-lg mx-auto d-block" alt="Dashboard Preview">
        </div>
    </section>


    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JS (Optional for landing page interactivity) -->
    <!-- <script src="script.js"></script> -->
</body>
</html>