<?php
session_start();
include("database.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fname = filter_input(INPUT_POST, "fname", FILTER_SANITIZE_SPECIAL_CHARS);
  $lname = filter_input(INPUT_POST, "lname", FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_UNSAFE_RAW);
  $phoneNumber = filter_input(INPUT_POST, "phoneNumber", FILTER_SANITIZE_SPECIAL_CHARS);
  $address = filter_input(INPUT_POST, "address", FILTER_SANITIZE_SPECIAL_CHARS);

  if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
    $error = "Please fill out all fields.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters.";
  } else {
    // Check if email already exists
    $checkSql = "SELECT email FROM users WHERE email = ?";
    $checkStmt = mysqli_prepare($con, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
      $error = "Email has already been registered!";
    } else {
      // Hash password and insert user
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
      $role = "customer";

      $insertSql = "INSERT INTO users (fname, lname, email, password, role, phoneNumber, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $insertStmt = mysqli_prepare($con, $insertSql);
      mysqli_stmt_bind_param($insertStmt, "sssssss", $fname, $lname, $email, $hashedPassword, $role, $phoneNumber, $address);

      if (mysqli_stmt_execute($insertStmt)) {
        header("Location: index.php"); // Redirect to login
        exit;
      } else {
        $error = "Registration failed. Try again later.";
      }

      mysqli_stmt_close($insertStmt);
    }

    mysqli_stmt_close($checkStmt);
  }

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

    #error {
      color: red;
      font-size: 1rem;
    }
  </style>
</head>
<body>

  <div class="main">
    <div class="container-custom row">
      <div class="col-12 col-md-6 form-section">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
          <div class="form-floating">
            <input type="text" class="form-control" name="fname" id="firstName" placeholder="Eg. Juan" required>
            <label for="firstName">First Name</label>
          </div>
          <div class="form-floating">
            <input type="text" class="form-control" name="lname" id="lastName" placeholder="Eg. Dela Cruz" required>
            <label for="lastName">Last Name</label>
          </div>
           <div class="form-floating">
            <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" placeholder="Eg. 09287364721" required>
            <label for="phoneNumber">Phone Number</label>
          </div>
           <div class="form-floating">
            <input type="text" class="form-control" name="address" id="address" placeholder="Eg. Skibidi St., Consolacion" required>
            <label for="address">Address</label>
          </div>
          <div class="form-floating">
            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
            <label for="email">Email</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required minlength="8">
            <label for="password">Password</label>
          </div>
          <div class="login">
            <button id="loginbtn" type="submit">Sign Up</button>
            <small>Already have an account? <a href="index.php" id="signupbtn">Login</a></small>
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
</html>
