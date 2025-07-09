<?php
include("../Main/php/database.php");
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Main/php/index.php");
    exit();
}

// Process status update and restock if rejected
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = $_POST['new_status'];
    $allowed = ['On the Way', 'Rejected', 'Delivered'];

    if (in_array($newStatus, $allowed)) {
        $stmt = $con->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $newStatus, $orderId);

        if ($stmt->execute()) {
            $message = "Order #$orderId marked as \"$newStatus\".";

            if ($newStatus === 'Rejected') {
                $items = mysqli_query($con, "SELECT product_id, quantity FROM order_items WHERE order_id = $orderId");
                while ($item = mysqli_fetch_assoc($items)) {
                    $updateStock = $con->prepare("UPDATE products SET product_quantity = product_quantity + ? WHERE product_id = ?");
                    $updateStock->bind_param("ii", $item['quantity'], $item['product_id']);
                    $updateStock->execute();
                    $updateStock->close();
                }
            }
        } else {
            $message = "Failed to update order status.";
        }
        $stmt->close();
    }
}

// Status filter & optional date filtering
$statusFilter = isset($_GET['filter']) ? $_GET['filter'] : '';
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$whereSQL = "WHERE 1=1";

if ($statusFilter && in_array($statusFilter, ['Pending', 'On the Way', 'Delivered', 'Rejected'])) {
    $whereSQL .= " AND o.status = '" . mysqli_real_escape_string($con, $statusFilter) . "'";
}

if (!empty($startDate) && !empty($endDate)) {
    $whereSQL .= " AND DATE(o.order_date) BETWEEN '" . mysqli_real_escape_string($con, $startDate) . "' AND '" . mysqli_real_escape_string($con, $endDate) . "'";
}

$ordersRes = mysqli_query($con, "
    SELECT o.*, u.phoneNumber, u.address 
    FROM orders o 
    LEFT JOIN users u ON CONCAT(u.fname, ' ', u.lname) = o.customer_name 
    $whereSQL 
    ORDER BY o.order_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin — Manage Orders</title>
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
    table th, table td {
        vertical-align: middle !important;
    }
    ul {
        padding-left: 20px;
        margin: 0;
    }
    .bg-item-row {
        background-color: #f9f9f9;
    }
    .filter-form {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
  </style>
  <script>
    function toggleItems(orderId) {
      const row = document.getElementById('items-' + orderId);
      const btn = document.getElementById('toggle-btn-' + orderId);
      if (row.style.display === 'none') {
        row.style.display = 'table-row';
        btn.textContent = 'Hide Items';
      } else {
        row.style.display = 'none';
        btn.textContent = 'View Items';
      }
    }
  </script>
</head>
<body>
  <div class="main">
    <div class="sidebar">
      <h4>Admin Panel</h4>
      <a href="main.php">Dashboard</a>
      <a href="?">All Orders</a>
      <a href="?filter=Pending">Pending</a>
      <a href="?filter=On the Way">On the Way</a>
      <a href="?filter=Delivered">Delivered</a>
      <a href="?filter=Rejected">Rejected</a>
    </div>

    <div class="content">
      <h2 class="mb-4">🛠️ Manage Orders</h2>

      <?php if ($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <?php if ($statusFilter): ?>
        <h5>Viewing: <strong><?php echo htmlspecialchars($statusFilter); ?></strong> orders</h5>
      <?php endif; ?>

      <form method="GET" class="filter-form">
        <input type="hidden" name="filter" value="<?php echo htmlspecialchars($statusFilter); ?>">
        <label>From: <input type="date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" class="form-control"></label>
        <label>To: <input type="date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" class="form-control"></label>
        <button type="submit" class="btn btn-dark">Filter</button>
        <a href="manage_orders.php?filter=<?php echo urlencode($statusFilter); ?>" class="btn btn-secondary">Clear</a>
      </form>

      <table class="table table-bordered bg-white">
        <thead class="table-light">
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Date</th>
            <th>Total (₱)</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($order = mysqli_fetch_assoc($ordersRes)): ?>
          <tr>
            <td><?php echo $order['order_id']; ?></td>
            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($order['phoneNumber'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($order['address'] ?? 'N/A'); ?></td>
            <td><?php echo date("M j, Y H:i", strtotime($order['order_date'])); ?></td>
            <td><?php echo number_format($order['total_amount'], 2); ?></td>
            <td><strong><?php echo $order['status']; ?></strong></td>
            <td>
              <?php if ($order['status'] === 'Pending'): ?>
                <form method="POST" class="d-inline">
                  <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                  <input type="hidden" name="new_status" value="On the Way">
                  <button class="btn btn-success btn-sm mt-1">On the Way</button>
                </form>
                <form method="POST" class="d-inline">
                  <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                  <input type="hidden" name="new_status" value="Rejected">
                  <button class="btn btn-danger btn-sm mt-1">Reject</button>
                </form>
              <?php elseif ($order['status'] === 'On the Way'): ?>
                <form method="POST" class="d-inline">
                  <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                  <input type="hidden" name="new_status" value="Delivered">
                  <button class="btn btn-primary btn-sm mt-1">Delivered</button>
                </form>
              <?php else: ?>
                <em class="d-block mt-1">N/A</em>
              <?php endif; ?><br><br>
              <button class="btn btn-outline-secondary btn-sm mb-1" id="toggle-btn-<?php echo $order['order_id']; ?>" onclick="toggleItems(<?php echo $order['order_id']; ?>)">View Items</button>
            </td>
          </tr>

          <tr class="bg-item-row" id="items-<?php echo $order['order_id']; ?>" style="display: none;">
            <td colspan="8">
              <strong>Order Items:</strong>
              <ul class="mb-0">
                <?php
                  $orderId = $order['order_id'];
                  $itemsRes = mysqli_query($con, "
                    SELECT oi.quantity, (oi.quantity * oi.price) AS subtotal, p.product_name 
                    FROM order_items oi 
                    JOIN products p ON oi.product_id = p.product_id 
                    WHERE oi.order_id = $orderId
                  ");
                  while ($item = mysqli_fetch_assoc($itemsRes)) {
                    echo "<li>" . htmlspecialchars($item['product_name']) .
                         " × " . intval($item['quantity']) .
                         " — ₱" . number_format($item['subtotal'], 2) . "</li>";
                  }
                ?>
              </ul>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
