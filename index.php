<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cebu Plant Depot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style>
        ::-webkit-scrollbar { display: none; } 
        * { -ms-overflow-style: none; scrollbar-width: none; }
        
        body {
            background-color: #dad7cd;
            overflow-x: hidden;
            font-family: 'Inter';
            padding-top: 70px;
        }
        
        .navbar {
            background-color: #344e41 !important;
            padding: 0.5rem 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled { padding: 0.3rem 1rem; }
        
        .navbar-brand img {
            height: 3.5rem;
            width: auto;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand img:hover { transform: scale(1.05); }
        
        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            position: relative;
            transition: all 0.3s ease;
            border-radius: 4px;
        }
        
        .nav-link:hover { background-color: rgba(255, 255, 255, 0.1); }
        
        .login-btn {
            background-color: #588157;
            border-radius: 50px;
            padding: 0.5rem 1.5rem !important;
            transition: all 0.3s ease;
            margin-left: 0.5rem;
        }
        
        .login-btn:hover {
            background-color: #ffc107;
            color: #000 !important;
        }
        
        .landing-section {
            position: relative;
            padding: 4rem 0;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }
        
        .landing-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #f8f9fa 0%, #f8f9fa 40%, rgba(248, 249, 250, 0.8) 50%, rgba(248, 249, 250, 0.5) 60%, rgba(248, 249, 250, 0.2) 70%, rgba(248, 249, 250, 0) 80%);
            z-index: 0;
        }
        
        .landing-section::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-image: url('./images/landing.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            z-index: -1;
            animation: fadeIn 1.5s ease-in-out;
        }
        
        .landing-content {
            animation: fadeInUp 1s ease-out;
            position: relative;
            z-index: 1;
            padding: 3rem;
            max-width: 600px;
            margin-right: auto;
        }
        
        .landing-heading {
            font-size: 2rem;
            color: #344e41;
            font-weight: 400;
            margin-bottom: 1rem;
            line-height: 1.2;
            white-space: nowrap;
        }
        
        .landing-logo {
            max-width: 400px;
            margin-bottom: 1.5rem !important;
        }
        
        .btn-shop-now {
            background-color: #ffc107;
            color: #000;
            border: none;
            padding: 0.7rem 1.8rem;
            font-size: 1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-shop-now:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-explore {
            background-color: transparent;
            color: #000;
            border: 2px solid #000;
            padding: 0.7rem 1.8rem;
            font-size: 1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-explore:hover {
            background-color: #000;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .mobile-image-container {
            display: none;
            height: 350px;
            margin-top: 2rem;
        }
        
        .owners-choice-section {
            background-image: linear-gradient(to bottom right, #344e41, #3a5a40, #588157, #588157);
            padding: 4rem 0;
        }
        
        .owners-choice-title {
            font-size: 3.5rem;
            color: #ffc107;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .owners-choice-subtitle {
            font-size: 1.2rem;
            color: #ffffff;
            max-width: 700px;
            margin: 0 auto 3rem;
        }
        
        .owner-choice-card {
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .owner-choice-img {
            height: 250px;
            width: 100%;
            object-fit: cover;
        }
        
        .owner-choice-body {
            padding: 1.25rem;
            background: white;
        }
        
        .owner-choice-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .owner-choice-price {
            font-size: 1rem;
            color: #588157;
            font-weight: 700;
        }
        
        .carousel-control-prev, .carousel-control-next {
            width: 70px;
            height: 70px;
            opacity: 1;
            transition: all 0.3s ease;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            transform-origin: center center;
        }
        
        .carousel-control-prev:hover, .carousel-control-next:hover {
            transform: translateY(-50%) scale(1.05);
        }
        
        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: #ffc107;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            background-size: 30px;
            box-shadow: 0 0 8px rgba(255, 193, 7, 0.7);
            color: #344e41;
            transform-origin: center center;
        }
        .carousel-control-prev {
            left: -20px;
        }
        .carousel-control-next {
            right: -20px;
        }
        
        .carousel {
            overflow: visible;
        }
        
        .carousel-inner {
            transition: transform 0.6s ease-in-out;
        }

        
        .cta-section {
            padding: 5rem 0;
            background-color: #344e41;
            position: relative;
            overflow: hidden;
        }

        .cta-container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .cta-content {
            text-align: center;
            color: white;
            padding: 3rem;
            background: rgba(58, 90, 64, 0.8);
            border-radius: 15px;
            backdrop-filter: blur(5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #ffc107;
        }

        .cta-text {
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 2rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-btn {
            background-color: #ffc107;
            color: #000;
            border: none;
            padding: 0.8rem 2.5rem;
            font-size: 1.1rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 0 10px;
            text-decoration: none !important;
        }

        .cta-btn:hover {
            background-color: #e0a800;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .cta-btn-secondary {
            background-color: transparent;
            text-decoration: none !important;
            color: white;
            border: 2px solid white;
        }

        .cta-btn-secondary:hover {
            background-color: white;
            color: #344e41;
        }

        .cta-pattern {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0.1;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .footer {
            background-color: #344e41;
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .footer p {
            margin: 0;
            font-weight: 500;
        }
        
        @media (max-width: 1200px) {
            .landing-heading {
                font-size: 2.2rem;
            }
        }
        
        @media (max-width: 992px) {
            .landing-section { padding: 2rem 0; }
            .landing-section::before { background: #f8f9fa; }
            .landing-section::after { display: none; }
            .landing-content { padding: 2rem 1rem !important; }
            .mobile-image-container { display: block; }
            .owners-choice-title { font-size: 2.5rem; }
            .cta-title { font-size: 2rem; }
            .cta-text { font-size: 1rem; }
            .landing-heading {
                white-space: normal;
                font-size: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .cta-content {
                padding: 2rem 1.5rem;
            }
            .cta-title {
                font-size: 1.8rem;
            }
            .cta-btn {
                display: block;
                width: 100%;
                margin: 10px 0;
            }
            .landing-heading {
                font-size: 1.8rem;
            }
        }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand me-lg-5" href="index.php">
                <img src="./Main/images/logonavwhite.png" alt="Cebu Plant Depot Logo" class="img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="./Main/php/index.php" class="nav-link login-btn">
                        <i class="fas fa-user me-2"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="landing-section">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12">
                    <div class="landing-content">
                        <img src="./Main/images/logolanding2.png" class="landing-logo img-fluid" alt="Cebu Plant Depot Logo">
                        <h1 class="landing-heading">Bring Nature Into Your Home</h1>
                        <p class="landing-subtext">
                            Discover our premium collection of indoor and outdoor plants to transform your living spaces.
                            Each plant is carefully selected to bring life and beauty to your environment.
                        </p>
                        <div class="d-flex flex-wrap btn-group gap-3">
                            <a href="./Main/php/index.php" class="btn btn-shop-now btn-lg">
                                <i class="fas fa-leaf me-2"></i>Shop Now
                            </a>
                            <a href="#owners-choice" class="btn btn-explore btn-lg">
                                <i class="fas fa-binoculars me-2"></i>Explore
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="mobile-image-container">
                        <img src="./images/landing.jpg" class="img-fluid" alt="Plant Collection">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="owners-choice-section" id="owners-choice">
        <div class="container">
            <h2 class="owners-choice-title text-center">Owner's Choice</h2>
            <p class="owners-choice-subtitle text-center">Our personal favorites - handpicked for their exceptional beauty and quality</p>
            
            <div id="ownersChoiceCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row g-4">
                            <div class="col-md-3">
                                <div class="owner-choice-card">
                                    <img src="./images/1.jpg" class="owner-choice-img" alt="Ruby Plant">
                                    <div class="owner-choice-body">
                                        <h5 class="owner-choice-name">Ruby</h5>
                                        <p class="owner-choice-price">Php 350.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="owner-choice-card">
                                    <img src="./images/2.jpg" class="owner-choice-img" alt="Tineke Plant">
                                    <div class="owner-choice-body">
                                        <h5 class="owner-choice-name">Tineke</h5>
                                        <p class="owner-choice-price">Php 350.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="owner-choice-card">
                                    <img src="./images/3.jpg" class="owner-choice-img" alt="Lemon Lime Plant">
                                    <div class="owner-choice-body">
                                        <h5 class="owner-choice-name">Lemon Lime</h5>
                                        <p class="owner-choice-price">Php 450.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="owner-choice-card">
                                    <img src="./images/4.jpg" class="owner-choice-img" alt="Moonshine Plant">
                                    <div class="owner-choice-body">
                                        <h5 class="owner-choice-name">Moonshine</h5>
                                        <p class="owner-choice-price">Php 350.00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row g-4">
                            <div class="col-md-3">
                                <div class="owner-choice-card">
                                    <img src="./images/5.jpg" class="owner-choice-img" alt="Black Prince Plant">
                                    <div class="owner-choice-body">
                                        <h5 class="owner-choice-name">Black Prince</h5>
                                        <p class="owner-choice-price">Php 350.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="owner-choice-card">
                                    <img src="./images/6.jpg" class="owner-choice-img" alt="Blechnum Fern">
                                    <div class="owner-choice-body">
                                        <h5 class="owner-choice-name">Blechnum Fern</h5>
                                        <p class="owner-choice-price">Php 650.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="owner-choice-card">
                                    <img src="./images/7.jpg" class="owner-choice-img" alt="Kenzui Fern">
                                    <div class="owner-choice-body">
                                        <h5 class="owner-choice-name">Kenzui Fern</h5>
                                        <p class="owner-choice-price">Php 300.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="owner-choice-card">
                                    <img src="./images/8.jpg" class="owner-choice-img" alt="Lace Fern">
                                    <div class="owner-choice-body">
                                        <h5 class="owner-choice-name">Lace Fern</h5>
                                        <p class="owner-choice-price">Php 200.00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#ownersChoiceCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#ownersChoiceCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-pattern"></div>
        <div class="cta-plant-decoration cta-plant-1"></div>
        <div class="cta-plant-decoration cta-plant-2"></div>
        <div class="cta-container">
            <div class="cta-content">
                <h2 class="cta-title">Ready to Transform Your Space?</h2>
                <p class="cta-text">
                    Whether you're looking for a statement piece or a collection of greenery, we have the perfect plants 
                    to match your style and space. Start your plant journey today!
                </p>
                <div class="cta-buttons">
                    <a href="./Main/php/index.php" class="cta-btn">
                        <i class="fas fa-shopping-cart me-2"></i>Shop Now
                    </a>
                    <a href="contact.php" class="cta-btn cta-btn-secondary">
                        <i class="fas fa-phone me-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>© 2025 Cebu Plant Depot. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
