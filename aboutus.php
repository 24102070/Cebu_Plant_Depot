<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Cebu Plant Depot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="shortcut icon" href="./images/logonavwhite.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
  ::-webkit-scrollbar { display: none; } 
  * { 
    -ms-overflow-style: none;  
    scrollbar-width: none;     
  }
  
  body {
    background-color: #f8f9fa;
    overflow-x: hidden;
    font-family: 'Inter', sans-serif;
    padding-top: 70px;
    color: #333;
    line-height: 1.6;
  }

  .hero-about {
    background: linear-gradient(rgba(52, 78, 65, 0.8), rgba(58, 90, 64, 0.8)), 
                url('https://images.unsplash.com/photo-1484503793037-5c9644d6d80a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 80px 0 60px;
    text-align: center;
  }

  .hero-about h1 {
    font-size: 2.8rem;
    font-weight: 700;
    margin-bottom: 15px;
  }

  .hero-about p {
    font-size: 1.2rem;
    max-width: 700px;
    margin: 0 auto;
    opacity: 0.9;
  }

  .main-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
  }

  .about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 60px;
  }

  .about-image {
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    min-height: 400px;
    background: url('./images/about.jpg') center/cover no-repeat;
  }

  .about-content {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
  }

  .about-content h2 {
    color: #344e41;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 20px;
  }

  .about-content p {
    font-size: 1.05rem;
    color: #555;
    margin-bottom: 20px;
  }

  .owner-section {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    margin-bottom: 60px;
  }

  .owner-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
  }

  .owner-img {
    padding: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f8f1;
  }

  .owner-img img {
    width: 100%;
    max-width: 240px;
    height: 240px;
    object-fit: cover;
    border-radius: 50%;
    border: 5px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }

  .owner-bio {
    padding: 40px;
  }

  .owner-bio h2 {
    color: #344e41;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 15px;
  }

  .owner-bio p {
    font-size: 1.05rem;
    color: #555;
    margin-bottom: 15px;
  }

  .mission-section {
    background: #344e41;
    color: white;
    border-radius: 12px;
    padding: 50px;
    margin-bottom: 60px;
    text-align: center;
  }

  .mission-section h2 {
    font-size: 2rem;
    margin-bottom: 20px;
    font-weight: 700;
  }

  .mission-section p {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 800px;
    margin: 0 auto;
  }

  footer {
    background: #344e41;
    color: white;
    padding: 30px 0;
    text-align: center;
    margin-top: 60px;
  }

  footer p {
    margin: 0;
    font-size: 1rem;
  }

  @media (max-width: 992px) {
    .about-grid {
      grid-template-columns: 1fr;
    }
    
    .owner-grid {
      grid-template-columns: 1fr;
    }
    
    .owner-img {
      padding: 40px 30px 20px;
    }
    
    .hero-about h1 {
      font-size: 2.3rem;
    }
  }

  @media (max-width: 768px) {
    .hero-about {
      padding: 60px 0 40px;
    }
    
    .hero-about h1 {
      font-size: 1.8rem;
    }
    
    .hero-about p {
      font-size: 1rem;
    }
    
    .about-content, .owner-bio {
      padding: 30px;
    }
    
    .mission-section {
      padding: 40px 20px;
    }
  }

  /* Navbar styles remain unchanged */
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
</style>
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

    <section class="hero-about">
        <div class="container">
            <h1>Our Story</h1>
            <p>Discover the passion behind Cebu Plant Depot and our commitment to bringing nature's beauty into your life</p>
        </div>
    </section>

    <div class="main-container">
        <div class="about-grid">
            <div class="about-image"></div>
            <div class="about-content">
                <h2>About Cebu Plant Depot</h2>
                <p>At Cebu Plant Depot, we believe that plants bring life, joy, and a sense of peace to every space. What started as a small passion project in Cebu quickly grew into a trusted plant shop for fellow plant lovers.</p>
                <p>We specialize in offering healthy, beautiful, and carefully selected plants — perfect for homes, offices, or gifts. Each plant in our collection is nurtured with care, ensuring you receive only the best quality.</p>
                <p>Our mission extends beyond selling plants. We're committed to helping our customers develop their green thumbs through expert advice and ongoing support. Whether you're a seasoned plant parent or just starting your plant journey, we're here to help every step of the way.</p>
            </div>
        </div>

        <div class="owner-section">
            <div class="owner-grid">
                <div class="owner-img">
                    <img src="./images/owner.jpg" alt="Melody Good Diano">
                </div>
                <div class="owner-bio">
                    <h2>Melody Good Diano</h2>
                    <p>Hi! I'm Melody, the owner and founder of Cebu Plant Depot. My journey with plants began as a personal passion, a way to bring more life and tranquility into my home. What started with a few potted plants quickly grew into an obsession with all things green and growing.</p>
                    <p>I personally select and care for every plant we offer, treating each one as if it were going into my own home. This hands-on approach ensures that when you shop with us, you're getting plants that have been loved and nurtured from the start.</p>
                    <p>When I'm not tending to our plant collection, you'll find me sharing plant care tips with our community or exploring new ways to help people connect with nature through plants.</p>
                </div>
            </div>
        </div>

        <div class="mission-section">
            <h2>Our Mission</h2>
            <p>To enrich lives by making the joy of plant ownership accessible to everyone, while promoting sustainable practices and fostering a community of plant lovers.</p>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>© 2025 Cebu Plant Depot. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>