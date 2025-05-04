<?php
// require_once 'db_connect.php';

$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
$username = "nbuser";
$password = "abc12345";
$dbname = "cloud";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$productId = $_POST['product_id'] ?? 0;

$sql = "SELECT category FROM `Product` WHERE productID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

$isCustomizable = false;
if ($product && strpos($product['category'], '3') !== false) {
    $isCustomizable = true;
}

echo json_encode(['customizable' => $isCustomizable]);
?>