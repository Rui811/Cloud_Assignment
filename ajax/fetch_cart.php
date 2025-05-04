<?php
// include 'db_connect.php';
// $host = "localhost";
// $username = "root";
// $password = "";
// $dbname = "cloud_testing";

$host = "localhost";
$username = "nbuser";
$password = "abc12345";
$dbname = "cloud";

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
        FROM Cart c
        JOIN Product p ON c.product_id = p.productID
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
