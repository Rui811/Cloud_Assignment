<?php
session_start();
// include 'db.php';
$host = "localhost";
$username = "root";
$password = "";
$dbname = "cloud_testing";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

$_SESSION['customer_id'] = 1;

if (!isset($_SESSION['customer_id'])) {
    echo json_encode([]);
    exit;
}

$customer_id = $_SESSION['customer_id'];//test

$sql = "SELECT c.cart_id, p.product_name, p.price, p.image, c.quantity
        FROM Cart c
        JOIN Products p ON c.product_id = p.product_id
        WHERE c.customer_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];

while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

echo json_encode($cartItems);
$conn->close();
?>
