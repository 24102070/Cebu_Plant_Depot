<?php
session_start();
include("database.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $row['password'])) {

      $_SESSION["user_id"] = $row['id'];
      $_SESSION["email"] = $row['email'];
      $_SESSION["role"] = $row['role'];
      $_SESSION["fname"] = $row['fname'];

      if ($row['role'] === 'admin') {
        header("Location: ../../Admin/main.php");
      } else {
        header("Location: catalogue.php");
      }
      exit;
    } else {
      $error = "Incorrect password.";
    }
  } else {
    $error = "Account not found.";
  }

  mysqli_stmt_close($stmt);
  mysqli_close($con);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cebu Plant Depot</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="shortcut icon" href="../images/logonavwhite.png" type="image/png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
      ::-webkit-scrollbar { display: none; } 
  * { 
    -ms-overflow-style: none;  
    scrollbar-width: none;     
  }
    body {
      background: linear-gradient(to right, #3a5a40, #588157);
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      color: #2d2d2d;
    }

    .main {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .container-custom {
      background-color: #dad7cd;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 900px;
    }

    .form-section {
      margin-bottom: 30px;
    }

    .form-floating > input {
      border-radius: 12px;
    }

    .form-floating + .form-floating {
      margin-top: 1rem;
    }

    .login {
      margin-top: 25px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    #loginbtn {
      background-color: #3a5a40;
      padding: 12px;
      border-radius: 10px;
      color: white;
      text-align: center;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease-in-out;
      border-style: none;
    }

    #loginbtn:hover {
      background-color: #6a994e;
      transform: scale(1.03);
    }

    #signupbtn {
      color: #3a5a40;
      text-decoration: none;
      font-weight: 500;
    }

    #signupbtn:hover {
      text-decoration: underline;
    }

    #logo {
      width: 100%;
      max-width: 400px;
    }

    @media (min-width: 768px) {
      .form-section {
        padding-right: 20px;
        border-right: 2px solid #a3b18a;
      }
    }

    @media (max-width: 767.98px) {
      .container-custom {
        padding: 20px;
      }

      #logo {
        margin-top: 30px;
      }

      .form-section {
        border: none;
        padding: 0;
      }
    }
    #error{

      color: red;
      font-size: 1rem;
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
      

  </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand me-lg-5" href="index.php">
                <img src="../images/logonavwhite.png" alt="Cebu Plant Depot Logo" class="img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="../../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../aboutus.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../contact.php">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </nav>

   <div class="main">
    <div class="container-custom row">
      <div class="col-12 col-md-6 form-section">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
          <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Eg. Juan Dela L. Cruz" required>
            <label for="floatingInput">Email</label>
          </div>
          <div class="form-floating position-relative">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Enter your password" required>
            <label for="floatingPassword">Password</label>
            <i class="bi bi-eye-slash-fill toggle-password" id="togglePassword" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
          </div>
          <small><a href="forgot_password.php" id="signupbtn">Forgot Password?</a></small>
          <div class="login">
            <button type="submit" id="loginbtn">Login</button>
            <small>Don't have an account? <a href="signup.php" id="signupbtn">Sign Up!</a></small>
        </div>
          <?php if (!empty($error)): ?>
            <div>
              <small id="error"><?php echo htmlspecialchars($error); ?></small>
            </div>
          <?php endif; ?>
        </form>
      </div>
      <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
        <img class="img-fluid" src="../images/logolanding2.png" alt="Cebu Plant Depot Logo" id="logo" style="padding: 20px;">
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const togglePassword = document.getElementById('togglePassword');
      const password = document.getElementById('floatingPassword');
      togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('bi-eye-fill');
        this.classList.toggle('bi-eye-slash-fill');
      });
    });
  </script>
</body>
</body>
</html>