<?php
include("database.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];


$userQuery = mysqli_query($con, "SELECT fname, lname FROM users WHERE id = $userId");
$user = mysqli_fetch_assoc($userQuery);
$customerName = $user['fname'] . ' ' . $user['lname'];

// Fetch orders by customer name
$orders = mysqli_query($con, "SELECT * FROM orders WHERE customer_name = '$customerName' ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Order History</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <style>
    body {
      background-color: #f5f5f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .container {
      max-width: 900px;
      margin-top: 50px;
    }
    .order-box {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 25px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .order-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .order-status {
      font-weight: bold;
    }
    .badge-pending { background-color: orange; }
    .badge-ontheway { background-color: #17a2b8; }
    .badge-delivered { background-color: #28a745; }
    .badge-rejected { background-color: #dc3545; }
  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4">📦 My Order History</h2>

  <?php if (mysqli_num_rows($orders) === 0): ?>
    <div class="alert alert-info">You have no past orders.</div>
  <?php endif; ?>

  <?php while ($order = mysqli_fetch_assoc($orders)): ?>
    <div class="order-box">
      <div class="order-header mb-3">
        <div>
          <strong>Order #<?php echo $order['order_id']; ?></strong><br>
          <small><?php echo date("M d, Y h:i A", strtotime($order['order_date'])); ?></small>
        </div>
        <div class="order-status badge 
            <?php 
              if ($order['status'] === 'Pending') echo 'badge-pending';
              elseif ($order['status'] === 'On the Way') echo 'badge-ontheway';
              elseif ($order['status'] === 'Delivered') echo 'badge-delivered';
              elseif ($order['status'] === 'Rejected') echo 'badge-rejected';
            ?>">
          <?php echo $order['status']; ?>
        </div>
      </div>
      <div>
        <ul class="list-group">
          <?php
            $orderId = $order['order_id'];
            $items = mysqli_query($con, "
              SELECT oi.*, p.product_name 
              FROM order_items oi
              JOIN products p ON oi.product_id = p.product_id
              WHERE oi.order_id = $orderId
            ");
            while ($item = mysqli_fetch_assoc($items)) {
              echo "<li class='list-group-item d-flex justify-content-between'>
                      {$item['product_name']} x {$item['quantity']}
                      <span>₱" . number_format($item['price'] * $item['quantity'], 2) . "</span>
                    </li>";
            }
          ?>
        </ul>
        <div class="mt-3 text-end fw-bold">
          Total: ₱<?php echo number_format($order['total_amount'], 2); ?>
        </div>
      </div>
    </div>
  <?php endwhile; ?>

  <div class="text-center">
    <a href="catalogue.php" class="btn btn-outline-success">← Back to Shop</a>
  </div>
</div>

</body>
</html>
