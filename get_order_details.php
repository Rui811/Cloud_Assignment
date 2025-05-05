<?php
// require_once 'db_connect.php';

$host = "chapalang-database1.cjpdewbot84w.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$dbname = "chapalang";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

if (!isset($_POST['orderId'])) {
    echo json_encode([
        "success" => false,
        "message" => "No order ID"
    ]);
    exit();
}

$orderId = $_POST['orderId'];

//order
$orderSql = "SELECT 
            o.order_id,
            o.customer_id,
            c.cust_name,
            o.order_date,
            o.total_amount,
            o.order_state,
            p.payment_status,
            o.updated_by,
            o.updated_time,
            o.update_reason
        FROM `order` o
        JOIN `customer` c ON o.customer_id = c.customer_id
        LEFT JOIN `payment` p ON o.order_id = p.order_id
        WHERE o.order_id = ?";

$orderStmt = $conn->prepare($orderSql);
$orderStmt->bind_param("i", $orderId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

if (!$orderResult || !$orderRow = $orderResult->fetch_assoc()) {
    echo json_encode([
        "success" => false, 
        "message" => "Order not found"
    ]);
    exit();
}

//order items
$itemSql = "SELECT 
                od.product_id, 
                p.productName AS productName, 
                od.quantity, 
                od.unit_price 
             FROM `order_details` od 
             JOIN product p ON od.product_id = p.productID 
             WHERE od.order_id = ?";
$itemStmt = $conn->prepare($itemSql);
$itemStmt->bind_param("i", $orderId);
$itemStmt->execute();
$itemResult = $itemStmt->get_result();

$items = [];
while ($item = $itemResult->fetch_assoc()) {
    $items[] = $item;
}

$orderRow["items"] = $items;
$orderRow["success"] = true;

echo json_encode($orderRow);
$conn->close();
?>
