<?php
include("../Main/php/database.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Handle product deletion form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $plant_name = filter_input(INPUT_POST, "plantname", FILTER_SANITIZE_SPECIAL_CHARS);
    $isFound = false;

    $sql = "SELECT product_name FROM products";
    $result = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        if (strtoupper($row['product_name']) == strtoupper($plant_name)) {
            $isFound = true;

            $delete = "DELETE FROM products WHERE UPPER(product_name) = ?";
            $deleteprod = $con->prepare($delete);
            $plant_name_upper = strtoupper($plant_name);
            $deleteprod->bind_param("s", $plant_name_upper);
            $deleteprod->execute();
            $deleteprod->close();

            break;
        }
    }

    if ($isFound) {
        echo "<script>alert('Plant has been deleted'); window.location.href='product_list.php';</script>";
        exit();
    } else {
        echo "<script>alert('No Plant Found!'); window.location.href='product_list.php';</script>";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $product_id = intval($_POST['product_id']);
    $name = trim($_POST['product_name']);
    $price = floatval($_POST['product_price']);
    $quantity = intval($_POST['product_quantity']);
    $availability = isset($_POST['product_availability']) ? intval($_POST['product_availability']) : 0;

    // Fetch current product image
    $sql = "SELECT product_image FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result_img = mysqli_stmt_get_result($stmt);
    $product_img_row = mysqli_fetch_assoc($result_img);
    mysqli_stmt_close($stmt);

    $newImage = $product_img_row['product_image'];
    if (!empty($_FILES['product_image']['name'])) {
        $imgName = $_FILES['product_image']['name'];
        $imgTmp = $_FILES['product_image']['tmp_name'];
        $uploadPath = "../images/" . basename($imgName);
        move_uploaded_file($imgTmp, $uploadPath);
        $newImage = $imgName;
    }

    $sql = "UPDATE products 
            SET product_name = ?, product_image = ?, product_price = ?, 
                product_availability = ?, product_quantity = ?
            WHERE product_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssdsii", $name, $newImage, $price, $availability, $quantity, $product_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: product_list.php");
    exit();
}


