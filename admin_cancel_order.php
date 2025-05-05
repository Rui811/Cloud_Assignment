<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
// require_once 'db_connect.php';

$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$dbname = "chapalang";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['orderId'])) {
    $_SESSION['errorToast'] ="Invalid Request!";
    header("Location: admin.php");
    exit();
    return;
}

$orderId = $_POST['orderId'];
$paymentStatus = "Refunded";
$reason = $_POST['reason'];
$updatedBy = $_POST['staffName'];
$updatedTime = date('Y-m-d H:i:s');

//order
$orderSql = "UPDATE `order` 
             SET order_state = 'Cancelled', 
                 updated_by = ?, 
                 updated_time = ?, 
                 update_reason = ? 
             WHERE order_id = ? AND order_state = 'Confirmed'";
             
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bind_param("sssi", $updatedBy, $updatedTime, $reason, $orderId);
$orderUpdated = $orderStmt->execute();

//payment
$paymentSql = "UPDATE `payment` SET payment_status = ? WHERE order_id = ?";
$paymentStmt = $conn->prepare($paymentSql);
$paymentStmt->bind_param("si", $paymentStatus, $orderId);
$paymentUpdated = $paymentStmt->execute();

if ($orderUpdated && $paymentUpdated) {
    echo 'success';
} else {
    echo 'error';
}

$conn->close();
?>