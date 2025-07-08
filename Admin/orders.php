<?php
include("../Main/php/database.php");
session_start();

// Ensure user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Main/php/index.php");
    exit();
}

// Process status updates from form
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
        } else {
            $message = "Failed to update order status.";
        }
        $stmt->close();
    }
}

// Fetch orders, optionally filter by status
$statusFilter = '';
$whereSQL = '';
if (!empty($_GET['filter']) && in_array($_GET['filter'], ['Pending', 'On the Way', 'Delivered', 'Rejected'])) {
    $statusFilter = $_GET['filter'];
    $whereSQL = "WHERE status = '" . mysqli_real_escape_string($con, $statusFilter) . "'";
}

$ordersRes = mysqli_query($con, "SELECT * FROM orders $whereSQL ORDER BY order_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin — Manage Orders</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body { background: #f5f5f5; }
    .container { margin: 40px auto; max-width: 900px; }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="mb-4">🛠️ Manage Orders</h2>
    <?php if ($message): ?>
      <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="mb-3">
      <a href="?">All</a> |
      <a href="?filter=Pending">Pending</a> |
      <a href="?filter=On the Way">On the Way</a> |
      <a href="?filter=Delivered">Delivered</a> |
      <a href="?filter=Rejected">Rejected</a>
      <?php if ($statusFilter): ?>
        <span class="ms-2">Showing: <strong><?php echo htmlspecialchars($statusFilter); ?></strong></span>
      <?php endif; ?>
    </div>

    <table class="table table-bordered bg-white">
      <thead class="table-light">
        <tr>
          <th>ID</th><th>Customer</th><th>Date</th><th>Total (₱)</th><th>Status</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($order = mysqli_fetch_assoc($ordersRes)): ?>
        <tr>
          <td><?php echo $order['order_id']; ?></td>
          <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
          <td><?php echo date("M j, Y H:i", strtotime($order['order_date'])); ?></td>
          <td><?php echo number_format($order['total_amount'], 2); ?></td>
          <td><strong><?php echo $order['status']; ?></strong></td>
          <td>
            <?php if ($order['status'] === 'Pending'): ?>
              <form method="POST" class="d-inline">
                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                <input type="hidden" name="new_status" value="On the Way">
                <button class="btn btn-success btn-sm">Confirm → On the Way</button>
              </form>
              <form method="POST" class="d-inline">
                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                <input type="hidden" name="new_status" value="Rejected">
                <button class="btn btn-danger btn-sm">Reject</button>
              </form>
            <?php elseif ($order['status'] === 'On the Way'): ?>
              <form method="POST" class="d-inline">
                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                <input type="hidden" name="new_status" value="Delivered">
                <button class="btn btn-primary btn-sm">Mark as Delivered</button>
              </form>
            <?php else: ?>
              <em>N/A</em>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
