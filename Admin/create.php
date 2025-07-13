<?php
include("../Main/php/database.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Main/php/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['product_name']);
    $price = floatval($_POST['product_price']);
    $availability = isset($_POST['product_availability']) ? 1 : 0;
    $quantity = intval($_POST['product_quantity']);

    $imgName = $_FILES['product_image']['name'];
    $imgTmp = $_FILES['product_image']['tmp_name'];
    $uploadPath = "../images/" . basename($imgName);
    move_uploaded_file($imgTmp, $uploadPath);

    $sql = "INSERT INTO products (product_name, product_image, product_price, product_availability, product_quantity)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssdii", $name, $imgName, $price, $availability, $quantity);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: product_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* HIDE ALL SCROLLBARS */
    ::-webkit-scrollbar { display: none; }
    * { 
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    body {
      background: linear-gradient(to right, #3a5a40, #588157);
      font-family: 'Segoe UI', sans-serif;
    }
    
    .main {
      display: flex;
      min-height: 100vh;
      padding: 20px;
    }
    
    .sidebar {
      width: 250px;
      background-color: #344e41;
      padding: 20px;
      border-radius: 10px;
      color: white;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
    }
    .sidebar-header {
      display: flex;
      align-items: center;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      margin-bottom: 20px;
    }
    
    .sidebar-nav {
      flex-grow: 1;
    }
    
    .sidebar a {
      display: flex;
      align-items: center;
      padding: 12px 15px;
      margin: 8px 0;
      border-radius: 8px;
      color: white;
      text-decoration: none;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .sidebar a:hover {
      background-color: #8ab372ff;
    }

    .sidebar a.create-btn {
      background-color: #8ab372ff;
    }
    .sidebar a i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .sidebar .sidebar-logo a{
      border: none;
    }
    .sidebar .sidebar-logo a:hover{
      border: none;
      background-color: transparent;
    }
    
    .logout-container {
      margin-top: auto;
      padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.1);
    }
    
    /* Content */
    .content {
      flex-grow: 1;
      background-color: #dad7cd;
      margin-left: 20px;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    
    /* Welcome Header */
    .welcome-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    
    .welcome-message h2 {
      margin-bottom: 5px;
      color: #344e41;
    }
    
    .welcome-message p {
      color: #6c757d;
      margin: 0;
    }
    
    .logout-btn {
      background-color: #0f5132;
      border: none;
      padding: 8px 15px;
      border-radius: 5px;
      display: flex;
      align-items: center;
    }
    
    .logout-btn:hover {
      background-color: #66a67eff;
    }
    
    .logout-btn i {
      margin-right: 5px;
    }
    
    .form-container {
      background-color: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .form-title {
      color: #344e41;
      margin-bottom: 30px;
      font-weight: 600;
    }
    
    .form-floating {
      margin-bottom: 20px;
    }
    
    .form-control, .form-select {
      border: 1px solid #ced4da;
      border-radius: 8px;
      padding: 12px 15px;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: #588157;
    }
    
    #submitbtn {
      background-color: #3a5a40;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
      width: 100%;
      max-width: 300px;
      margin-top: 20px;
    }
    
    #submitbtn:hover {
      background-color: #588157;
      transform: translateY(-2px);
    }
    
  </style>
</head>
<body>
<div class="main">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <div class="sidebar-logo">
        <a href="main.php"><img src="../images/logowhite.png" style="width: 8rem; padding: 5px;" class="img-fluid"></a>
      </div>
    </div>
    
    <div class="sidebar-nav">
      <a href="main.php" class="dashboard-btn"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="product_list.php" class="product-btn"><i class="fas fa-list"></i> Product List</a>
      <a href="create.php" class="create-btn"><i class="fas fa-plus-circle"></i> Add Product</a>
      <a href="orders.php" class="orders-btn"><i class="fas fa-shopping-cart"></i> Manage Orders</a>
    </div>
    
    <div class="logout-container">
      <a href="../index.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content">
    <!-- Welcome Header -->
    <div class="welcome-header">
      <div class="welcome-message">
        <h2><i class="fas fa-plus-circle"></i> Add New Product</h2>
        <p>Fill out the form to add a new plant to your inventory</p>
      </div>
      <a href="../index.php" class="btn logout-btn text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Add Product Form -->
    <div class="form-container">
      <form method="POST" enctype="multipart/form-data" class="text-center">
        <h3 class="form-title">Product Details</h3>
        
        <div class="form-floating">
          <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product Name" required>
          <label for="product_name">Product Name</label>
        </div>
        
        <div class="form-floating">
          <input type="file" class="form-control" name="product_image" id="product_image" required>
          <label for="product_image">Product Image</label>
        </div>
        
        <div class="form-floating">
          <input type="number" step="0.01" class="form-control" name="product_price" id="product_price" placeholder="Price" required>
          <label for="product_price">Price (₱)</label>
        </div>
        
        <div class="form-floating">
          <input type="number" class="form-control" name="product_quantity" id="product_quantity" placeholder="Quantity" required>
          <label for="product_quantity">Quantity</label>
        </div>
        
        <div class="form-floating">
          <select class="form-select" name="product_availability" id="product_availability" required>
            <option value="1" selected>Available</option>
            <option value="0">Not Available</option>
          </select>
          <label for="product_availability">Availability Status</label>
        </div>
        
        <button type="submit" id="submitbtn" class="btn">
          <i class="fas fa-save"></i> Add Product
        </button>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>