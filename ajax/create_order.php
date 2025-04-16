<?php
// require_once 'db_connect.php';
// $host = "localhost";
// $username = "root";
// $password = "";
// $dbname = "cloud_testing";

$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$dbname = "cloud";

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
    $orderSql = "INSERT INTO `Order` (customer_id, order_date, total_amount, order_state) VALUES (?, ?, ?, ?)";

    $orderStmt = $conn->prepare($orderSql);
    $orderStmt->bind_param("isds", $customerId, $orderDate, $totalAmount, $orderState);
    $orderStmt->execute();
    $orderId = $orderStmt->insert_id;

    //create order_detail
    $selectSql = "SELECT c.product_id, c.quantity, p.price 
                FROM `Cart` c 
                INNER JOIN `Product` p 
                ON c.product_id = p.product_id 
                WHERE c.cart_id = ?";
    $selectStmt = $conn->prepare($selectSql);

    $insertDetailSql = "INSERT INTO `Order_Details` (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
    $insertDetailStmt = $conn->prepare($insertDetailSql);

    foreach($cart_ids as $cartId) {
        $selectStmt->bind_param("i", $cart_id);
        $selectStmt->execute();
        $result = $selectStmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $productId = $row['product_id'];
            $quantity = $row['quantity'];
            $unitPrice = $row['price'];

            $insertDetailStmt->bind_param("iiid", $orderId, $productId, $quantity, $unitPrice);
            $insertDetailStmt->execute();
        }

    }

    //create payment
    $paymentSql = "INSERT INTO `Payment` (order_id, payment_method, payment_status, payment_date, amount_paid) VALUES (?, ?, ?, ?, ?)";
    $paymentStmt = $conn->prepare($paymentSql);
    $paymentStmt->bind_param("isssd", $orderId, $paymentMethod, $paymentStatus, $paymentDate, $totalAmount);
    $paymentStmt->execute();
    
    //remove cart_id from cart
    $deleteSql = "DELETE FROM `Cart` WHERE cart_id = ?";
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