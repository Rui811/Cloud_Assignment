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

$_SESSION['user_id'] = 1;

if (!isset($_SESSION['user_id'])) {
    $_SESSION['errorToast'] = "Invalid Request!";
    header("Location: product.php");
    exit();
    return;
}

if (!isset($_POST['productId'])) {
    $_SESSION['errorToast'] = "Invalid Request!";
    header("Location: product.php");
    exit();
    return;
}

$productId = $_POST['productId'];
$quantity = $_POST['quantity'];
$customerId = $_SESSION['user_id'];

$sql = "INSERT INTO `Cart` (customer_id, product_id, quantity) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $customerId, $productId, $quantity);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$conn->close();
?>