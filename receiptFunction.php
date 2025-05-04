<?php

$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$dbname = "chapalang";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['order_id'])) {
    $_SESSION['errorToast'] = "Invalid Request!";
    header("Location: homepage.php");
    exit();
    return;
}

if (!isset($_GET['from']) || $_GET['from'] != "checkout" && $_GET['from'] != "profile") {
    $_SESSION['errorToast'] = "Invalid Request!";
    header("Location: homepage.php");
    exit();
    return;
}

$orderId = $_GET['order_id'];
$from = $_GET['from'];

//verify the order ID is for that cust
$verifySql = "SELECT * FROM `Order` WHERE customer_id = ? AND order_id = ?";
$verifyStmt = $conn->prepare($verifySql);
$verifyStmt->bind_param("ii", $customer_id, $orderId);
$verifyStmt->execute();
$result = $verifyStmt->get_result();

if($result->num_rows === 0) {
    $_SESSION['errorToast'] = "Invalid Request!";
    header("Location: homepage.php");
    exit();
    return;
}

//retrieve result
$selectSql = "SELECT o.order_date, 
                     o.order_state, 
                     o.total_amount, 
                     cust.cust_name, 
                     cust.cust_email, 
                     cust.cust_phone, 
                     cust.cust_address,
                     pay.payment_method, 
                     pay.payment_status, 
                     pay.payment_date
              FROM `Order` o 
              JOIN `Customer` cust ON o.customer_id = cust.customer_id
              JOIN `Payment` pay ON pay.order_id = o.order_id
              WHERE o.order_id = ?";

$selectStmt = $conn->prepare($selectSql);
$selectStmt->bind_param("i", $orderId);
$selectStmt->execute();
$result = $selectStmt->get_result();

if ($row = $result->fetch_assoc()) {
    $orderDate = $row['order_date'];
    $orderStatus = $row['order_state'];
    $customerName = $row['cust_name'];
    $customerEmail = $row['cust_email'];
    $customerPhone = $row['cust_phone'];
    $customerAddress = $row['cust_address'];
    $paymentMethod = $row['payment_method'];
    $paymentStatus = $row['payment_status'];
    $paymentDate = $row['payment_date'];
    $grandTotal = $row['total_amount'];
}

$itemSql = "SELECT p.productName, 
                   od.quantity, 
                   od.unit_price, 
                   od.remark, 
                   (od.unit_price * od.quantity) AS subtotal 
            FROM `Order_Details` od 
            JOIN `Product` p ON od.product_id = p.productID
            WHERE od.order_id = ?";

$itemStmt = $conn->prepare($itemSql);
$itemStmt->bind_param("i", $orderId);
$itemStmt->execute();
$itemsResult = $itemStmt->get_result();

$conn->close();
?>