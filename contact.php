<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Cebu Plant Depot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="shortcut icon" href="./images/logonavwhite.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
  ::-webkit-scrollbar { display: none; } 
  * { 
    -ms-overflow-style: none;  
    scrollbar-width: none;     
  }
  
  body {
    background-color: #f8f9fa;
    font-family: 'Inter', sans-serif;
    padding-top: 70px;
    color: #333;
    overflow-x: hidden;
  }

  .hero-section {
    background: linear-gradient(rgba(58, 90, 64, 0.9), rgba(52, 78, 65, 0.9)), 
                url('https://images.unsplash.com/photo-1483794344563-d27a8d18014e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 80px 0;
    text-align: center;
    margin-bottom: 60px;
  }

  .hero-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .hero-content h1 {
    font-size: 2.8rem;
    font-weight: 700;
    margin-bottom: 20px;
  }

  .hero-content p {
    font-size: 1.2rem;
    opacity: 0.9;
    line-height: 1.6;
  }

  .contact-sections {
    max-width: 1200px;
    margin: 0 auto 60px;
    padding: 0 20px;
  }

  .section-title {
    text-align: center;
    margin-bottom: 50px;
  }

  .section-title h2 {
    color: #344e41;
    font-weight: 700;
    font-size: 2rem;
    position: relative;
    display: inline-block;
    margin-bottom: 15px;
  }

  .section-title h2:after {
    content: '';
    position: absolute;
    width: 60px;
    height: 3px;
    background: #588157;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
  }

  .section-title p {
    color: #666;
    max-width: 700px;
    margin: 0 auto;
    font-size: 1.1rem;
  }

  .contact-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 60px;
  }

  .contact-card {
    background: white;
    border-radius: 12px;
    padding: 40px 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    text-align: center;
    border-top: 4px solid #588157;
  }

  .contact-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
  }

  .contact-icon {
    font-size: 2.5rem;
    color: #588157;
    margin-bottom: 20px;
  }

  .contact-card h3 {
    color: #344e41;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 1.4rem;
  }

  .contact-info {
    text-align: left;
  }

  .contact-info p {
    margin-bottom: 12px;
    font-size: 1rem;
    line-height: 1.6;
    display: flex;
    align-items: flex-start;
  }

  .contact-info strong {
    font-weight: 500;
    min-width: 80px;
    display: inline-block;
  }

  .contact-info a {
    color: #588157;
    text-decoration: none;
    transition: all 0.2s ease;
    font-weight: 500;
  }

  .contact-info a:hover {
    color: #3a5a40;
    text-decoration: underline;
  }

  .map-section {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 60px;
  }

  .map-header {
    background: #344e41;
    color: white;
    padding: 20px;
    text-align: center;
  }

  .map-header h3 {
    margin: 0;
    font-weight: 600;
  }

  iframe {
    width: 100%;
    height: 400px;
    border: none;
    display: block;
  }

  .brand-section {
    background: #f1f8f1;
    padding: 80px 0;
    text-align: center;
  }

  .brand-content {
    max-width: 600px;
    margin: 0 auto;
  }

  .brand-content img {
    width: 200px;
    margin-bottom: 20px;
  }

  .brand-content p {
    font-size: 1rem;
    color: #3a5a40;
    font-style: italic;
    line-height: 1.6;
  }

  @media (max-width: 992px) {
    .hero-content h1 {
      font-size: 2.2rem;
    }
    
    .contact-cards {
      grid-template-columns: 1fr;
    }
    
    iframe {
      height: 300px;
    }
  }

  @media (max-width: 576px) {
    .hero-section {
      padding: 60px 0;
    }
    
    .hero-content h1 {
      font-size: 1.8rem;
    }
    
    .contact-card {
      padding: 30px 20px;
    }
    
    iframe {
      height: 250px;
    }
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

    <section class="hero-section">
        <div class="hero-content">
            <h1>We'd Love to Hear From You</h1>
            <p>Whether you have questions about our plants, need gardening advice, or want to visit our nursery, our team is here to help you grow your green space.</p>
        </div>
    </section>

    <div class="contact-sections">
        <div class="section-title">
            <h2>Contact Information</h2>
            <p>Reach out to us through any of these channels. We're happy to answer your questions and help with your plant needs.</p>
        </div>

        <div class="contact-cards">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Visit Us</h3>
                <div class="contact-info">
                    <p><strong>Address:</strong> Cebu Plant Depot, Cebu City, Philippines</p>
                    <p><strong>Hours:</strong> Mon–Sat: 8:00 AM – 6:00 PM</p>
                    <p><strong>Sunday:</strong> Closed</p>
                </div>
            </div>

            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3>Call Us</h3>
                <div class="contact-info">
                    <p><strong>Phone:</strong> 09639278793</p>
                    <p><strong>Email:</strong> melodydiano@yahoo.com</p>
                    <p><strong>Facebook:</strong> <a href="https://www.facebook.com/share/g/1Yw9N3MKAs/" target="_blank">Cebu Plant Depot</a></p>
                </div>
            </div>

            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email Us</h3>
                <div class="contact-info">
                    <p>For general inquiries:</p>
                    <p><strong>Email:</strong> info@cebuplantdepot.com</p>
                    <p>For business partnerships:</p>
                    <p><strong>Email:</strong> business@cebuplantdepot.com</p>
                </div>
            </div>
        </div>

        <div class="map-section">
            <div class="map-header">
                <h3><i class="fas fa-map-marked-alt"></i> Our Location</h3>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3925.234365209774!2d123.89631431526022!3d10.315058392649988!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDE4JzU0LjIiTiAxMjPCsDUzJzUyLjEiRQ!5e0!3m2!1sen!2sph!4v1620000000000!5m2!1sen!2sph" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>

    <section class="brand-section">
        <div class="brand-content">
            <img src="./Main/images/logolanding2.png" alt="Cebu Plant Depot Logo" style="filter: brightness(0) saturate(100%) invert(29%) sepia(15%) saturate(992%) hue-rotate(87deg) brightness(94%) contrast(87%);">
            <p>"To plant a garden is to believe in tomorrow"</p>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>