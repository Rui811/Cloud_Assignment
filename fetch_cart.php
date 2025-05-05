<?php
// include 'db_connect.php';
// $host = "chapalang-database1.cjpdewbot84w.us-east-1.rds.amazonaws.com";
// $username = "root";
// $password = "";
// $dbname = "cloud_testing";

$host = "chapalang-database1.cjpdewbot84w.us-east-1.rds.amazonaws.com";
$username = "main";
$password = "chapalang-password";
$dbname = "chapalang";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

// if (!isset($_SESSION['user_id'])) {
//     echo json_encode([]);
//     exit;
// }

// $customer_id = $_SESSION['user_id'];
$customer_id = $_POST['customer_id'];

$sql = "SELECT c.cart_id, p.productName, p.price, p.image, c.quantity, c.remark
        FROM cart c
        JOIN product p ON c.product_id = p.productID
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
