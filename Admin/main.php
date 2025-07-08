<?php
include("../Main/php/database.php");
session_start();

// Admin access control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #3a5a40, #588157);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main {
            display: flex;
            min-height: 100vh;
            padding: 30px;
        }
        .sidebar {
            width: 220px;
            background-color: #344e41;
            padding: 20px;
            border-radius: 10px;
            color: white;
            height: fit-content;
        }
        .sidebar a {
            display: block;
            padding: 10px 15px;
            margin: 10px 0;
            background-color: #588157;
            border-radius: 8px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #6a994e;
        }
        .content {
            flex-grow: 1;
            background-color: #dad7cd;
            margin-left: 30px;
            padding: 20px;
            border-radius: 10px;
        }
        .product-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.15) 0px 8px 16px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            border-radius: 8px;
            height: 150px;
            object-fit: cover;
        }
        .action-buttons a {
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="sidebar">
            <h4>Admin Panel</h4>
            <a href="create.php">Add Product</a>
            <a href="orders.php">Manage Orders</a>
            <a href="../index.php">Logout</a>
        </div>
        <div class="content">
            <h2 class="mb-4">Product Catalogue</h2>
            <div class="row">
                <?php
                $sql = "SELECT * FROM products ORDER BY product_quantity DESC";
                $result = mysqli_query($con, $sql);
                if (mysqli_num_rows($result) > 0):
                    while($row = mysqli_fetch_assoc($result)):
                ?>
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="../images/<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                        <h5 class="mt-2"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                        <p>₱<?php echo number_format($row['product_price'], 2); ?></p>
                        <p>Quantity: <?php echo $row['product_quantity']; ?></p>
                        <p>Status: <?php echo $row['product_availability'] ? 'Available' : 'Out of Stock'; ?></p>
                        <div class="action-buttons">
                            <a href="update.php?id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="remove.php?id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </div>
                    </div>
                </div>
                <?php
                    endwhile;
                else:
                ?>
                <p>No products found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
