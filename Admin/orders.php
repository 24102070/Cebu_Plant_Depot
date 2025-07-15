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
        if ($newStatus === 'On the Way' && isset($_POST['tentative_ship_datetime'])) {
            $tentativeShipDatetime = $_POST['tentative_ship_datetime'];
            $stmt = $con->prepare("UPDATE orders SET status = ?, tentative_ship_datetime = ? WHERE order_id = ?");
            $stmt->bind_param("ssi", $newStatus, $tentativeShipDatetime, $orderId);
        } else {
            $stmt = $con->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
            $stmt->bind_param("si", $newStatus, $orderId);
        }

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

// Fixed query to prevent duplicate orders
$ordersRes = mysqli_query($con, "
    SELECT o.*, 
           MAX(u.phoneNumber) AS phoneNumber, 
           MAX(u.address) AS address, 
           o.tentative_ship_datetime, 
           o.adjustment_reason
    FROM orders o 
    LEFT JOIN users u ON CONCAT(u.fname, ' ', u.lname) = o.customer_name 
    $whereSQL 
    GROUP BY o.order_id
    ORDER BY o.order_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin — Manage Orders</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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

    .sidebar a.orders-btn {
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
    
    /* Table Styling */
    .table-responsive {
      overflow-x: auto;
    }
    
    .table {
      background-color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .table th {
      background-color: #344e41;
      color: white;
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(88,129,87,0.1);
    }
    
    /* Status Badges */
    .badge-pending {
      background-color: #ffc107;
      color: #000;
    }
    
    .badge-ontheway {
      background-color: #0dcaf0;
      color: #000;
    }
    
    .badge-delivered {
      background-color: #198754;
      color: #fff;
    }
    
    .badge-rejected {
      background-color: #dc3545;
      color: #fff;
    }
    
    /* Filter Form */
    .filter-form {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    /* Responsive */
    @media (max-width: 992px) {
      .main {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        top: 0;
        margin-bottom: 20px;
      }
      .content {
        margin-left: 0;
      }
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
        <h2><i class="fas fa-shopping-cart"></i> Manage Orders</h2>
        <p>View and update order status</p>
      </div>
      <a href="../index.php" class="btn logout-btn text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <?php if ($message): ?>
      <div class="alert alert-success mb-4"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Filter Form -->
    <div class="filter-form">
      <form method="GET" class="row g-3">
        <div class="col-md-3">
          <select class="form-select" name="filter">
            <option value="">All Statuses</option>
            <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="On the Way" <?= $statusFilter === 'On the Way' ? 'selected' : '' ?>>On the Way</option>
            <option value="Delivered" <?= $statusFilter === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
            <option value="Rejected" <?= $statusFilter === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
          </select>
        </div>
        <div class="col-md-3">
          <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>" placeholder="Start Date">
        </div>
        <div class="col-md-3">
          <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>" placeholder="End Date">
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-success w-100">Filter</button>
        </div>
      </form>
    </div>

    <!-- Orders Table -->
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Total (₱)</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($order = mysqli_fetch_assoc($ordersRes)): ?>
          <tr>
            <td><?= $order['order_id'] ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td><?= htmlspecialchars($order['phoneNumber'] ?? 'N/A') ?></td>
            <td><?= date("M j, Y H:i", strtotime($order['order_date'])) ?></td>
            <td>₱<?= number_format($order['total_amount'], 2) ?></td>
            <td>
              <?php 
                $statusClass = strtolower(str_replace(' ', '', $order['status']));
                echo '<span class="badge badge-'.$statusClass.'">'.$order['status'].'</span>';
              ?>
            </td>
            <td>
              <?php if ($order['status'] === 'Pending'): ?>
                <button class="btn btn-success btn-sm" onclick="openShipModal(<?= $order['order_id'] ?>)">Ship</button>
                <form method="POST" class="d-inline">
                  <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                  <input type="hidden" name="new_status" value="Rejected">
                  <button class="btn btn-danger btn-sm">Reject</button>
                </form>
              <?php elseif ($order['status'] === 'On the Way'): ?>
                <form method="POST" class="d-inline">
                  <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                  <input type="hidden" name="new_status" value="Delivered">
                  <button class="btn btn-primary btn-sm">Delivered</button>
                </form>
                <button 
                  class="btn btn-warning btn-sm" 
                  onclick="openUpdateShipModal(this)" 
                  data-order-id="<?= $order['order_id'] ?>" 
                  data-tentative_ship_datetime="<?= htmlspecialchars($order['tentative_ship_datetime']) ?>" 
                  data-adjustment_reason="<?= htmlspecialchars($order['adjustment_reason']) ?>"
                >Update Shipment</button>
              <?php endif; ?>
              <button class="btn btn-info btn-sm" onclick="toggleItems(<?= $order['order_id'] ?>)">Details</button>
            </td>
          </tr>
          <tr id="items-<?= $order['order_id'] ?>" style="display: none;">
            <td colspan="7">
              <div class="p-3 bg-light rounded">
                <h6>Order Details</h6>
                <p><strong>Address:</strong> <?= htmlspecialchars($order['address'] ?? 'N/A') ?></p>
                <h6>Items:</h6>
                <ul>
                  <?php
                    $orderId = $order['order_id'];
                    $itemsRes = mysqli_query($con, "
                      SELECT oi.quantity, oi.price, p.product_name 
                      FROM order_items oi 
                      JOIN products p ON oi.product_id = p.product_id 
                      WHERE oi.order_id = $orderId
                    ");
                    $items = [];
                    while ($item = mysqli_fetch_assoc($itemsRes)) {
                      // Group items by product_id to avoid duplicates
                      $key = $item['product_name'];
                      if (!isset($items[$key])) {
                          $items[$key] = [
                              'product_name' => $item['product_name'],
                              'quantity' => 0,
                              'price' => $item['price']
                          ];
                      }
                      $items[$key]['quantity'] += $item['quantity'];
                    }
                    
                    foreach ($items as $item) {
                      $subtotal = $item['quantity'] * $item['price'];
                      echo "<li>" . htmlspecialchars($item['product_name']) .
                           " × " . intval($item['quantity']) .
                           " — ₱" . number_format($subtotal, 2) . "</li>";
                    }
                  ?>
                </ul>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Ship Modal -->
<div class="modal fade" id="shipModal" tabindex="-1" aria-labelledby="shipModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="shipForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shipModalLabel">Set Tentative Shipment Date and Time</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="order_id" id="modalOrderId" value="">
        <input type="hidden" name="new_status" value="On the Way">
        <div class="mb-3">
          <label for="tentative_ship_datetime" class="form-label">Tentative Shipment Date and Time</label>
          <input type="datetime-local" class="form-control" id="tentative_ship_datetime" name="tentative_ship_datetime" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success">Confirm Ship</button>
      </div>
    </form>
  </div>
</div>

<!-- Update Shipment Modal -->
<div class="modal fade" id="updateShipModal" tabindex="-1" aria-labelledby="updateShipModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="updateShipForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateShipModalLabel">Update Tentative Shipment Date and Adjustment Reason</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="order_id" id="updateModalOrderId" value="">
        <div class="mb-3">
          <label for="update_tentative_ship_datetime" class="form-label">Tentative Shipment Date and Time</label>
          <input type="datetime-local" class="form-control" id="update_tentative_ship_datetime" name="tentative_ship_datetime" required>
        </div>
        <div class="mb-3">
          <label for="update_adjustment_reason" class="form-label">Adjustment Reason</label>
          <textarea class="form-control" id="update_adjustment_reason" name="adjustment_reason" rows="3" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Shipment</button>
      </div>
    </form>
  </div>
</div>

<script>
  function toggleItems(orderId) {
    const row = document.getElementById('items-' + orderId);
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
  }

  function openShipModal(orderId) {
    const modalOrderId = document.getElementById('modalOrderId');
    modalOrderId.value = orderId;
    const shipModal = new bootstrap.Modal(document.getElementById('shipModal'));
    shipModal.show();
  }

  function openUpdateShipModal(button) {
    const orderId = button.getAttribute('data-order-id');
    const tentativeShipDatetime = button.getAttribute('data-tentative_ship_datetime');
    const adjustmentReason = button.getAttribute('data-adjustment_reason');

    document.getElementById('updateModalOrderId').value = orderId;

    if (tentativeShipDatetime) {
      const dt = new Date(tentativeShipDatetime);
      const formatted = dt.toISOString().slice(0,16);
      document.getElementById('update_tentative_ship_datetime').value = formatted;
    } else {
      document.getElementById('update_tentative_ship_datetime').value = '';
    }

    document.getElementById('update_adjustment_reason').value = adjustmentReason || '';

    const updateShipModal = new bootstrap.Modal(document.getElementById('updateShipModal'));
    updateShipModal.show();
  }
</script>

<?php
// Handle update shipment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['tentative_ship_datetime'], $_POST['adjustment_reason'])) {
    $orderId = (int)$_POST['order_id'];
    $tentativeShipDatetime = $_POST['tentative_ship_datetime'];
    $adjustmentReason = $_POST['adjustment_reason'];

    $stmt = $con->prepare("UPDATE orders SET tentative_ship_datetime = ?, adjustment_reason = ? WHERE order_id = ?");
    $stmt->bind_param("ssi", $tentativeShipDatetime, $adjustmentReason, $orderId);

    if ($stmt->execute()) {
        $message = "Shipment details updated for Order #$orderId.";
    } else {
        $message = "Failed to update shipment details for Order #$orderId.";
    }
    $stmt->close();
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>