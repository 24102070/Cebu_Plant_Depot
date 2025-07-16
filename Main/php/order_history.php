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


$totalOrdersQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM orders WHERE customer_name = '$customerName'");
$totalOrders = mysqli_fetch_assoc($totalOrdersQuery)['total'];


$statusCountQuery = mysqli_query($con, "
    SELECT status, COUNT(*) as count 
    FROM orders 
    WHERE customer_name = '$customerName' 
    GROUP BY status
");

$statusCounts = [];
while ($row = mysqli_fetch_assoc($statusCountQuery)) {
    $statusCounts[$row['status']] = $row['count'];
}

$orders = mysqli_query($con, "SELECT *, tentative_ship_datetime, adjustment_reason FROM orders WHERE customer_name = '$customerName' ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Order History</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="shortcut icon" href="../images/logonavwhite.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *::-webkit-scrollbar {
       display: none; 
    }
    :root {
      --primary-color: #3a5a40;
      --secondary-color: #588157;
      --accent-color: #a3b18a;
      --light-bg: #f8f9fa;
      --dark-text: #212529;
    }
    
    body {
      background-color: #588157;
      font-family: 'Inter', sans-serif;
      color: var(--dark-text);
      padding-top: 180px;
      min-height: 100vh;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    .sticky-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: white;
      z-index: 1000;
      padding: 15px 0;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .sticky-header-content {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    
    h2 {
      margin: 0;
      font-size: 1.8rem;
      font-weight: 600;
      color: var(--primary-color);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .btn-outline-success {
      border: 2px solid var(--primary-color);
      color: var(--primary-color);
      font-weight: 500;
      padding: 8px 16px;
      border-radius: 8px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .btn-outline-success:hover {
      background-color: var(--primary-color);
      color: white;
      transform: translateY(-2px);
    }
    
    .order-stats {
      background: rgba(255,255,255,0.8);
      padding: 12px 15px;
      border-radius: 10px;
      margin-bottom: 15px;
      backdrop-filter: blur(5px);
    }
    
    .total-orders {
      font-weight: 600;
      margin-right: 15px;
      color: var(--secondary-color);
    }
    
    .stat-badge {
      font-size: 0.85rem;
      padding: 6px 12px;
      margin-right: 8px;
      margin-bottom: 8px;
      font-weight: 500;
      border-radius: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .badge-pending { 
      background-color: rgba(255, 193, 7, 0.15);
      color: #ff9800;
      border: 1px solid rgba(255, 193, 7, 0.3);
    }
    
    .badge-ontheway { 
      background-color: rgba(3, 169, 244, 0.15);
      color: #03a9f4;
      border: 1px solid rgba(3, 169, 244, 0.3);
    }
    
    .badge-delivered { 
      background-color: rgba(76, 175, 80, 0.15);
      color: #4caf50;
      border: 1px solid rgba(76, 175, 80, 0.3);
    }
    
    .badge-rejected { 
      background-color: rgba(244, 67, 54, 0.15);
      color: #f44336;
      border: 1px solid rgba(244, 67, 54, 0.3);
    }
    
    .orders-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
      gap: 25px;
      margin-top: 20px;
    }
    
    .order-box {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 1px solid rgba(0,0,0,0.03);
    }
    
    .order-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .order-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .order-id {
      font-weight: 700;
      color: var(--primary-color);
      font-size: 1.1rem;
    }
    
    .order-date {
      font-size: 0.85rem;
      color: #6c757d;
      margin-top: 3px;
    }
    
    .order-status {
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
    }
    
    .shipment-info {
      margin-top: 12px;
      background-color: rgba(3, 169, 244, 0.05);
      border-left: 3px solid #03a9f4;
      padding: 12px;
      border-radius: 0 6px 6px 0;
    }
    
    .shipment-info i {
      color: #03a9f4;
      margin-right: 8px;
    }
    
    .shipment-label {
      font-size: 0.9rem;
      color: #495057;
      font-weight: 500;
    }
    
    .shipment-date {
      font-size: 0.95rem;
      color: #03a9f4;
      font-weight: 600;
    }
    
    .schedule-note {
      font-size: 0.85rem;
      color: #6c757d;
      line-height: 1.4;
      margin-top: 8px;
    }
    
    .list-group {
      border-radius: 8px;
      overflow: hidden;
    }
    
    .list-group-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 15px;
      border: none;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      transition: background-color 0.2s ease;
    }
    
    .list-group-item:hover {
      background-color: #f8f9fa;
    }
    
    .order-total {
      font-weight: 700;
      font-size: 1.1rem;
      color: var(--primary-color);
      margin-top: 15px;
    }
    
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      grid-column: 1 / -1;
    }
    
    .empty-state i {
      font-size: 3rem;
      color: #adb5bd;
      margin-bottom: 15px;
    }
    
    @media (max-width: 992px) {
      .orders-container {
        grid-template-columns: 1fr;
      }
      
      body {
        padding-top: 200px;
      }
    }
    
    @media (max-width: 576px) {
      .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
      
      .order-stats {
        padding: 10px;
      }
    }
  </style>
</head>
<body>

<div class="sticky-header">
  <div class="sticky-header-content">
    <div class="page-header">
      <h2><i class="fas fa-history"></i> My Order History</h2>
      <a href="catalogue.php" class="btn btn-outline-success">
        <i class="fas fa-arrow-left"></i> Back to Shop
      </a>
    </div>
    
    <div class="order-stats">
      <span class="total-orders"><i class="fas fa-shopping-bag"></i> Total Orders: <?php echo $totalOrders; ?></span>
      <?php 
      $statusLabels = [
        'Pending' => 'badge-pending',
        'On the Way' => 'badge-ontheway',
        'Delivered' => 'badge-delivered',
        'Rejected' => 'badge-rejected'
      ];
      
      foreach ($statusLabels as $status => $badgeClass): 
        $count = $statusCounts[$status] ?? 0;
        if ($count > 0): ?>
          <span class="stat-badge badge <?php echo $badgeClass; ?>">
            <i class="fas fa-<?php 
              if ($status === 'Pending') echo 'clock';
              elseif ($status === 'On the Way') echo 'truck';
              elseif ($status === 'Delivered') echo 'check-circle';
              else echo 'times-circle';
            ?>"></i> <?php echo "$status: $count"; ?>
          </span>
        <?php endif;
      endforeach; ?>
    </div>
  </div>
</div>

<div class="container">
  <div class="orders-container">
    <?php if (mysqli_num_rows($orders) === 0): ?>
      <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>No Orders Yet</h3>
        <p>You haven't placed any orders yet. Start shopping to see your order history here.</p>
        <a href="catalogue.php" class="btn btn-success mt-3">
          <i class="fas fa-shopping-cart"></i> Browse Products
        </a>
      </div>
    <?php else: ?>
      <?php while ($order = mysqli_fetch_assoc($orders)): ?>
        <div class="order-box">
          <div class="order-header">
            <div>
              <div class="order-id">Order #<?php echo $order['order_id']; ?></div>
              <div class="order-date">
                <i class="far fa-calendar-alt"></i> <?php echo date("M d, Y h:i A", strtotime($order['order_date'])); ?>
              </div>
            </div>
            <div>
              <div class="order-status badge 
                  <?php 
                    if ($order['status'] === 'Pending') echo 'badge-pending';
                    elseif ($order['status'] === 'On the Way') echo 'badge-ontheway';
                    elseif ($order['status'] === 'Delivered') echo 'badge-delivered';
                    elseif ($order['status'] === 'Rejected') echo 'badge-rejected';
                  ?>">
                <?php echo $order['status']; ?>
              </div>
                <div class="shipment-info">
                  <div class="d-flex align-items-center">
                    <i class="fas fa-shipping-fast"></i>
                    <span class="shipment-label">Estimated Shipment Date:</span>
                  </div>
                  <div class="shipment-date">
                    <?php 
                      if (!empty($order['tentative_ship_datetime'])) {
                        echo date("M d, Y h:i A", strtotime($order['tentative_ship_datetime']));
                      } else {
                        echo '<span class="text-muted">Not specified</span>';
                      }
                    ?>
                  </div>
                  
                  <?php if (!empty($order['adjustment_reason'])): ?>
                    <div class="d-flex align-items-start mt-2">
                      <i class="fas fa-info-circle mt-1"></i>
                      <div>
                        <div class="shipment-label">Schedule Note:</div>
                        <div class="schedule-note">
                          <?php echo htmlspecialchars($order['adjustment_reason']); ?>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
            </div>
          </div>
          <div>
            <ul class="list-group">
              <?php
                $orderId = $order['order_id'];
                $items = mysqli_query($con, "
                  SELECT oi.*, p.product_name, p.product_image 
                  FROM order_items oi
                  JOIN products p ON oi.product_id = p.product_id
                  WHERE oi.order_id = $orderId
                ");
                while ($item = mysqli_fetch_assoc($items)) {
                  echo "<li class='list-group-item'>
                          <div class='d-flex align-items-center'>
                            <img src='../../images/{$item['product_image']}' alt='{$item['product_name']}' style='width: 40px; height: 40px; object-fit: cover; border-radius: 6px; margin-right: 12px;'>
                            <div>
                              <div>{$item['product_name']}</div>
                              <small class='text-muted'>x{$item['quantity']}</small>
                            </div>
                          </div>
                          <span class='fw-bold'>₱" . number_format($item['price'] * $item['quantity'], 2) . "</span>
                        </li>";
                }
              ?>
            </ul>
            <div class="text-end order-total">
              Order Total: ₱<?php echo number_format($order['total_amount'], 2); ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</div>

</body>
</html>