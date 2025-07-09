<?php
include("database.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get search and price filter values
$search = $_GET['search'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

// Build filter conditions
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
  <style>
    body {
      background-color: #dad7cd;
      overflow-x: hidden;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .catalogue {
      text-align: center;
      padding: 20px;
      background-color: #3a5a40;
      border-radius: 10px;
    }
    .catalogue-container {
      margin-right: 320px; /* space for sidebar */
      padding-right: 10px;
    }
    .shop-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .card {
      background-color: white;
      border-radius: 20px;
      padding: 10px;
      width: 200px;
      text-align: center;
      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
    }
    .card:hover {
      transform: translateY(-5px);
      transition: 0.2s ease-in-out;
    }
    .card img {
      height: 150px;
      object-fit: cover;
      border-radius: 10px;
    }
    .cart-sidebar {
      position: fixed;
      top: 70px;
      right: 0;
      width: 300px;
      height: calc(100% - 70px);
      background-color: #fff;
      box-shadow: -2px 0 8px rgba(0, 0, 0, 0.2);
      padding: 15px;
      overflow-y: auto;
      z-index: 1000;
    }
    .cart-sidebar h4 {
      text-align: center;
      margin-bottom: 15px;
    }
    .cart-item {
      border-bottom: 1px solid #ccc;
      padding: 10px 0;
    }
    .cart-item:last-child {
      border: none;
    }
    .qty-btn {
      padding: 2px 8px;
      margin: 0 5px;
    }
    .total-price {
      font-weight: bold;
      margin-top: 10px;
    }
    .cart-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    @media (max-width: 991.98px) {
      .catalogue-container {
        margin-right: 0;
        padding-right: 0;
      }
      .cart-sidebar {
        position: static;
        width: 100%;
        height: auto;
        box-shadow: none;
        margin-top: 30px;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #3a5a40;">
    <div class="container-fluid">
      <div class="d-flex order-lg-3 position-static position-lg-absolute end-0 me-3 align-items-center">
        <a href="order_history.php" class="nav-link text-white me-3">
          <i class="fas fa-clock-rotate-left"></i> <span class="d-none d-md-inline">Order History</span>
        </a>
        <a href="logout.php" class="nav-link text-white">
          <i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span>
        </a>
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
        <a href="catalogue.php"><img src="../images/logowhite.png" style="width: 8rem; padding: 5px;"></a>
      </div>
    </div>
  </nav>

  <div class="catalogue-container">
    <section class="mt-5 mb-5 mx-3 catalogue">
      <h2 class="text-center text-white mt-3 mb-4">CATALOGUE</h2>

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
          <p class="text-white fw-bold">No products match your filter.</p>
        <?php endif; ?>
      </div>
    </section>
  </div>

  <div class="cart-sidebar" id="cart">
    <h4>Your Cart</h4>
    <div id="cart-items"></div>
    <div class="total-price">Total: ₱<span id="cart-total">0.00</span></div>
    <form action="order.php" method="POST">
      <input type="hidden" name="cart_data" id="cart_data">
      <button type="submit" class="btn btn-success mt-3 w-100">Checkout</button>
    </form>
  </div>

  <footer>
    <div class="footer text-center p-2">
      <p class="fw-bold mt-3">© 2025 Cebu Plant Depot.</p>
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
