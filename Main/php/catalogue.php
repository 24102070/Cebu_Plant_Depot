<?php
include("database.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$search = $_GET['search'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';


$conditions = ["product_availability = 1"];
if (!empty($search)) {
    $conditions[] = "product_name LIKE '%" . mysqli_real_escape_string($con, $search) . "%'";
}
if (is_numeric($minPrice)) {
    $conditions[] = "product_price >= " . (float)$minPrice;
}
if (is_numeric($maxPrice)) {
    $conditions[] = "product_price <= " . (float)$maxPrice;
}

$whereClause = implode(" AND ", $conditions);
$sql = "SELECT * FROM products WHERE $whereClause ORDER BY product_name ASC";
$result = mysqli_query($con, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cebu Plant Depot</title>
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
      position: relative;
      padding-bottom: 100px; 
    }
    
    .navbar {
      background-color: var(--primary-green) !important;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
      padding: 0.8rem 1rem;
    }
    
    
    .catalogue-header {
      text-align: center;
      padding: 40px 30px;
      background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
      border-radius: 16px;
      box-shadow: 0 10px 20px rgba(58, 90, 64, 0.15);
      margin-bottom: 40px;
      position: relative;
      overflow: hidden;
    }
    
    .catalogue-header::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: var(--accent-yellow);
    }
    
    .catalogue-header h2 {
      color: white;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 15px;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
      position: relative;
      display: inline-block;
    }
    
    .catalogue-header h2::after {
      content: "";
      display: block;
      width: 60px;
      height: 3px;
      background: var(--accent-yellow);
      margin: 10px auto 0;
    }
    
    .catalogue-header .leaf-decoration {
      position: absolute;
      opacity: 0.1;
    }
    
    .catalogue-header .leaf-1 {
      top: 20px;
      left: 30px;
      transform: rotate(-15deg);
    }
    
    .catalogue-header .leaf-2 {
      bottom: 20px;
      right: 30px;
      transform: rotate(15deg);
    }
    
    .catalogue-container {
      margin-right: 320px;
      padding-right: 20px;
      transition: all 0.3s ease;
    }
    
    .shop-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 25px;
      justify-content: center;
      padding: 10px;
    }
    
    .card {
      background-color: white;
      border-radius: 16px;
      padding: 15px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      border: none;
      overflow: hidden;
      position: relative;
    }
    
    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: var(--light-green);
    }
    
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }
    
    .card img {
      height: 160px;
      width: 100%;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 15px;
      transition: transform 0.3s ease;
    }
    
    .card:hover img {
      transform: scale(1.03);
    }
    
    .card-body {
      padding: 0;
    }
    
    .card-text {
      font-weight: 600;
      color: var(--primary-green);
      margin-bottom: 8px;
      font-size: 1.05rem;
    }
    
    .card p {
      color: #555;
      margin-bottom: 10px;
    }
    
    .card small {
      color: #777;
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
    
    
    .cart-sidebar {
      position: fixed;
      top: 70px;
      right: 0;
      width: 320px;
      max-height: calc(100vh - 170px); 
      background-color: #fff;
      box-shadow: -5px 0 20px rgba(0, 0, 0, 0.1);
      padding: 20px;
      overflow-y: auto;
      z-index: 1000;
      border-top-left-radius: 16px;
      border-bottom-left-radius: 16px;
    }
    
    .cart-sidebar h4 {
      text-align: center;
      margin-bottom: 20px;
      color: var(--primary-green);
      font-weight: 600;
      padding-bottom: 10px;
      border-bottom: 2px dashed var(--lighter-green);
    }
    
    .cart-item {
      border-bottom: 1px solid #eee;
      padding: 15px 0;
      transition: background-color 0.2s;
    }
    
    .cart-item:hover {
      background-color: rgba(163, 177, 138, 0.05);
    }
    
    .cart-img {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      object-fit: cover;
      border: 1px solid #eee;
    }
    
    .qty-btn {
      padding: 3px 10px;
      margin: 0 5px;
      border-radius: 6px;
      background-color: var(--lighter-green);
      border: none;
      transition: all 0.2s;
    }
    
    .qty-btn:hover {
      background-color: var(--light-green);
      color: white;
    }
    
    .total-price {
      font-weight: bold;
      margin-top: 20px;
      font-size: 1.2rem;
      color: var(--primary-green);
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
    
    .form-control {
      border-radius: 10px;
      padding: 10px 15px;
      border: 1px solid #ddd;
    }
    
    .form-control:focus {
      border-color: var(--light-green);
      box-shadow: 0 0 0 0.25rem rgba(163, 177, 138, 0.25);
    }
    
    footer {
      background-color: var(--primary-green);
      color: white;
      padding: 20px 0;
      margin-top: auto;
      position: relative;
      z-index: 100;
    }
    
    @media (max-width: 1199.98px) {
      .catalogue-container {
        margin-right: 0;
        padding-right: 0;
      }
      
      .cart-sidebar {
        position: static;
        width: 100%;
        max-height: none;
        box-shadow: none;
        margin-top: 30px;
        border-radius: 16px;
      }
    }
    
    @media (max-width: 767.98px) {
      .shop-container {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
      }
      
      .card {
        padding: 12px;
      }
      
      .card img {
        height: 120px;
      }
      
      .catalogue-header h2 {
        font-size: 2rem;
      }
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
    
  
    .nav-button-container .nav-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .nav-button-container .nav-link i {
        font-size: 1.1rem;
    }
  </style>
</head>
<body>
  <div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand me-lg-5" href="catalogue.php">
                <img src="../images/logonavwhite.png" alt="Cebu Plant Depot Logo" class="img-fluid" style="height: 3.5rem; width: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="catalogue.php">Shop</a></li>
                </ul>
                <div class="d-flex ms-auto">
                    <div class="nav-button-container me-2">
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
            </div>
        </div>
    </nav>
</div>

    <div class="catalogue-container">
      <section class="mt-5 mb-5 mx-3 catalogue-header">
        <i class="fas fa-leaf leaf-decoration leaf-1 fa-3x"></i>
        <i class="fas fa-leaf leaf-decoration leaf-2 fa-3x"></i>
        <h2>OUR CATALOGUE</h2>
        
        <!-- 🔍 Search and Filter Form -->
        <form method="GET" class="row g-2 mb-4 justify-content-center">
          <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search product name"
                   value="<?php echo htmlspecialchars($search); ?>">
          </div>
          <div class="col-md-2">
            <input type="number" name="min_price" class="form-control" placeholder="Min Price" step="0.01"
                   value="<?php echo htmlspecialchars($minPrice); ?>">
          </div>
          <div class="col-md-2">
            <input type="number" name="max_price" class="form-control" placeholder="Max Price" step="0.01"
                   value="<?php echo htmlspecialchars($maxPrice); ?>">
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Filter</button>
          </div>
          <div class="col-md-2">
            <a href="catalogue.php" class="btn btn-secondary w-100">Reset</a>
          </div>
        </form>
      </section>

      <div class="shop-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
              <img src="../../images/<?php echo htmlspecialchars($row['product_image']); ?>" alt="">
              <div class="card-body">
                <p class="card-text fw-bold"><?php echo htmlspecialchars($row['product_name']); ?></p>
                <p>₱<?php echo number_format($row['product_price'], 2); ?></p>
                <p><small>Stocks Left: <?php echo $row['product_quantity']; ?></small></p>
                <form action="order.php" method="POST" class="add-to-cart-form">
                  <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                  <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>">
                  <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>">
                  <input type="hidden" name="product_image" value="../../images/<?php echo htmlspecialchars($row['product_image']); ?>">
                  <button type="submit" class="btn btn-success w-100">Buy Now</button>
                </form>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="col-12 text-center py-5">
            <i class="fas fa-seedling fa-3x mb-3" style="color: var(--light-green);"></i>
            <h4 class="text-muted">No products match your filter</h4>
            <a href="catalogue.php" class="btn btn-outline-success mt-3">Show All Products</a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="cart-sidebar" id="cart">
      <h4><i class="fas fa-shopping-cart me-2"></i>Your Cart</h4>
      <div id="cart-items"></div>
      <div class="total-price">Total: ₱<span id="cart-total">0.00</span></div>
      <form action="order.php" method="POST">
        <input type="hidden" name="cart_data" id="cart_data">
        <button type="submit" class="btn btn-success mt-3 w-100">
          <i class="fas fa-check-circle me-2"></i>Checkout
        </button>
      </form>
    </div>
  </div>

  <footer>
    <div class="footer text-center p-3">
      <p class="fw-bold mt-3">© 2025 Cebu Plant Depot. All rights reserved.</p>
    </div>
  </footer>

<script>
    const cart = {};
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const cartDataInput = document.getElementById('cart_data');

    function updateCart() {
      cartItemsContainer.innerHTML = '';
      let total = 0;

      for (let id in cart) {
        const item = cart[id];
        const subtotal = item.price * item.qty;
        total += subtotal;

        const div = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
          <div class="d-flex align-items-center">
            <img src="${item.img}" class="cart-img me-2">
            <div>
              <strong>${item.name}</strong><br>
              ₱${item.price.toFixed(2)} x 
              <button type="button" class="btn btn-sm btn-outline-secondary qty-btn" onclick="changeQty('${id}', -1)">-</button>
              ${item.qty}
              <button type="button" class="btn btn-sm btn-outline-secondary qty-btn" onclick="changeQty('${id}', 1)">+</button><br>
              <small>Subtotal: ₱${subtotal.toFixed(2)}</small>
            </div>
          </div>
        `;
        cartItemsContainer.appendChild(div);
      }

      cartTotal.textContent = total.toFixed(2);
      cartDataInput.value = JSON.stringify(cart);
    }

    function changeQty(id, delta) {
      if (cart[id]) {
        cart[id].qty += delta;
        if (cart[id].qty <= 0) {
          delete cart[id];
        }
        updateCart();
      }
    }

    document.querySelectorAll('.add-to-cart-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        const id = formData.get('product_id');
        const name = formData.get('product_name');
        const price = parseFloat(formData.get('product_price'));
        const img = formData.get('product_image');

        if (cart[id]) {
          cart[id].qty += 1;
        } else {
          cart[id] = { name, price, qty: 1, img };
        }

        updateCart();
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
