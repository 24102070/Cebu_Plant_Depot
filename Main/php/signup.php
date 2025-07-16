<?php
session_start();
include("database.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fname = filter_input(INPUT_POST, "fname", FILTER_SANITIZE_SPECIAL_CHARS);
  $lname = filter_input(INPUT_POST, "lname", FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_UNSAFE_RAW);
  $confirm_password = filter_input(INPUT_POST, "confirm_password", FILTER_UNSAFE_RAW);
  $phoneNumber = filter_input(INPUT_POST, "phoneNumber", FILTER_SANITIZE_SPECIAL_CHARS);
  $address = filter_input(INPUT_POST, "address", FILTER_SANITIZE_SPECIAL_CHARS);

  if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($confirm_password)) {
    $error = "Please fill out all fields.";
  } elseif ($password !== $confirm_password) {
    $error = "Passwords do not match.";
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


.captcha-modal {
  display: none; 
  position: fixed; 
  top: 0; 
  left: 0; 
  width: 100%; 
  height: 100%; 
  background-color: rgba(0,0,0,0.7); 
  z-index: 1000; 
  justify-content: center; 
  align-items: center;
}

.captcha-form {
  background-color: white; 
  padding: 30px; 
  border-radius: 10px; 
  width: 90%; 
  max-width: 500px;
  min-height: 300px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.captcha-form .preview {
  background-color: #f8f9fa; 
  padding: 20px;
  text-align: center; 
  margin-bottom: 20px; 
  border-radius: 5px;
  height: 90px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem; 
}

.captcha-error {
  color: red; 
  display: none; 
  margin-bottom: 20px; 
  text-align: center;
  font-size: 1.1rem;
}

#captcha-submit-btn {
  background-color: #3a5a40; 
  border-color: #3a5a40;
  padding: 10px 20px;
  font-size: 1.1rem;
  transition: all 0.3s;
}

#captcha-submit-btn:hover {
  background-color: #588157; 
  border-color: #588157;
  transform: scale(1.02);
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="signup-form">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-floating">
                <input type="text" class="form-control" name="fname" id="firstName" placeholder="Eg. Juan" required>
                <label for="firstName">First Name</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <input type="text" class="form-control" name="lname" id="lastName" placeholder="Eg. Dela Cruz" required>
                <label for="lastName">Last Name</label>
              </div>
            </div>
          </div>
          <div class="form-floating mt-3">
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
          <div class="form-floating position-relative">
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required minlength="8">
            <label for="password">Password</label>
            <i class="bi bi-eye-slash-fill toggle-password" id="togglePassword" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
          </div>
          <div class="form-floating position-relative">
            <input type="password" class="form-control" name="confirm_password" id="confirmPassword" placeholder="Confirm your password" required minlength="8">
            <label for="confirmPassword">Confirm Password</label>
            <i class="bi bi-eye-slash-fill toggle-password" id="toggleConfirmPassword" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
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
        <img class="img-fluid" src="../images/logolanding2.png" alt="Cebu Plant Depot Logo" id="logo" style="padding: 20px;">
      </div>
    </div>
  </div>

  
  <div class="captcha-modal">
    <div class="captcha-form">
      <h4 style="margin-bottom: 20px; text-align: center;">Verify you're human</h4>
      <div class="preview"></div>
      <div style="display: flex; gap: 10px; margin-bottom: 15px;">
        <input type="text" id="captchaInput" class="form-control" placeholder="Enter CAPTCHA text" style="flex-grow: 1;">
        <button id="captcha-submit-btn" class="btn btn-primary" style="white-space: nowrap;">Verify</button>
      </div>
      <div class="captcha-error">
        Incorrect CAPTCHA. Please try again.
      </div>
      <input type="hidden" id="captcha-verified" value="false">
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Password toggle functionality
      const togglePassword = document.getElementById('togglePassword');
      const password = document.getElementById('password');
      togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('bi-eye-fill');
        this.classList.toggle('bi-eye-slash-fill');
      });

      const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
      const confirmPassword = document.getElementById('confirmPassword');
      toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        this.classList.toggle('bi-eye-fill');
        this.classList.toggle('bi-eye-slash-fill');
      });

      // CAPTCHA functionality
      const form = document.getElementById('signup-form');
      const captchaModal = document.querySelector('.captcha-modal');
      const captchaInput = document.getElementById('captchaInput');
      const captchaSubmitBtn = document.getElementById('captcha-submit-btn');
      const captchaError = document.querySelector('.captcha-error');
      const captchaVerified = document.getElementById('captcha-verified');

      let currentCaptcha = generateCaptcha();
      renderCaptcha(currentCaptcha);

      // Refresh only
      captchaSubmitBtn.addEventListener('click', () => {
        if (captchaInput.value.trim() === '') {
          // Refresh the CAPTCHA
          currentCaptcha = generateCaptcha();
          renderCaptcha(currentCaptcha);
          captchaInput.value = '';
          captchaError.style.display = 'none';
        } else {
          validateCaptcha();
        }
      });

      // Validate on Enter key
      captchaInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          validateCaptcha();
        }
      });

      // Handle Sign Up click or Submit
      form.addEventListener('submit', function (e) {
        if (captchaVerified.value !== 'true') {
          e.preventDefault();

          // Basic field check (form is mostly filled)
          const requiredFields = ['firstName', 'lastName', 'email', 'password', 'confirmPassword'];
          let allFieldsFilled = true;
          for (let id of requiredFields) {
            if (!document.getElementById(id).value.trim()) {
              allFieldsFilled = false;
              break;
            }
          }

          if (allFieldsFilled) {
            captchaModal.style.display = 'flex';
          }
        }
      });

      function validateCaptcha() {
        if (captchaInput.value.trim().toUpperCase() === currentCaptcha) {
          captchaVerified.value = 'true';
          captchaModal.style.display = 'none';
          captchaError.style.display = 'none';
          form.submit(); // re-trigger actual form submit
        } else {
          captchaError.style.display = 'block';
          captchaInput.value = '';
          currentCaptcha = generateCaptcha();
          renderCaptcha(currentCaptcha);
        }
      }

      // CAPTCHA generation functions
      function generateCaptcha(length = 5) {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        let captcha = '';
        for (let i = 0; i < length; i++) {
          captcha += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return captcha;
      }

      function renderCaptcha(captcha) {
        const preview = document.querySelector('.captcha-form .preview');
        preview.innerHTML = '';
        preview.style.position = 'relative';  // for absolute positioning of noise

        const noiseCount = 50; 
        for (let i = 0; i < noiseCount; i++) {
          const dot = document.createElement('span');
          dot.style.position = 'absolute';
          dot.style.width = '3px';
          dot.style.height = '3px';
          dot.style.borderRadius = '50%';
          dot.style.backgroundColor = `hsl(${Math.random() * 360}, 50%, 50%)`;
          dot.style.top = `${Math.random() * 40}px`; 
          dot.style.left = `${Math.random() * (preview.clientWidth || 200)}px`; 
          dot.style.opacity = '0.3';
          preview.appendChild(dot);
        }

        for (let char of captcha) {
          const span = document.createElement('span');
          span.textContent = char;

          // messy styles
          span.style.position = 'relative';
          span.style.zIndex = '1';
          span.style.transform = `
            rotate(${Math.floor(Math.random() * 60 - 30)}deg)
            translate(${Math.floor(Math.random() * 5 - 2)}px, ${Math.floor(Math.random() * 5 - 2)}px)
          `;
          span.style.fontSize = `${Math.floor(Math.random() * 8 + 18)}px`;
          span.style.margin = `${Math.floor(Math.random() * 6)}px ${Math.floor(Math.random() * 6)}px`;
          span.style.fontWeight = ['normal', 'bold', 'bolder', 'lighter'][Math.floor(Math.random() * 4)];
          span.style.fontStyle = Math.random() > 0.5 ? 'italic' : 'normal';
          span.style.letterSpacing = `${Math.floor(Math.random() * 4 - 2)}px`;
          span.style.color = `hsl(${Math.random() * 360}, ${50 + Math.random() * 50}%, ${30 + Math.random() * 30}%)`;
          span.style.display = 'inline-block';

          preview.appendChild(span);
        }
      }
    });
  </script>
</body>
</html>
