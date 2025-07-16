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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="shortcut icon" href="../images/logonavwhite.png" type="image/png">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    .sidebar a.dashboard-btn {
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
        <a href="main.php"><img src="../images/Plantlogo.png" style="width: 8rem; padding: 5px;" class="img-fluid"></a>
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

  <div class="content">
    <div class="welcome-header">
      <div class="welcome-message">
        <h2><i class="fas fa-chart-line"></i> Sales Dashboard</h2>
        <p>Overview of your store's performance</p>
        <a href="download_sales_pdf.php" class="download-pdf-link"><i class="fas fa-file-pdf"></i> Download Sales PDF</a>
      </div>
      <a href="../index.php" class="btn logout-btn text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <style>
      .download-pdf-link {
        color: #344e41;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        margin-top: 0.5rem;
      }
      .download-pdf-link:hover {
        text-decoration: underline;
        cursor: pointer;
      }
    </style>

    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card stat-card">
          <div class="icon-wrapper">
            <i class="fas fa-money-bill-wave"></i>
          </div>
          <h5>Total Sales (Delivered)</h5>
          <h3>₱<?php echo number_format($totalSales, 2); ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card stat-card">
          <div class="icon-wrapper">
            <i class="fas fa-calendar-alt"></i>
          </div>
          <h5>Sales This Month</h5>
          <h3>₱<?php echo number_format($monthlySales, 2); ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card stat-card">
          <div class="icon-wrapper">
            <i class="fas fa-truck"></i>
          </div>
          <h5>Delivered Orders</h5>
          <h3><?php echo $totalDelivered; ?></h3>
        </div>
      </div>
    </div>

    <div class="dashboard-grid">
      <!-- Left Column -->
      <div class="left-column">
        <div class="card mb-4">
          <div class="card-header">
            <i class="fas fa-boxes"></i>
            <h5 class="mb-0">Inventory Status</h5>
          </div>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
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
                $statusClass = ($qty == 0) ? 'status-out' : (($qty < 5) ? 'status-low' : 'status-good');
                $rowClass = ($qty == 0) ? 'out-of-stock' : (($qty < 5) ? 'low-stock' : 'in-stock');
              ?>
                <tr class="<?php echo $rowClass; ?>">
                  <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                  <td><?php echo $qty; ?></td>
                  <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $status; ?></span></td>
                </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header">
            <i class="fas fa-clock"></i>
            <h5 class="mb-0">Recent Orders</h5>
          </div>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Customer</th>
                  <th>Status</th>
                  <th>Total</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
              <?php mysqli_data_seek($recentOrdersResult, 0); while ($row = mysqli_fetch_assoc($recentOrdersResult)): ?>
                <tr>
                  <td><?php echo $row['order_id']; ?></td>
                  <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                  <td>
                    <span class="badge 
                      <?php echo $row['status'] == 'Delivered' ? 'bg-success' : 
                            ($row['status'] == 'Processing' ? 'bg-warning text-dark' : 'bg-info'); ?>">
                      <?php echo $row['status']; ?>
                    </span>
                  </td>
                  <td>₱<?php echo number_format($row['total_amount'], 2); ?></td>
                  <td><?php echo date("M j, Y H:i", strtotime($row['order_date'])); ?></td>
                </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Right Column -->
      <div class="right-column">
        <div class="card mb-4">
          <div class="card-header">
            <i class="fas fa-chart-bar"></i>
            <h5 class="mb-0">Monthly Sales Chart</h5>
          </div>
          <canvas id="monthlyChart"></canvas>
        </div>

        <div class="card mb-4">
          <div class="card-header">
            <i class="fas fa-trophy"></i>
            <h5 class="mb-0">Top Product</h5>
          </div>
          <div class="top-product-card">
            <div class="top-product-icon">
              <i class="fas fa-star"></i>
            </div>
            <div class="top-product-info">
              <h5><?php echo $mostPurchased ? htmlspecialchars($mostPurchased['product_name']) : "No Data"; ?></h5>
              <p>Total Sold: <?php echo $mostPurchased ? $mostPurchased['total_quantity'] : "0"; ?></p>
            </div>
          </div>
        </div>
      </div>
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