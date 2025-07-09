<?php
session_start();

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = htmlspecialchars($_SESSION['reset_email']);
$message = htmlspecialchars($_SESSION['reset_message'] ?? '');
$role = "Customer";

$admin_email = "rezuesan@gmail.com";

$subject_text = "Password Reset Request from $email";
$body_text = "A customer account with email $email has requested a password reset.\n\n";
if ($message) {
    $body_text .= "Message from user:\n$message\n\n";
}
$body_text .= "Please verify and assist with the password reset.";

$subject = urlencode($subject_text);
$body = urlencode($body_text);

$mailto_link = "mailto:$admin_email?subject=$subject&body=$body";
$gmail_link = "https://mail.google.com/mail/?view=cm&fs=1&to=$admin_email&su=$subject&body=$body";

// Clear session after generating
unset($_SESSION['reset_email'], $_SESSION['reset_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manual Reset Request</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #3a5a40, #588157);
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 40px 15px;
      color: #2d2d2d;
    }

    .container-reset {
      background-color: #dad7cd;
      max-width: 700px;
      margin: auto;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #3a5a40;
      margin-bottom: 20px;
    }

    a {
      color: #3a5a40;
      text-decoration: none;
      font-weight: 600;
    }

    a:hover {
      text-decoration: underline;
    }

    textarea {
      width: 100%;
      padding: 10px;
      font-size: 0.95rem;
      background-color: #f6f6f6;
      border: 1px solid #bbb;
      border-radius: 8px;
      resize: none;
      margin-bottom: 15px;
      font-family: 'Segoe UI', sans-serif;
      color: #333;
    }

    .btn-links a {
      display: inline-block;
      margin: 10px 10px 20px 0;
      padding: 10px 20px;
      background-color: #3a5a40;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .btn-links a:hover {
      background-color: #6a994e;
    }

    label {
      font-weight: 600;
    }

    .footer-links a {
      display: inline-block;
      margin-right: 15px;
      color: #3a5a40;
      font-weight: 500;
    }

    .footer-links a:hover {
      text-decoration: underline;
    }

    #sentNotification {
      display: none;
    }
  </style>
</head>
<body>

<div class="container-reset">
  <h2>Manual Password Reset Request</h2>

  <p>Your email <strong><?php echo $email; ?></strong> has been identified as a customer requesting a password reset.</p>

  <p>You can manually email the admin for reset instructions by using one of the buttons below:</p>

  <div class="btn-links">
    <a href="<?php echo $mailto_link; ?>" onclick="handleSendClick()">Open Mail App</a>
    <a href="<?php echo $gmail_link; ?>" target="_blank" onclick="handleSendClick()">Open Gmail</a>
  </div>

  <div id="sentNotification" class="alert alert-success text-center mt-3">
    Your email has been sent. You will be redirected to the login page in <strong><span id="countdown">20</span> seconds</strong>...
  </div>

  <p>If the buttons above do not work, manually copy the details below and send an email to <strong><?php echo $admin_email; ?></strong>:</p>

  <div class="mt-3">
    <label for="subject">Subject:</label>
    <textarea id="subject" rows="2" readonly><?php echo $subject_text; ?></textarea>

    <label for="body">Body:</label>
    <textarea id="body" rows="6" readonly><?php echo $body_text; ?></textarea>
  </div>

  <div class="footer-links mt-4">
    <a href="forgot_password.php">← Back to Forgot Password</a>
    <a href="index.php">Back to Login</a>
  </div>
</div>

<script>
  function handleSendClick() {
    document.getElementById("sentNotification").style.display = "block";

    document.querySelectorAll('.btn-links a').forEach(btn => {
      btn.onclick = null;
      btn.style.pointerEvents = "none";
      btn.style.opacity = "0.6";
    });

    let seconds = 40;
    const countdown = document.getElementById('countdown');
    const interval = setInterval(() => {
      seconds--;
      countdown.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(interval);
        window.location.href = "index.php";
      }
    }, 1000);
  }
</script>

</body>
</html>
