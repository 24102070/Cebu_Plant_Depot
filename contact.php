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

body{

background-color: #dad7cd;
overflow-x: hidden;
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

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
                <a href="home.php"><img src="./images/logowhite.png" style="width: 8rem; padding: 5px;"></a>
            </div>
        </div>
    </nav>

    <section class="mt-5">
        <p class="display-5 text-center">We Would Love to Hear From You!</p>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
                    <div class="d-flex flex-lg-row flex-column gap-3">
                        <div class="form-floating flex-grow-1">
                            <input type="text" class="form-control" name="name" id="floatingInput" placeholder="Eg. Juan Dela L. Cruz">
                            <label for="floatingInput">Name</label>
                        </div>
                        <div class="form-floating flex-grow-1">
                            <input type="email" class="form-control" name="email" id="floatingEmail" placeholder="name@example.com">
                            <label for="floatingEmail">Email</label>
                        </div>
                    </div>
                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="floatingMessage" name="content" placeholder="Message.." style="height: 150px"></textarea>
                        <label for="floatingMessage">Message</label>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" name="submit" value="Submit" class="bg-success fw-bold" style="color: white; padding: 10px; border-radius: 10px; border-style: none;">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <br><br><br>
    <section class="mt-3">
        <h1 class="display-5 text-center">You Can Also Contact us by:</h1>
        <div class="text-center">
            <div>
                <p style="font-size: 1.5rem;">Phone Number: 09639278793</p>
                <p style="font-size: 1.5rem;">Facebook: <a href="https://www.facebook.com/share/g/1Yw9N3MKAs/" target="_blank" style="color: black;">Cebu Plant Depot</a></p>
            </div>
        </div>
    </section>
    <br><br><br>

    <footer>
      <div class="footer text-center p-2">
        <p class="fw-bold mt-3">© 2025 Cebu Plant Depot.</p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST"){

$email = FILTER_INPUT(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$name = FILTER_INPUT(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
$content = FILTER_INPUT(INPUT_POST, "content", FILTER_SANITIZE_SPECIAL_CHARS);

// echo "$name $email $content";

if(filter_var($email, FILTER_VALIDATE_EMAIL)){

  $to = "dustin.jb09@gmail.com";
  $subject = "Website Feedback";
  $message = "From: $name\n\nMessage:\n$content";
  $headers = "From: $email";

  mail($to, $subject, $message, $headers);

}

}

?>
