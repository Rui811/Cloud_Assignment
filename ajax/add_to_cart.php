<?php

$host = "localhost";
$username = "nbuser";
$password = "abc12345";
$dbname = "cloud";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['productId'])) {
    // $_SESSION['errorToast'] = "Invalid Request!";
    // header("Location: product.php");
    echo "error";
    exit();
}

$productId = $_POST['productId'];
$quantity = $_POST['quantity'];
$customerId = $_POST['customerId'];
$remark = trim($_POST['remark']) !== "" ? $_POST['remark'] : null;

try {
    if ($remark === null) {
        $selectSql = "SELECT * FROM `Cart` WHERE customer_id = ? AND product_id = ? AND remark IS NULL";
        $selectStmt = $conn->prepare($selectSql);
        $selectStmt->bind_param("ii", $customerId, $productId);
    }
    else {
        $selectSql = "SELECT * FROM `Cart` WHERE customer_id = ? AND product_id = ? AND remark = ?";
        $selectStmt = $conn->prepare($selectSql);
        $selectStmt->bind_param("iis", $customerId, $productId, $remark);
    }

    $selectStmt->execute();
    $result = $selectStmt->get_result();

    //if exists, then update quantity
    //if not, then create new record
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $cartId = $row['cart_id'];
        $originalQty = $row['quantity'];
        $updatedQty = $originalQty + $quantity;

        $updateSql = "UPDATE `Cart` SET quantity = ? WHERE cart_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ii", $updatedQty, $cartId);

        if ($updateStmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    }
    else {
        $insertSql = "INSERT INTO `Cart` (customer_id, product_id, quantity, remark) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("iiis", $customerId, $productId, $quantity, $remark);

        if ($insertStmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    }

} catch (Exception $ex) {
    echo "error";
}

$conn->close();
?>