// Get filter values
$search = $_GET['search'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

$conditions = [];
if ($search !== '') {
    $conditions[] = "product_name LIKE '%" . mysqli_real_escape_string($con, $search) . "%'";
}
if (is_numeric($minPrice)) {
    $conditions[] = "product_price >= " . (float)$minPrice;
}
if (is_numeric($maxPrice)) {
    $conditions[] = "product_price <= " . (float)$maxPrice;
}

$whereClause = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";
$sql = "SELECT * FROM products $whereClause ORDER BY product_quantity DESC";
$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
    .sidebar a.product-btn {
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

    .content {
      flex-grow: 1;
      background-color: #dad7cd;
      margin-left: 20px;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      overflow-y: auto;
      max-height: calc(100vh - 40px);
    }
    .card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      border: none;
    }
    .card-header {
      background: transparent;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      padding-bottom: 15px;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
    }
    .card-header i {
      margin-right: 10px;
      color: #588157;
    }
    .stat-card {
      text-align: center;
      padding: 20px;
    }
    .stat-card h3 {
      font-size: 2rem;
      margin: 10px 0;
      color: #344e41;
    }
    .stat-card h5 {
      color: #6c757d;
      font-size: 1rem;
    }
    .stat-card .icon-wrapper {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: rgba(88,129,87,0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
    }
    .stat-card .icon-wrapper i {
      color: #588157;
      font-size: 1.2rem;
    }
    .low-stock {
      background-color: #fff3cd !important;
    }
    .out-of-stock {
      background-color: #f8d7da !important;
    }
    .in-stock {
      background-color: #d1e7dd !important;
    }
    .status-badge {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
    }
    .status-out {
      background-color: #f8d7da;
      color: #842029;
    }
    .status-low {
      background-color: #fff3cd;
      color: #664d03;
    }
    .status-good {
      background-color: #d1e7dd;
      color: #0f5132;
    }
    #monthlyChart {
      width: 100%;
      max-width: 600px;
      height: 300px;
      margin: auto;
    }
    .table-responsive {
      overflow-x: auto;
    }
    .table th {
      background-color: #344e41;
      color: white;
    }
    .table-hover tbody tr:hover {
      background-color: rgba(88,129,87,0.1);
    }
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
    .top-product-card {
      display: flex;
      align-items: center;
      padding: 15px;
    }
    .top-product-icon {
      width: 50px;
      height: 50px;
      background-color: rgba(88,129,87,0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
    }
    .top-product-icon i {
      color: #588157;
      font-size: 1.5rem;
    }
    .top-product-info h5 {
      margin-bottom: 5px;
      color: #344e41;
    }
    .top-product-info p {
      margin: 0;
      color: #6c757d;
      font-size: 0.9rem;
    }
    #monthlyChart {
      width: 100%;
      height: 300px;
      margin: auto;
    }
    .dashboard-grid {
      display: grid;
      grid-template-columns: 1fr 1fr; 
      gap: 10px;
    }
    .left-column {
      padding-right: 20px;
    }
    .right-column {
      padding-left: 10px;
    }
    .content::-webkit-scrollbar {
      display: none;
    }
    
    .content {
      -ms-overflow-style: none; 
      scrollbar-width: none; 
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
                <h2><i class="fas fa-seedling"></i> Product Management</h2>
                <p>Manage your plant inventory and listings</p>
            </div>
            <a href="../index.php" class="btn logout-btn text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <!-- Search & Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-filter"></i>
                <h5 class="mb-0">Filter Products</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search product name" value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="min_price" class="form-control" placeholder="Min Price" step="0.01" value="<?php echo htmlspecialchars($minPrice); ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="max_price" class="form-control" placeholder="Max Price" step="0.01" value="<?php echo htmlspecialchars($maxPrice); ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100"><i class="fas fa-filter"></i> Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="product_list.php" class="btn btn-secondary w-100"><i class="fas fa-sync-alt"></i> Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list"></i>
                <h5 class="mb-0">Product Catalogue</h5>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price (₱)</th>
                                    <th>Quantity</th>
                                    <th>Availability</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                  <tr data-product='<?php echo json_encode($row); ?>'>
                                      <td style="cursor: pointer;"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                      <td><?php echo number_format($row['product_price'], 2); ?></td>
                                      <td><?php echo $row['product_quantity']; ?></td>
                                      <td>
                                            <span class="status-badge
                                                <?php 
                                                    if ($row['product_quantity'] == 0) {
                                                        echo 'status-out';
                                                    } elseif ($row['product_availability'] == 0) {
                                                        echo 'status-out';
                                                    } else {
                                                        echo 'status-good';
                                                    }
                                                ?>">
                                                <?php 
                                                    if ($row['product_quantity'] == 0) {
                                                        echo 'Out of Stock';
                                                    } elseif ($row['product_availability'] == 0) {
                                                        echo 'Not Available';
                                                    } else {
                                                        echo 'Available';
                                                    }
                                                ?>
                                            </span>
                                        </td>

                                      <td>
                                          <button class="btn btn-sm btn-warning edit-btn" type="button"><i class="fas fa-edit"></i> Edit</button>
                                          <button class="btn btn-sm btn-danger delete-btn" type="button" data-product-name="<?php echo htmlspecialchars($row['product_name']); ?>">
                                              <i class="fas fa-trash"></i> Delete
                                          </button>
                                      </td>
                                  </tr>
                              <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No products found matching your criteria.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Edit Product Modal -->
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST" enctype="multipart/form-data" id="editProductForm">
                <div class="modal-header">
                  <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="product_name" id="product_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_image" class="form-label">New Product Image (optional)</label>
                        <input type="file" class="form-control" name="product_image" id="product_image">
                        <small class="form-text text-muted" id="currentImageText"></small>
                    </div>
                    <div class="mb-3">
                        <label for="product_price" class="form-label">Price (₱)</label>
                        <input type="number" step="0.01" class="form-control" name="product_price" id="product_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="product_quantity" id="product_quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_availability" class="form-label">Availability Status</label>
                        <select class="form-select" name="product_availability" id="product_availability" required>
                            <option value="1">Available</option>
                            <option value="0">Not Available</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Product Details Modal -->
        <div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-5 text-center">
                    <img id="detailProductImage" src="" alt="Product Image" class="img-fluid rounded" style="max-height: 300px;">
                  </div>
                  <div class="col-md-7">
                    <h4 id="detailProductName"></h4>
                    <p><strong>Price:</strong> ₱<span id="detailProductPrice"></span></p>
                    <p><strong>Quantity:</strong> <span id="detailProductQuantity"></span></p>
                    <p><strong>Availability:</strong> <span id="detailProductAvailability"></span></p>
                    <p><strong>Product ID:</strong> <span id="detailProductID"></span></p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="deleteForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete Plant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>To confirm deletion, please enter the plant name:</p>
        <input type="text" class="form-control" id="plantname" name="plantname" required>
        <input type="hidden" name="delete_product" value="1">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger">Remove Plant</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
        var detailsModal = new bootstrap.Modal(document.getElementById('productDetailsModal'));
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));

        var editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var tr = this.closest('tr');
                var product = JSON.parse(tr.getAttribute('data-product'));

                document.getElementById('product_id').value = product.product_id;
                document.getElementById('product_name').value = product.product_name;
                document.getElementById('product_price').value = product.product_price;
                document.getElementById('product_quantity').value = product.product_quantity;
                document.getElementById('product_availability').value = product.product_availability;
                document.getElementById('currentImageText').textContent = 'Current image: ' + product.product_image;

                // Clear file input
                document.getElementById('product_image').value = '';

                editModal.show();
            });
        });

        // Add click event listener on product name cells to open details modal
        var productNameCells = document.querySelectorAll('tbody tr td:first-child');
        productNameCells.forEach(function(cell) {
            cell.style.cursor = 'pointer';
            cell.addEventListener('click', function() {
                var tr = this.closest('tr');
                var product = JSON.parse(tr.getAttribute('data-product'));

                document.getElementById('detailProductImage').src = '../images/' + product.product_image;
                document.getElementById('detailProductName').textContent = product.product_name;
                document.getElementById('detailProductPrice').textContent = parseFloat(product.product_price).toFixed(2);
                document.getElementById('detailProductQuantity').textContent = product.product_quantity;
                document.getElementById('detailProductAvailability').textContent = product.product_availability ? 'Available' : 'Out of Stock';
                document.getElementById('detailProductID').textContent = product.product_id;

                detailsModal.show();
            });
        });

        // Delete button modal logic
        var deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var tr = this.closest('tr');
                var product = JSON.parse(tr.getAttribute('data-product'));
                var plantNameInput = document.getElementById('plantname');
                plantNameInput.value = ''; // Clear input
                plantNameInput.placeholder = product.product_name; // Show plant name as placeholder
                deleteModal.show();
            });
        });
    });
</script>
</body>
</html>