<?php
// Admin/download_sales_pdf.php
// This script generates a detailed PDF report of sales statistics and breakdown using fpdf library.
// Please ensure you have the fpdf.php file and the 'font' directory in the Admin directory.

// Include fpdf library
require('fpdf.php');
include("../Main/php/database.php");

// Start session and check admin role
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

class PDF extends FPDF {
    // Table header
    function HeaderTable($header, $widths) {
        $this->SetFont('Arial','B',12);
        foreach($header as $i => $col) {
            $this->Cell($widths[$i],7,$col,1,0,'C');
        }
        $this->Ln();
    }
    // Table rows
    function RowsTable($data, $widths, $aligns = []) {
        $this->SetFont('Arial','',11);
        foreach($data as $row) {
            foreach($row as $i => $col) {
                $align = $aligns[$i] ?? 'L';
                $this->Cell($widths[$i],6,$col,1,0,$align);
            }
            $this->Ln();
        }
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Sales Statistics and Breakdown', 0, 1, 'C');
$pdf->Ln(5);

// 1. Total Sales (Delivered) and list of all delivered orders
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, '1. Total Sales (Delivered)', 0, 1);
$totalSalesQuery = "SELECT SUM(total_amount) AS total_sales FROM orders WHERE status = 'Delivered'";
$totalSalesResult = mysqli_query($con, $totalSalesQuery);
$totalSales = mysqli_fetch_assoc($totalSalesResult)['total_sales'] ?? 0;
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'Total Sales Amount: PHP ' . number_format($totalSales, 2), 0, 1);
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'List of All Delivered Orders:', 0, 1);
$deliveredOrdersQuery = "SELECT order_id, customer_name, total_amount, order_date FROM orders WHERE status = 'Delivered' ORDER BY order_date DESC";
$deliveredOrdersResult = mysqli_query($con, $deliveredOrdersQuery);
$deliveredOrdersData = [];
    while ($row = mysqli_fetch_assoc($deliveredOrdersResult)) {
        $deliveredOrdersData[] = [
            $row['order_id'],
            $row['customer_name'],
            'PHP ' . number_format($row['total_amount'], 2),
            date("M j, Y H:i", strtotime($row['order_date']))
        ];
    }
$pdf->HeaderTable(['Order ID', 'Customer', 'Total Amount', 'Order Date'], [30, 60, 40, 50]);
$pdf->RowsTable($deliveredOrdersData, [30, 60, 40, 50], ['C', 'L', 'R', 'L']);
$pdf->Ln(10);

// 2. Sales This Month and list of delivered orders this month
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, '2. Sales This Month', 0, 1);
$monthlySalesQuery = "
    SELECT SUM(total_amount) AS monthly_sales 
    FROM orders 
    WHERE status = 'Delivered' AND MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())
";
$monthlySalesResult = mysqli_query($con, $monthlySalesQuery);
$monthlySales = mysqli_fetch_assoc($monthlySalesResult)['monthly_sales'] ?? 0;
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'Total Sales This Month: PHP ' . number_format($monthlySales, 2), 0, 1);
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'List of Delivered Orders This Month:', 0, 1);
$deliveredOrdersMonthQuery = "
    SELECT order_id, customer_name, total_amount, order_date 
    FROM orders 
    WHERE status = 'Delivered' AND MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())
    ORDER BY order_date DESC
";
$deliveredOrdersMonthResult = mysqli_query($con, $deliveredOrdersMonthQuery);
$deliveredOrdersMonthData = [];
    while ($row = mysqli_fetch_assoc($deliveredOrdersMonthResult)) {
        $deliveredOrdersMonthData[] = [
            $row['order_id'],
            $row['customer_name'],
            'PHP ' . number_format($row['total_amount'], 2),
            date("M j, Y H:i", strtotime($row['order_date']))
        ];
    }
$pdf->HeaderTable(['Order ID', 'Customer', 'Total Amount', 'Order Date'], [30, 60, 40, 50]);
$pdf->RowsTable($deliveredOrdersMonthData, [30, 60, 40, 50], ['C', 'L', 'R', 'L']);
$pdf->Ln(10);

// 3. Total Delivered Orders count
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, '3. Total Delivered Orders', 0, 1);
$totalDeliveredQuery = "SELECT COUNT(*) AS total_delivered FROM orders WHERE status = 'Delivered'";
$totalDeliveredResult = mysqli_query($con, $totalDeliveredQuery);
$totalDelivered = mysqli_fetch_assoc($totalDeliveredResult)['total_delivered'] ?? 0;
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'Total Delivered Orders: ' . $totalDelivered, 0, 1);
$pdf->Ln(10);

// 4. Most Purchased Products (all)
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, '4. Most Purchased Products', 0, 1);
$mostPurchasedQuery = "
    SELECT p.product_name, SUM(oi.quantity) AS total_quantity
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.status = 'Delivered'
    GROUP BY p.product_name
    ORDER BY total_quantity DESC
";
$mostPurchasedResult = mysqli_query($con, $mostPurchasedQuery);
$mostPurchasedData = [];
while ($row = mysqli_fetch_assoc($mostPurchasedResult)) {
    $mostPurchasedData[] = [
        $row['product_name'],
        $row['total_quantity']
    ];
}
$pdf->HeaderTable(['Product Name', 'Total Quantity Sold'], [100, 60]);
$pdf->RowsTable($mostPurchasedData, [100, 60], ['L', 'C']);
$pdf->Ln(10);

// 5. 5 Most Recent Orders
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, '5. 5 Most Recent Orders', 0, 1);
$recentOrdersQuery = "SELECT order_id, customer_name, status, total_amount, order_date FROM orders ORDER BY order_date DESC LIMIT 5";
$recentOrdersResult = mysqli_query($con, $recentOrdersQuery);
$recentOrdersData = [];
    while ($row = mysqli_fetch_assoc($recentOrdersResult)) {
        $recentOrdersData[] = [
            $row['order_id'],
            $row['customer_name'],
            $row['status'],
            'PHP ' . number_format($row['total_amount'], 2),
            date("M j, Y H:i", strtotime($row['order_date']))
        ];
    }
$pdf->HeaderTable(['Order ID', 'Customer', 'Status', 'Total', 'Date'], [25, 50, 30, 30, 45]);
$pdf->RowsTable($recentOrdersData, [25, 50, 30, 30, 45], ['C', 'L', 'C', 'R', 'L']);
$pdf->Ln(10);

// 6. Inventory Status
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, '6. Inventory Status', 0, 1);
$inventoryQuery = "SELECT product_name, product_quantity FROM products ORDER BY product_quantity ASC";
$inventoryResult = mysqli_query($con, $inventoryQuery);
$inventoryData = [];
while ($row = mysqli_fetch_assoc($inventoryResult)) {
    $qty = $row['product_quantity'];
    if ($qty == 0) {
        $status = 'Out of Stock';
    } elseif ($qty < 5) {
        $status = 'Low Stock';
    } else {
        $status = 'In Stock';
    }
    $inventoryData[] = [
        $row['product_name'],
        $qty,
        $status
    ];
}
$pdf->HeaderTable(['Product Name', 'Quantity', 'Status'], [90, 30, 40]);
$pdf->RowsTable($inventoryData, [90, 30, 40], ['L', 'C', 'C']);
$pdf->Ln(10);

$pdf->Output('D', 'sales_statistics_breakdown.pdf');
exit();
?>
