<?php

include("database.php");

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
  <style>
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
  </style>
</head>
<body>

   <div class="main">
    <div class="container-custom row">
      <div class="col-12 col-md-6 form-section">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
          <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Eg. Juan Dela L. Cruz" required>
            <label for="floatingInput">Email</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Enter your password" required>
            <label for="floatingPassword">Password</label>
          </div>
          <div class="login">
            <button type="submit" id="loginbtn">Login</button>
            <small>Don't have an account? <a href="signup.php" id="signupbtn">Sign Up!</a></small>
            <small><a href="forgot_password.php" id="signupbtn">Forgot Password?</a></small>
        </div>
          <?php if (!empty($error)): ?>
            <div>
              <small id="error"><?php echo htmlspecialchars($error); ?></small>
            </div>
          <?php endif; ?>
        </form>
      </div>
      <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
        <img class="img-fluid" src="../images/logo.png" alt="Cebu Plant Depot Logo" id="logo">
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</body>
</html>

<?php
include("database.php");
session_start();

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
