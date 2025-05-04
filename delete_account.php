<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in or invalid request.']);
    exit();
}

$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit();
}

$customer_id = $_POST['user_id']; 

// error_log("Deactivating user with ID: $customer_id");

$sql = "UPDATE customer SET status = 0 WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);

if ($stmt->execute()) {
    session_unset();
    session_destroy();
    header("Location: signup.php");  
    exit();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to deactivate account.']);
}

$stmt->close();
$conn->close();
?>
