<?php
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_id = $_SESSION['user_id']; 

$sql = "SELECT cust_name, cust_email, cust_phone, cust_address FROM customer WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); 
} else {
    echo "User not found.";
    exit();
}

$conn->close();
?>