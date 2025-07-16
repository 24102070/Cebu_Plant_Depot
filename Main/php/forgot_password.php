<?php
session_start();
include("database.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 0) {
            $error = "No account found with that email address.";
        } else {
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_message'] = $message;

            // Redirect to manual_send.php for someone to process
            header("Location: manual_send.php");
            exit();
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="shortcut icon" href="../images/logonavwhite.png" type="image/png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(to right, #3a5a40, #588157);
      font-family: 'Segoe UI', sans-serif;
      color: #2d2d2d;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    .reset-container {
      background-color: #dad7cd;
      padding: 30px;
      border-radius: 15px;
      width: 100%;
      max-width: 500px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .reset-container h2 {
      margin-bottom: 20px;
      color: #3a5a40;
    }
    .form-control {
      background-color: #f0f0f0;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    .btn-reset {
      background-color: #3a5a40;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 10px;
      font-weight: 600;
    }
    .btn-reset:hover {
      background-color: #6a994e;
    }
    .error-text {
      color: red;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="reset-container">
    <h2>Forgot Password</h2>

    <?php if ($error): ?>
      <div class="error-text mb-3"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="forgot_password.php">
      <label for="email">Registered Email Address</label>
      <input type="email" name="email" class="form-control" required>

      <label for="message">Additional Message (Optional)</label>
      <textarea name="message" class="form-control" rows="3" placeholder="E.g., I forgot my password. Please assist me."></textarea>

      <button type="submit" class="btn btn-reset mt-3 w-100">Send Reset Request</button>
    </form>

    <div class="mt-3">
      <a href="index.php" style="color:#3a5a40; text-decoration: none;">← Back to Login</a>
    </div>
  </div>
</body>
</html>
