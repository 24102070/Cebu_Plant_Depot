<?php
include("database.php");
session_start();

$message = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_data'])) {
    $cart = json_decode($_POST['cart_data'], true);

    if (!$cart || count($cart) == 0) {
        $message = "Your cart is empty.";
    } else {
        $userId = $_SESSION['user_id'];

        // Fetch user name
        $userQuery = mysqli_query($con, "SELECT fname, lname FROM users WHERE id = $userId");
        $user = mysqli_fetch_assoc($userQuery);
        $customerName = $user['fname'] . ' ' . $user['lname'];

        // Check how many orders user has placed in total
        $orderCountQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM orders WHERE customer_name = '$customerName'");
        $orderCountResult = mysqli_fetch_assoc($orderCountQuery);
        $orderCount = $orderCountResult['count'];

        if ($orderCount >= 2) {
            $message = "You have reached the limit of 2 orders.";
        } else {
            $totalAmount = 0;
            $hasStockIssue = false;

            foreach ($cart as $productId => $item) {
                $productId = (int)$productId;
                $qty = (int)$item['qty'];

                $prodQuery = mysqli_query($con, "SELECT product_quantity FROM products WHERE product_id = $productId");
                $prod = mysqli_fetch_assoc($prodQuery);

                if (!$prod || $prod['product_quantity'] < $qty) {
                    $hasStockIssue = true;
                    break;
                }

                $totalAmount += $item['price'] * $qty;
            }

            if ($hasStockIssue) {
                $message = "One or more products in your cart no longer have enough stock.";
            } else {
                // Create order with status
                $status = 'Pending';
                $stmt = $con->prepare("INSERT INTO orders (customer_name, total_amount, status) VALUES (?, ?, ?)");
                $stmt->bind_param("sds", $customerName, $totalAmount, $status);
                $stmt->execute();
                $orderId = $stmt->insert_id;
                $stmt->close();

                // Insert each order item and update product stock
                foreach ($cart as $productId => $item) {
                    $productId = (int)$productId;
                    $qty = (int)$item['qty'];
                    $price = (float)$item['price'];

                    $stmt = $con->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iiid", $orderId, $productId, $qty, $price);
                    $stmt->execute();
                    $stmt->close();

                    $con->query("UPDATE products SET product_quantity = product_quantity - $qty WHERE product_id = $productId");
                }

                $message = "Your order has been placed successfully!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="../images/logonavwhite.png" type="image/png">
    <style>
      body {
        background-color: #dad7cd;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }
      .message-box {
        max-width: 500px;
        margin: 100px auto;
        background-color: #fff;
        border-radius: 10px;
        padding: 30px;
        box-shadow: rgba(0, 0, 0, 0.2) 0px 8px 24px;
        text-align: center;
      }
      .btn-back {
        margin-top: 20px;
        background-color: #3a5a40;
        color: white;
      }
      .btn-back:hover {
        background-color: #588157;
      }
      .limit-message {
        background-color: #ff4d4d !important;
        color: white !important;
      }
    </style>
</head>
<body>
  <div class="message-box">
    <?php if ($message): ?>
      <div class="alert <?php echo ($message === 'You have reached the limit of 2 orders.') ? 'limit-message' : (strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-warning'); ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>
    <a href="catalogue.php" class="btn btn-back">Back to Shop</a>
  </div>
</body>
</html>
