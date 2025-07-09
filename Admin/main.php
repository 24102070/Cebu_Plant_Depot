<?php
include("../Main/php/database.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Total sales
$salesQuery = "SELECT SUM(total_amount) AS total_sales FROM orders WHERE status = 'Delivered'";
$salesResult = mysqli_query($con, $salesQuery);
$salesData = mysqli_fetch_assoc($salesResult);
$totalSales = $salesData['total_sales'] ?? 0;

// Monthly sales (current month)
$monthlySalesQuery = "
    SELECT SUM(total_amount) AS monthly_sales 
    FROM orders 
    WHERE status = 'Delivered' AND MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())
";
$monthlySalesResult = mysqli_query($con, $monthlySalesQuery);
$monthlySales = mysqli_fetch_assoc($monthlySalesResult)['monthly_sales'] ?? 0;

// Total delivered orders
$orderCountQuery = "SELECT COUNT(*) AS total_delivered FROM orders WHERE status = 'Delivered'";
$orderCountResult = mysqli_query($con, $orderCountQuery);
$orderCountData = mysqli_fetch_assoc($orderCountResult);
$totalDelivered = $orderCountData['total_delivered'] ?? 0;

// Most purchased product
$mostPurchasedQuery = "
    SELECT p.product_name, SUM(oi.quantity) AS total_quantity
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.status = 'Delivered'
    GROUP BY p.product_name
    ORDER BY total_quantity DESC
    LIMIT 1
";
$mostPurchasedResult = mysqli_query($con, $mostPurchasedQuery);
$mostPurchased = mysqli_fetch_assoc($mostPurchasedResult);

// 5 most recent orders
$recentOrdersQuery = "SELECT * FROM orders ORDER BY order_date DESC LIMIT 5";
$recentOrdersResult = mysqli_query($con, $recentOrdersQuery);

// Inventory sorted by quantity
$lowStockQuery = "SELECT * FROM products ORDER BY product_quantity ASC";
$lowStockResult = mysqli_query($con, $lowStockQuery);

// Monthly sales for the last 6 months
$monthlySalesBarData = [];
$months = [];
for ($i = 5; $i >= 0; $i--) {
    $date = date("Y-m-01", strtotime("-$i months"));
    $label = date("M Y", strtotime($date));
    $months[] = $label;

    $salesQuery = "
        SELECT SUM(total_amount) AS monthly_total
        FROM orders
        WHERE status = 'Delivered' AND MONTH(order_date) = MONTH('$date') AND YEAR(order_date) = YEAR('$date')
    ";
    $result = mysqli_query($con, $salesQuery);
    $row = mysqli_fetch_assoc($result);
    $monthlySalesBarData[] = $row['monthly_total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background: linear-gradient(to right, #3a5a40, #588157);
      font-family: 'Segoe UI', sans-serif;
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
    }
    .sidebar a {
      display: block;
      padding: 10px;
      background-color: #588157;
      margin: 10px 0;
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
    .card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }
    .low-stock {
      background-color: #fff3cd !important;
    }
    .out-of-stock {
      background-color: #f8d7da !important;
    }
    #monthlyChart {
  width: 100%;
  max-width: 750px;  
  height: 350px;      
  margin: auto;
}
  </style>
</head>
<body>
<div class="main">
  <div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="main.php">Dashboard</a>
    <a href="product_list.php">Product List</a>
    <a href="create.php">Add Product</a>
    <a href="orders.php">Manage Orders</a>
    <a href="../index.php">Logout</a>
  </div>

  <div class="content">
    <h2 class="mb-4">📊 Sales Summary & Inventory Overview</h2>

    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card">
          <h5>Total Sales (Delivered)</h5>
          <h3>₱<?php echo number_format($totalSales, 2); ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <h5>Sales This Month</h5>
          <h3>₱<?php echo number_format($monthlySales, 2); ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <h5>Total Delivered Orders</h5>
          <h3><?php echo $totalDelivered; ?></h3>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <h5>📈 Monthly Sales Chart</h5>
      <canvas id="monthlyChart"></canvas>
    </div>

    <div class="card mb-4">
      <h5>📦 Inventory Status (Sorted by Quantity)</h5>
      <table class="table table-bordered table-hover bg-white">
        <thead class="table-light">
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php mysqli_data_seek($lowStockResult, 0); while ($row = mysqli_fetch_assoc($lowStockResult)):
          $qty = $row['product_quantity'];
          $status = ($qty == 0) ? 'Out of Stock' : (($qty < 5) ? 'Low Stock' : 'In Stock');
          $rowClass = ($qty == 0) ? 'out-of-stock' : (($qty < 5) ? 'low-stock' : '');
        ?>
          <tr class="<?php echo $rowClass; ?>">
            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
            <td><?php echo $qty; ?></td>
            <td><strong><?php echo $status; ?></strong></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    
    <div class="card mb-4">
      <h5>🏆 Most Purchased Product</h5>
      <p><strong><?php echo $mostPurchased ? $mostPurchased['product_name'] . " (Total: " . $mostPurchased['total_quantity'] . ")" : "No Data"; ?></strong></p>
    </div>

    <div class="card mb-4">
      <h5>🆕 Top 5 Most Recent Orders</h5>
      <table class="table table-bordered bg-white">
        <thead class="table-light">
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Total</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($recentOrdersResult)): ?>
          <tr>
            <td><?php echo $row['order_id']; ?></td>
            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>₱<?php echo number_format($row['total_amount'], 2); ?></td>
            <td><?php echo date("M j, Y H:i", strtotime($row['order_date'])); ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    

  </div>
</div>

<script>
  const ctx = document.getElementById('monthlyChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($months); ?>,
      datasets: [{
        label: '₱ Sales per Month',
        data: <?php echo json_encode($monthlySalesBarData); ?>,
        backgroundColor: '#588157',
        borderRadius: 5,
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return '₱' + value;
            }
          }
        }
      }
    }
  });
</script>
</body>
</html>
