<?php
date_default_timezone_set('Asia/Kuala_Lumpur');

// require_once 'db_connect.php';
// $host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
// $username = "root";
// $password = "";
// $dbname = "cloud_testing";

$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$dbname = "chapalang";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['cart_ids'])) {
    $_SESSION['errorToast'] ="Invalid Request!";
    header("Location: cart.php");
    exit();
    return;
}

$cart_ids = $_POST['cart_ids'];
$paymentMethod = $_POST['payment_method'];
$totalAmount = $_POST['total_amount'];
$customerId = $_POST['customer_id'];

$orderDate = date('Y-m-d H:i:s');
$orderState = "Confirmed";
$paymentDate = $orderDate;
$paymentStatus = "Paid";

try {
    $conn->begin_transaction();

    //create order and get order_id
    $orderSql = "INSERT INTO `order` (customer_id, order_date, total_amount, order_state) VALUES (?, ?, ?, ?)";

    $orderStmt = $conn->prepare($orderSql);
    $orderStmt->bind_param("isds", $customerId, $orderDate, $totalAmount, $orderState);
    $orderStmt->execute();
    $orderId = $orderStmt->insert_id;

    //create order_detail
    $selectSql = "SELECT c.product_id, c.quantity, p.price, c.remark 
                FROM `cart` c 
                INNER JOIN product p 
                ON c.product_id = p.productID 
                WHERE c.cart_id = ?";
    $selectStmt = $conn->prepare($selectSql);

    $insertDetailSql = "INSERT INTO `order_details` (order_id, product_id, quantity, unit_price, remark) VALUES (?, ?, ?, ?, ?)";
    $insertDetailStmt = $conn->prepare($insertDetailSql);

    foreach($cart_ids as $cartId) {
        $selectStmt->bind_param("i", $cartId);
        $selectStmt->execute();
        $result = $selectStmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $productId = $row['product_id'];
            $quantity = $row['quantity'];
            $unitPrice = $row['price'];
            $remark = $row['remark'];

            if ($remark === null) {
                $remark = null;
            }

            $insertDetailStmt->bind_param("iiids", $orderId, $productId, $quantity, $unitPrice, $remark);
            $insertDetailStmt->execute();
        }

    }

    //create payment
    $paymentSql = "INSERT INTO payment (order_id, payment_method, payment_status, payment_date, amount_paid) VALUES (?, ?, ?, ?, ?)";
    $paymentStmt = $conn->prepare($paymentSql);
    $paymentStmt->bind_param("isssd", $orderId, $paymentMethod, $paymentStatus, $paymentDate, $totalAmount);
    $paymentStmt->execute();
    
    //remove cart_id from cart
    $deleteSql = "DELETE FROM cart WHERE cart_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);

    foreach($cart_ids as $cartId) {
        $deleteStmt->bind_param("i", $cartId);
        $deleteStmt->execute();
    }

    //if everything ok then commit
    $conn->commit();

    //return orderId
    $response = json_encode([
        "success" => true,
        "orderId" => $orderId
    ]);

} catch(Exception $ex) {
    //if one of the stmt not ok then rollback
    $conn->rollback();

    $response = json_encode([
        "success" => false,
        "message" => "Error: " . $ex->getMessage()
    ]);
}

echo $response;
?>