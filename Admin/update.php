<?php
include("../Main/php/database.php");
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Main/php/index.php");
    exit();
}


if (!isset($_GET['id'])) {
    header("Location: main.php");
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product info
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);


if (!$product) {
    header("Location: main.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['product_name']);
    $price = floatval($_POST['product_price']);
    $availability = intval($_POST['product_availability']);
    $quantity = intval($_POST['product_quantity']);

    
    $newImage = $product['product_image'];
    if (!empty($_FILES['product_image']['name'])) {
        $imgName = $_FILES['product_image']['name'];
        $imgTmp = $_FILES['product_image']['tmp_name'];
        $uploadPath = "../images/" . basename($imgName);
        move_uploaded_file($imgTmp, $uploadPath);
        $newImage = $imgName;
    }

   
    $sql = "UPDATE products 
            SET product_name = ?, product_image = ?, product_price = ?, product_availability = ?, product_quantity = ?
            WHERE product_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssdsii", $name, $newImage, $price, $availability, $quantity, $product_id);
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
  <title>Edit Product</title>
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
        <h1>Edit Product</h1>
        <div class="form-floating">
          <input type="text" class="form-control" name="product_name" id="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
          <label for="product_name">Product Name</label>
        </div>
        <div class="form-floating">
          <input type="file" class="form-control" name="product_image" id="product_image">
          <label for="product_image">New Product Image (optional)</label>
        </div>
        <small class="text-muted">Current image: <?php echo htmlspecialchars($product['product_image']); ?></small>
        <div class="form-floating mt-3">
          <input type="number" step="0.01" class="form-control" name="product_price" id="product_price" value="<?php echo $product['product_price']; ?>" required>
          <label for="product_price">Price (₱)</label>
        </div>
        <div class="form-floating">
          <input type="number" class="form-control" name="product_quantity" id="product_quantity" value="<?php echo $product['product_quantity']; ?>" required>
          <label for="product_quantity">Quantity</label>
        </div>
        <div class="form-floating my-3">
            <select class="form-select" name="product_availability" id="product_availability" required>
                <option value="1" <?php if ($product['product_availability'] == 1) echo 'selected'; ?>>Available</option>
                <option value="0" <?php if ($product['product_availability'] == 0) echo 'selected'; ?>>Not Available</option>
            </select>
            <label for="product_availability">Availability Status</label>
        </div>

        <button type="submit" id="submitbtn">Update Product</button>
      </form>
    </div>
  </div>
</body>
</html>
