<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cebu Plant Depot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap.css">
</head>
<style>
  ::-webkit-scrollbar { display: none; } 
  * { 
    -ms-overflow-style: none;  
    scrollbar-width: none;     
  }
body{

background-color: #dad7cd;
overflow-x: hidden;
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

}
.card{

cursor: pointer;
    transition: 0.2s ease-in-out;

}
.card:hover{

transform: translateY(-5px);

}
.container{

position: relative;
height: 500px;
width: 100%;
overflow: hidden;

}

.elements{
    
position: absolute;
height: auto;
width: 250px;
transition: 0.4s ease-in-out;
left: calc(50% - 110px);
top: 0;
text-align: center;

}

.elements img {

width: 100%;
height: 100%;
display: block;

}

#next, #prev{

position: absolute;
top: 50%;
transform: translateY(-50%);
background-color: transparent;
border-style: none;
font-size: 5rem;
color: white;
cursor: pointer;
z-index: 10;

}

#prev{

left: 5%;

}

#next{

right: 5%;

}
</style>
<body>
  
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #3a5a40;">
        <div class="container-fluid">
            <div class="d-flex order-lg-3 position-static position-lg-absolute end-0 me-3">
                <a href="#" class="nav-link text-white me-3">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a href="./Main/php/index.php" class="nav-link text-white">Login</a>
            </div>
            <button class="navbar-toggler order-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse order-2 order-lg-1" id="navbarNav" style="align-content: left;">
                <ul class="navbar-nav mx-auto ">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="aboutus.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="contact.php">Contact Us</a>
                    </li>
                </ul>
            </div>
            <div>
                <a href="index.php"><img src="./images/logowhite.png" style="width: 8rem; padding: 5px;" class="img-fluid"></a>
            </div>
        </div>
    </nav>

    <section class="landing_p d-flex flex-column flex-md-row align-items-center justify-content-md-center justify-content-sm-center text-justify">
        <div class="col-md-6 d-flex flex-column align-items-center me-5 mx-5 mt-5 mb-5">  
            <div class="">
                <img src="./images/logo.png" class="img-fluid mb-5" style="height: auto; width: 30rem;"><br>
                <a href="./Main/php/index.php" class="btn btn-lg active" style="background-color: #588157; border-color: #588157; color: white;">Shop Now</a>
            </div>
        </div>
        <div class="col-md-6 me-5 d-none d-lg-block">
            <img src="./images/landing.jpg" class="img-fluid w-100" style="height: auto; border-radius: 5px;">
        </div>
    </section>

    <section class="mt-5 p-4" style="background-image: linear-gradient(to bottom right, #344e41, #3a5a40, #588157, #588157);">
        <div class="text-center mt-3">
            <p class="display-2 text-center" style="color: #ffffff; font-weight: 400;">New Arrivals</p>
        </div>
        <div class="container d-flex justify-content-center mb-3 mt-3 position-relative" style="overflow: hidden;">
            <div class="elements card p-3 ms-2 me-2 mt-2" style="width: 15rem;">
                <img src="./images/1.jpg" class="card-img-top" alt=".">
                <div class="card-body">
                  <p class="card-text">Ruby<br>Php 350.00</p>
                </div>
              </div>
              <div class="elements card p-3 ms-2 me-2 mt-2" style="width: 15rem;">
                <img src="./images/2.jpg" class="card-img-top" alt=".">
                <div class="card-body">
                  <p class="card-text">Tineke<br>Php 350.00</p>
                </div>
              </div>
              <div class="elements card p-3 ms-2 me-2 mt-2" style="width: 15rem;">
                <img src="./images/3.jpg" class="card-img-top" alt=".">
                <div class="card-body">
                  <p class="card-text">Lemon Lime<br>Php 450</p>
                </div>
              </div>
              <div class="elements card p-3 ms-2 me-2 mt-2" style="width: 15rem;">
                <img src="./images/4.jpg" class="card-img-top" alt=".">
                <div class="card-body">
                  <p class="card-text">Moonshine<br>Php 350</p>
                </div>
              </div>
              <div class="elements card p-3 ms-1 me-1 mt-2" style="width: 15rem;">
                <img src="./images/5.jpg" class="card-img-top" alt=".">
                <div class="card-body">
                  <p class="card-text">Black Prince<br>Php 350.00</p>
                </div>
            </div>
            <div class="elements card p-3 ms-1 me-1 mt-2" style="width: 15rem;">
                <img src="./images/6.jpg" class="card-img-top" alt=".">
                <div class="card-body">
                  <p class="card-text">Blechnum Fern<br>Php 650.00</p>
                </div>
            </div>
            <div class="elements card p-3 ms-1 me-1 mt-2" style="width: 15rem;">
                <img src="./images/7.jpg" class="card-img-top" alt=".">
                <div class="card-body">
                  <p class="card-text">Kenzui Fern<br>Php 300.00</p>
                </div>
            </div>
            <div class="elements card p-3 ms-1 me-1 mt-2" style="width: 1   5rem;">
                <img src="./images/8.jpg" class="card-img-top" alt=".">
                <div class="card-body">
                  <p class="card-text">Lace Fern<br>Php 200.00</p>
                </div>
            </div>

                <button id="next">></button>
                <button id="prev"><</button>
        </div>
    </section>

    <section class="mt-5 p-4" style="background-image: linear-gradient(to bottom right, #344e41, #3a5a40, #588157, #588157);">
        <div class="d-flex">
            <div class="d-none d-lg-block">
                <img src="./images/placeholder.jpg" class="" style="height: auto; width: 25rem; border-radius: 10px;">
            </div>
            <div class="mt-3 ms-5 me-3 mb-5">
                <p class="display-6 mt-5 text-white" style="font-weight: bold;">"TO PLANT A GARDEN IS TO BELIEVE IN TOMORROW"</p>
                <p class="text-white" style="font-size: 2rem;">Looking to add a little life to your home or workspace? A plant is the perfect start. Whether you’re going for calm and cozy or fresh and vibrant, the right plant can transform any space. At Cebu Plant Depot, we make it easy to find healthy, beautiful plants that are ready to grow with you. Start your plant journey today — your green space is just one plant away!  </p>
                <a href="./Main/php/index.php" class="btn btn-lg active" style="background-color: #dad7cd; border-color: #dad7cd; color: #3a5a40;">Shop Now</a>
            </div>
        </div>
    </section>

    <footer>
      <div class="footer text-center p-2">
        <p class="fw-bold mt-3">© 2025 Cebu Plant Depot.</p>
      </div>
    </footer>

    <script src="./js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
