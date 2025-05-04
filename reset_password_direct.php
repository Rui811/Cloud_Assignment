<?php
// File: reset_password_direct.php
session_start();
header('Content-Type: application/json');

$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit();
}

if (!isset($_SESSION['reset_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'No user found in session.']);
    exit();
}

// Validate and hash password
$new_password = $_POST['new_password'] ?? '';
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_]).{8,}$/', $new_password)) {
    echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.']);
    exit();
}

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$user_id = $_SESSION['reset_user'];

// Update password in database
$stmt = $conn->prepare("UPDATE customer SET cust_psw = ? WHERE customer_id = ?");
$stmt->bind_param("ss", $hashed_password, $user_id);

if ($stmt->execute()) {
    unset($_SESSION['reset_user']);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Password update failed.']);
}

$stmt->close();
$conn->close();
