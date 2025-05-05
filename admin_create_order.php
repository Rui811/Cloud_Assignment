<?php
date_default_timezone_set('Asia/Kuala_Lumpur');

$host = "chapalang-database1.cjpdewbot84w.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$dbname = "chapalang";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['customer_id'])) {
    $_SESSION['errorToast'] ="Invalid Request!";
    header("Location: admin.php");
    exit();
    return;
}

$customerId = $_POST['customer_id'];
$productIds = $_POST['product_id'];
$quantities = $_POST['quantity'];
$remarks = $_POST['remark'];
$paymentMethod = $_POST['payment_method'];
$updateReason = $_POST['update_reason'];
$updatedBy = $_POST['staffName'];

$orderDate = date('Y-m-d H:i:s');
$orderState = "Confirmed";
$updatedTime = date('Y-m-d H:i:s');
$paymentDate = $orderDate;
$paymentStatus = "Paid";
$totalAmount = 0.0;

$response = ['success' => false];

try {
    $conn->begin_transaction();

    // 1. calculate total order amount
    for ($i = 0; $i < count($productIds); $i++) {
        $prodId = $productIds[$i];
        $qty = $quantities[$i];

        $priceStmt = $conn->prepare("SELECT price FROM `product` WHERE productID = ?");
        $priceStmt->bind_param("i", $prodId);
        $priceStmt->execute();

        $priceResult = $priceStmt->get_result();
        $row = $priceResult->fetch_assoc();
        $unitPrice = $row['price'] ? $row['price'] : 0.0;

        $totalAmount += ($unitPrice * $qty);
        $priceStmt->close();
    }

    // 2. create order and get order_id
    $orderSql = "INSERT INTO `order` (customer_id, order_date, total_amount, order_state, updated_by, updated_time, update_reason) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $orderStmt = $conn->prepare($orderSql);
    $orderStmt->bind_param("isdssss", $customerId, $orderDate, $totalAmount, $orderState, $updatedBy, $updatedTime, $updateReason);
    $orderStmt->execute();
    $orderId = $orderStmt->insert_id;

    // 3. create order_detail
    $insertDetailSql = "INSERT INTO `order_details` (order_id, product_id, quantity, unit_price, remark) VALUES (?, ?, ?, ?, ?)";
    $insertDetailStmt = $conn->prepare($insertDetailSql);

    for ($i = 0; $i < count($productIds); $i++) {
        $prodId = $productIds[$i];
        $qty = $quantities[$i];
        $remark = $remarks[$i] ? $remarks[$i] : '';

        $priceStmt = $conn->prepare("SELECT price FROM `product` WHERE productID = ?");
        $priceStmt->bind_param("i", $prodId);
        $priceStmt->execute();

        $priceResult = $priceStmt->get_result();
        $row = $priceResult->fetch_assoc();
        $unitPrice = $row['price'] ? $row['price'] : 0.0;
        $priceStmt->close();

        $insertDetailStmt->bind_param("iiids", $orderId, $prodId, $qty, $unitPrice, $remark);
        $insertDetailStmt->execute();
    }

    // 4. create payment
    $paymentSql = "INSERT INTO `payment` (order_id, payment_method, payment_status, payment_date, amount_paid) VALUES (?, ?, ?, ?, ?)";
    $paymentStmt = $conn->prepare($paymentSql);
    $paymentStmt->bind_param("isssd", $orderId, $paymentMethod, $paymentStatus, $paymentDate, $totalAmount);
    $paymentStmt->execute();

    //if everything ok then commit
    $conn->commit();

    $response['success'] = true;
} catch (Exception $ex) {
    //if one of the stmt not ok then rollback
    $conn->rollback();

    $response['success'] = false;
    $response['message'] = $ex->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>