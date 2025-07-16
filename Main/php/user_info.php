<?php
include("database.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get user information from database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found");
}

// Handle form submission for profile update (without fname/lname)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $phoneNumber = mysqli_real_escape_string($con, $_POST['phoneNumber']);
    $address = mysqli_real_escape_string($con, $_POST['address']);

    $update_query = "UPDATE users SET phoneNumber = ?, address = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ssi", $phoneNumber, $address, $user_id);

    if (mysqli_stmt_execute($update_stmt)) {
        // Refresh user data
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Error updating profile: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Account - Cebu Plant Depot</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

  <style>
    ::-webkit-scrollbar { display: none; } 
    * { 
      -ms-overflow-style: none;  
      scrollbar-width: none;     
    }
    :root {
      --primary-green: #3a5a40;
      --secondary-green: #588157;
      --light-green: #a3b18a;
      --lighter-green: #dad7cd;
      --accent-yellow: #e6c229;
    }
    
    body {
      background-color: #f8f9fa;
      font-family: 'Inter';
      background-image: radial-gradient(circle at 10% 20%, rgba(218, 215, 205, 0.1) 0%, rgba(218, 215, 205, 0.2) 90%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    .main-content {
      flex: 1;
      padding-bottom: 100px; 
    }
    
    .navbar {
      background-color: var(--primary-green) !important;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
      padding: 0.8rem 1rem;
    }
    
    .account-header {
      text-align: center;
      padding: 40px 30px;
      background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
      border-radius: 16px;
      box-shadow: 0 10px 20px rgba(58, 90, 64, 0.15);
      margin-bottom: 40px;
      position: relative;
      overflow: hidden;
    }
    
    .account-header::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: var(--accent-yellow);
    }
    
    .account-header h2 {
      color: white;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 15px;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
      position: relative;
      display: inline-block;
    }
    
    .account-header h2::after {
      content: "";
      display: block;
      width: 60px;
      height: 3px;
      background: var(--accent-yellow);
      margin: 10px auto 0;
    }
    
    .account-header .leaf-decoration {
      position: absolute;
      opacity: 0.1;
    }
    
    .account-header .leaf-1 {
      top: 20px;
      left: 30px;
      transform: rotate(-15deg);
    }
    
    .account-header .leaf-2 {
      bottom: 20px;
      right: 30px;
      transform: rotate(15deg);
    }
    
    .account-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    .account-card {
      background-color: white;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      position: relative;
      margin-bottom: 40px;
    }
    
    .account-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: var(--light-green);
    }
    
    .account-info p {
      margin-bottom: 15px;
      font-size: 1.05rem;
    }
    
    .account-info strong {
      color: var(--primary-green);
      min-width: 120px;
      display: inline-block;
    }
    
    .btn-success {
      background-color: var(--secondary-green);
      border-color: var(--secondary-green);
      border-radius: 10px;
      padding: 8px 15px;
      font-weight: 500;
      transition: all 0.3s;
    }
    
    .btn-success:hover {
      background-color: var(--primary-green);
      border-color: var(--primary-green);
      transform: translateY(-2px);
    }
    
    .nav-button-container {
      display: inline-block;
      margin-left: 15px;
      padding: 8px 15px;
      border-radius: 10px;
      transition: all 0.3s ease;
      background-color: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .nav-button-container:hover {
      transform: translateY(-3px);
      background-color: var(--accent-yellow);
      box-shadow: 0 5px 15px rgba(230, 194, 41, 0.3);
    }
    
    .nav-button-container a {
      text-decoration: none;
      color: white !important;
      font-weight: 500;
    }
    
    footer {
      background-color: var(--primary-green);
      color: white;
      padding: 20px 0;
      margin-top: auto;
      position: relative;
      z-index: 100;
    }
    
    /* Modal styles */
    .modal-header {
      background-color: var(--primary-green);
      color: white;
      border-bottom: none;
    }
    
    .modal-header .btn-close {
      filter: invert(1);
    }
    
    .modal-footer {
      border-top: none;
    }
    
    @media (max-width: 767.98px) {
      .account-header h2 {
        font-size: 2rem;
      }
      
      .account-info strong {
        min-width: 100px;
      }
      
      .nav-button-container {
        margin-left: 8px;
        padding: 6px 10px;
      }
    }
  </style>
</head>
<body>
  <div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container-fluid">
        <div class="d-flex order-lg-3 position-static position-lg-absolute end-0 me-3 align-items-center">
          <div class="nav-button-container">
            <a href="catalogue.php" class="nav-link">
            <i class="fas fa-arrow-left"></i> <span class="d-none d-md-inline">Back to Shop</span>
            </a>
          </div>
          <div class="nav-button-container">
            <a href="order_history.php" class="nav-link">
              <i class="fas fa-clock-rotate-left"></i> <span class="d-none d-md-inline">Order History</span>
            </a>
          </div>
          <div class="nav-button-container">
            <a href="logout.php" class="nav-link">
              <i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span>
            </a>
          </div>
        </div>
        <button class="navbar-toggler order-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse order-2 order-lg-1" id="navbarNav">
          <ul class="navbar-nav mx-auto">
            <li class="nav-item"><a class="nav-link text-white fw-bold" href="catalogue.php">Shop</a></li>
          </ul>
        </div>
        <div>
          <img src="../images/logonavwhite.png" alt="Cebu Plant Depot Logo" class="img-fluid" style="height: 3.5rem; width: auto;">
        </div>
      </div>
    </nav>

    <div class="account-container">
      <section class="mt-5 mb-5 mx-3 account-header">
        <i class="fas fa-leaf leaf-decoration leaf-1 fa-3x"></i>
        <i class="fas fa-leaf leaf-decoration leaf-2 fa-3x"></i>
        <h2>MY ACCOUNT</h2>
      </section>

      <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?php echo $success_message; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?php echo $error_message; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="account-card">
        <div class="account-info">
          <div class="row">
            <div class="col-md-6">
              <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['fname'] ?? 'Not provided'); ?></p>
              <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['lname'] ?? 'Not provided'); ?></p>
              <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'Not provided'); ?></p>
            </div>
            <div class="col-md-6">
              <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phoneNumber'] ?? 'Not provided'); ?></p>
              <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($user['address'] ?? 'Not provided')); ?></p>
              <p><strong>Account Type:</strong> <?php echo ucfirst($user['role'] ?? 'customer'); ?></p>
            </div>
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fas fa-edit me-1"></i> Edit Profile
          </button>
        </div>
      </div>
    </div>
  </div>

 <!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="">
        <div class="modal-body">
          <!-- REMOVE fname/lname fields -->
          <div class="mb-3">
            <label for="phoneNumber" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" 
                   value="<?php echo htmlspecialchars($user['phoneNumber'] ?? ''); ?>">
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"><?php 
              echo htmlspecialchars($user['address'] ?? ''); 
            ?></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="update_profile" class="btn btn-success">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


  <footer>
    <div class="footer text-center p-3">
      <p class="fw-bold mt-3">© 2025 Cebu Plant Depot. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Auto-focus on first input when modal opens
    document.getElementById('editProfileModal').addEventListener('shown.bs.modal', function () {
      document.getElementById('fname').focus();
    });

    // Close alerts after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  </script>
</body>
</html>