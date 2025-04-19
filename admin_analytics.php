<?php
$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// total customer data
$resultCustomers = mysqli_query($conn, "SELECT COUNT(*) AS totalCustomers FROM Customer");
$rowCustomers = mysqli_fetch_assoc($resultCustomers);
$totalCustomers = $rowCustomers['totalCustomers'];

// total order data
$resultOrders = mysqli_query($conn, "SELECT COUNT(*) AS totalOrders FROM `Order`");
$rowOrders = mysqli_fetch_assoc($resultOrders);
$totalOrders = $rowOrders['totalOrders'];

// total product data
$resultProducts = mysqli_query($conn, "SELECT COUNT(*) AS totalProducts FROM Product");
$rowProducts = mysqli_fetch_assoc($resultProducts);
$totalProducts = $rowProducts['totalProducts'];

//total Sales
$totalSalesResult = $conn->query("SELECT SUM(total_amount) AS total FROM `Order` WHERE order_state = 'Confirmed'");
$totalSalesRow = $totalSalesResult->fetch_assoc();
$totalSales = $totalSalesRow['total'] ?? 0;


// Top 5 Best-Selling Products
$topProductNames = [];
$topProductQuantities = [];

$sql = "SELECT p.productName, SUM(od.quantity) AS total_sold
        FROM order_details od
        JOIN product p ON od.product_id = p.productID
        GROUP BY od.product_id
        ORDER BY total_sold DESC
        LIMIT 5";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $topProductNames[] = $row['productName'];
    $topProductQuantities[] = $row['total_sold'];
}

// Daily Revenue
$dailyDates = [];
$dailyRevenue = [];

$sql = "SELECT DATE_FORMAT(o.order_date, '%d %b') AS day, SUM(p.amount_paid) AS total_revenue
        FROM `order` o
        JOIN payment p ON o.order_id = p.order_id
        WHERE p.payment_status = 'Paid'
        GROUP BY DATE(o.order_date)
        ORDER BY DATE(o.order_date)";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $dailyDates[] = $row['day'];
    $dailyRevenue[] = $row['total_revenue'];
}

// Daily Orders
$dailyOrderCounts = [];

$sql = "SELECT DATE_FORMAT(order_date, '%d %b') AS day, COUNT(order_id) AS order_count
        FROM `order`
        GROUP BY DATE(order_date)
        ORDER BY DATE(order_date)";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $dailyOrderCounts[] = $row['order_count'];
}

?>
 