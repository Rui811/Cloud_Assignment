<?php

$host = "192.168.192.73";
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

try {
    $selectSql = "SELECT * FROM `Cart` WHERE customer_id = ? AND product_id = ?";
    $selectStmt = $conn->prepare($selectSql);
    $selectStmt->bind_param("ii", $customerId, $productId);
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    //if exists, then update quantity
    //if not, then create new record
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $originalQty = $row['quantity'];
        $updatedQty = $originalQty + $quantity;

        $updateSql = "UPDATE `Cart` SET quantity = ? WHERE customer_id = ? AND product_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("iii", $updatedQty, $customerId, $productId);

        if ($updateStmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    }
    else {
        $insertSql = "INSERT INTO `Cart` (customer_id, product_id, quantity) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("iii", $customerId, $productId, $quantity);

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