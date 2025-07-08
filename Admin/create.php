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

    header("Location: main.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #3a5a40, #588157);
      text-align: center;
    }
    .main {
      display: flex;
      height: 100vh;
      align-items: center;
      justify-content: center;
      gap: 80px;
    }
    .container {
      background-color: #dad7cd;
      padding: 20px;
      border-radius: 10px;
      margin: 10px;
      gap: 20px;
      box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px,
                  rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
    }
    .form h1 {
      font-size: 3rem;
      margin-bottom: 30px;
    }
    #submitbtn {
      background-color: #3a5a40;
      padding: 15px;
      margin-top: 20px;
      text-align: center;
      border-radius: 10px;
      color: white;
      text-decoration: none;
      cursor: pointer;
      transition: 0.2s ease-in-out;
      border-style: none;
      width: 15rem;
      font-weight: bold;
    }
    #submitbtn:hover {
      background-color: #588157;
    }
    .form-floating {
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="main">
    <div class="container">
      <form method="POST" enctype="multipart/form-data" class="form">
        <h1>Add Product</h1>
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
        <button type="submit" id="submitbtn">Add Product</button>
      </form>
    </div>
  </div>
</body>
</html>
