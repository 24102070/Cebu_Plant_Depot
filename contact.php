<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cebu Plant Depot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
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
text-align: center;

}

*{

box-sizing: border-box;
margin: 0;
padding: 0;

}

body{

font-family: Arial, sans-serif;
background-color: #dad7cd;
color: #333;

}

.container{

display: flex;
flex-wrap: wrap;
min-height: 100vh;
margin-top: 20px;
margin-bottom: 20px;
border-radius: 50px;

}

.left-panel{

background-image: linear-gradient(to bottom right, #344e41, #3a5a40, #588157, #588157);
flex: 1;
min-width: 300px;
padding: 60px 30px;
display: flex;
flex-direction: column;
align-items: center;
justify-content: center;
text-align: center;
border-top-left-radius: 20px;
border-bottom-left-radius: 20px;
box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);

}

.left-panel img{

width: 100px;
margin-bottom: 20px;

}

.left-panel h1{

font-size: 28px;
color: #3e8e41;

}

.left-panel p{

font-size: 16px;
margin-top: 10px;
color: #555;

}

.right-panel{

flex: 2;
min-width: 400px;
padding: 40px 40px 60px;
background-color: #ffffff;
border-top-right-radius: 20px;
border-bottom-right-radius: 20px;
box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);

}

h2{

color: #3e8e41;
margin-bottom: 10px;

}

.section{

margin-bottom: 40px;

}

button{

background-color: #3e8e41;
color: white;
border: none;
padding: 12px 25px;
font-size: 16px;
border-radius: 8px;
cursor: pointer;
transition: background-color 0.3s ease;

}


iframe{

width: 100%;
height: 300px;
border: 0;
border-radius: 8px;

}

a{
      
color: #3e8e41;
text-decoration: none;

}

a:hover{

text-decoration: underline;

}

@media (max-width: 991px) {

    .container {flex-direction: column;}
    .right-panel {padding: 30px 20px; border-top-right-radius: 0px; border-bottom-left-radius: 20px; /*box-shadow: 0px -2px 30px black;*/}
    .left-panel {padding: 40px 20px; border-bottom-left-radius: 0px; border-top-right-radius: 20px; /*box-shadow: 0px 2px 30px black;*/}

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
            <div class="collapse navbar-collapse order-2 order-lg-1" id="navbarNav">
                <ul class="navbar-nav mx-auto">
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
                <a href="index.php"><img src="./images/logowhite.png" style="width: 8rem; padding: 5px;"></a>
            </div>
        </div>
    </nav>


<div class="container">
    <div class="left-panel">
        <img src="./images/logowhite.png" alt="Plant Logo" style="width: 300px; padding: 5px;">
        <p style='color: white; font-size: 20px;'>"To plant a garden is to believe in tomorrow"</p>
    </div>

    <div class="right-panel">
        <div class="section">
            <h2>📍 Visit Us</h2>
        <div class="info">
            <p><strong>Address:</strong> Cebu Plant Depot, Cebu City, Philippines</p>
            <p><strong>Hours:</strong> Mon–Sat: 8:00 AM – 6:00 PM</p>
            <p><strong>Sunday:</strong> Closed</p>
        </div>
    </div>

    <div class="section">
        <h2>📞 Contact Info</h2>
        <div class="info">
            <p><strong>Phone:</strong> 09639278793</p>
            <p><strong>Email:</strong> melodydiano@yahoo.com</p>
            <p><strong>Facebook:</strong> <a href="https://www.facebook.com/share/g/1Yw9N3MKAs/" target="_blank">Cebu Plant Depot</a></p>
        </div>
    </div>

    <div class="section">
        <h2>🗺️ Find Us on the Map</h2>
        <iframe src="https://www.google.com/maps?q=Cebu%20City&output=embed" allowfullscreenloading="lazy"></iframe>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>